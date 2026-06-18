<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../helpers/env.php';
load_env(__DIR__ . '/../../.env');

$input = json_decode(file_get_contents('php://input'), true);
$interests = trim($input['interests'] ?? '');

if (!$interests) {
    echo json_encode(["error" => "No interests provided."]);
    exit;
}

// ✅ Gemini 2.5 Flash model confirmed
$apiKey = getenv('GEMINI_API_KEY') ?: '';$model = "gemini-2.5-flash";
$url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent?key=$apiKey";

// 🇿🇦 South Africa–specific prompt
$prompt = <<<PROMPT
You are an expert South African career advisor.
Your task is to output ONLY valid JSON.

Based on the interests below, suggest 3–5 potential careers.

Interests: "$interests"

Each object must have:
{
"title": "Career title",
"description": "2–3 sentences explaining what the career involves",
"skills": "comma-separated key skills",
"matchReason": "why it fits the user's interests",
"salaryRange": "estimated monthly salary range in South African Rand (ZAR)",
"demandStatus": "High Demand, Moderate Demand, or Low Demand — based on South African job market trends"
}

Ensure the salaries are realistic for South Africa and demandStatus is accurate. Return only the JSON array — no markdown, no text outside JSON.
PROMPT;

$payload = json_encode([
    "contents" => [
        ["parts" => [["text" => $prompt]]]
    ]
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Connection: keep-alive",
        "Accept-Encoding: gzip"
    ],
    CURLOPT_ENCODING => "gzip",
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_TIMEOUT => 30,
]);
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(["error" => "Curl error: $error"]);
    exit;
}
if (!$response) {
    echo json_encode(["error" => "No response received from Gemini API."]);
    exit;
}

$data = json_decode($response, true);

/* ✅ Extract text from response */
$text = '';
if (isset($data['candidates'][0]['content']['parts'])) {
    foreach ($data['candidates'][0]['content']['parts'] as $p) {
        if (isset($p['text'])) $text .= $p['text'] . "\n";
    }
}
if (!$text && isset($data['candidates'][0]['output'])) {
    $text = $data['candidates'][0]['output'];
}

if (!$text) {
    echo json_encode(["error" => "Empty AI response. Raw data: " . substr(json_encode($data), 0, 400)]);
    exit;
}

/* 🧼 Clean & parse JSON */
$clean = preg_replace('/^[^{[]+/', '', $text);
$clean = preg_replace('/```(?:json)?|```/', '', $clean);
$clean = trim($clean);

try {
    $parsed = json_decode($clean, true);
    if (!is_array($parsed)) {
        throw new Exception("Invalid JSON format from Gemini.");
    }

    $out = "";
    foreach ($parsed as $i => $c) {
        $title = $c['title'] ?? 'Unknown Career';
        $desc = $c['description'] ?? 'No description available.';
        $skills = $c['skills'] ?? 'N/A';
        $reason = $c['matchReason'] ?? 'N/A';
        $salary = $c['salaryRange'] ?? 'N/A';
        $demand = strtolower($c['demandStatus'] ?? 'moderate demand');

        // ✅ Demand indicator
        $demandIcon = "⚠️";
        $demandLabel = "Moderate Demand";
        if (str_contains($demand, 'high')) {
            $demandIcon = "✅";
            $demandLabel = "In-Demand in South Africa";
        } elseif (str_contains($demand, 'low')) {
            $demandIcon = "❌";
            $demandLabel = "Low Demand / Limited Opportunities";
        }

        $out .= "🎯 $title\n$desc\n\n🧠 Skills: $skills\n💡 Why it matches: $reason\n💰 Salary Range: $salary\n$demandIcon $demandLabel\n";
        if ($i < count($parsed) - 1) $out .= "\n───────────────\n\n";
    }

    echo json_encode(["message" => $out]);
} catch (Exception $e) {
    echo json_encode([
        "error" => "Failed to parse AI response. Raw text (first 400 chars): " . substr($text, 0, 400)
    ]);
}
?>
