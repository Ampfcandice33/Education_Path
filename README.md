# EduPath

EduPath is a PHP-based education and career guidance website with AI-powered quiz generation and career exploration.

## Clean repository structure

```text
EduPath/
├── public/              # Website pages / web root
├── app/                 # PHP config, API, auth, includes, helpers
├── assets/              # CSS, JavaScript, images
├── database/            # SQL import file
├── storage/uploads/     # Runtime uploads, ignored by Git except .gitkeep
└── legacy/              # Experimental Spring Boot and Node API files
```

## Setup

1. Import `database/edupath.sql` into MySQL/MariaDB.
2. Copy `.env.example` to `.env`.
3. Update database credentials and add your Gemini API key in `.env`.
4. Point your server document root to the `public/` folder.

For shared hosting where you cannot change the document root, upload the contents of `public/` to `public_html/`, then keep `app/`, `assets/`, `database/`, and `storage/` one level above or adjust paths carefully.

## Security notes

- Do not commit `.env`.
- API keys were removed from PHP files and must be loaded from `.env`.
- `storage/uploads/` is ignored by Git except for `.gitkeep`.

## Legacy folders

`legacy/springboot/` and `legacy/node-api/` are preserved for reference. The current main project is the PHP version.

---

## Original README content

# 🧠 EduPath

EduPath is an **AI-powered education and career platform** designed to help students explore career paths, generate quizzes from study materials, and access academic tools through a unified, modern web interface.  
Built with a **Node.js frontend**, **PHP backend**, and **MariaDB database**, EduPath combines responsive UI design with intelligent functionality.

---

## 🚀 Tech Stack

| Layer | Technology | Description |
|--------|-------------|-------------|
| **Frontend** | **Node.js**, **Tailwind CSS**, **Lucide Icons** | Dynamic UI rendering, responsive navigation, and interactive components. |
| **Backend** | **PHP 8+** | Handles authentication, API endpoints, and database interaction. |
| **Database** | **MariaDB** | Stores user profiles, quiz data, events, and career exploration insights. |

---

## ✨ Key Features

### 🏠 Dynamic Landing Page
- Interactive navigation bar with **dropdowns and mobile support**  
- Sections: Home, About Us, Connect, Announcements, Student Portal, AI Tools, and Gallery  

### 🤖 AI Tools
- **AI Quiz Generator**: Upload notes or paste text to generate quizzes  
- **AI Career Explorer**: Get personalized career suggestions with match percentages  

### 🎓 Student Portal
- View grades, attendance, assignments, and AI learning tools  
- Different modes for **High School** and **University** students  

### 📰 Announcements & Events
- Live pages for **News**, **Academic Calendar**, and **Upcoming Events**

### 📸 Photo Gallery
- Modern grid layout featuring campus life, workshops, and student highlights  

### 👥 Staff Directory & Contact
- Organized **staff listing** and responsive **contact form**

---

## 🧩 Folder Structure (Recommended)


