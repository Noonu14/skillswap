# SkillSwap Circle (V3) - Setup & User Guide

## What's New in V3?
- **Login with Unique ID**: No more emails. Use `alice_123` to login.
- **Security Questions**: Forgot Password flow added.
- **Chat Fix**: Message alignment issue resolved.

## Deployment Steps

1.  **Initialize Database (Required)**:
    - Run: `http://localhost:8000/setup_v3.php`
    - (Or `http://localhost/skillswap/setup_v3.php` depending on your server setup)
    - **Warning**: This resets all user data.

2.  **Start Application**:
    - Go to: `http://localhost:8000/index.php`

## User Flow
1.  **Register**: Create a User ID and answer 3 security questions.
2.  **Login**: Use the User ID.
3.  **Chat**: Messages will now align correctly (Yours on right, Partner's on left).
4.  **Forgot Password**: Click the link on login page -> Answer questions -> Reset.

## Troubleshooting
- If chat bubbles are mismatched, clear your browser cache (Ctrl+F5) to reload the new Javascript.
- If "Too many attempts" during password reset, wait or clear cookies (session-based).
