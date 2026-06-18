# Security Notes

The original project included a hardcoded Gemini API key in PHP files. Those values were removed.

Before pushing to GitHub:

1. Keep `.env` out of Git.
2. Put real credentials only in `.env` on the server.
3. Rotate the old Gemini API key if it was ever exposed publicly.
4. Import `database/edupath.sql` only into trusted database environments.
