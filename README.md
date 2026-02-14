<p align="center">
  <img width="1920" height="1200" alt="Screenshot (51)" src="https://github.com/user-attachments/assets/e7b414ea-9812-487e-87f1-e228433c4532" />

</p>

# Skill Swap Name 🎯

## Basic Details

### Team Name: Neenu

### Team Members
- Member 1: Neenu - LBS COLLEGE OF ENGINEERING, KASARGOD

### Hosted Project Link
https://skillswaps.page.gd/

### Project Description
A web platform where women can:
✨Offer one skill
✨Request one skill
✨Get matched automatically

Example:
“I can teach Canva design”
“I want to learn basic budgeting”
→ Platform matches compatible users.

### The Problem statement
Most platforms:
 One-sided (mentor → learner)
 Paid
 Passive listings

SkillSwap Circle:
 Two-sided reciprocity
 Instant logical matching
 Community-driven micro-economy

### The Solution
Exchange Skills. Empower Each Other
 I created a platform where people can connect with eachother and share there skills and teach eachother to upbring themselves.

---

## Technical Details

### Technologies/Components Used

**For Software**

Languages used: HTML, CSS, JavaScript, PHP, SQL
Frameworks used: None (Pure Vanilla PHP)
Libraries used: None
Tools used: VS Code, XAMPP (Apache + MySQL), PHPMyAdmin

**For Hardware**

Main components: Standard PC/Laptop (Server Host)
Specifications: 4GB+ RAM, 100MB+ Storage
Tools required: Web Browser
---

##Features
List the key features of your project:

-User Authentication: Secure Login/Registration with Unique ID and hashed passwords.
-Smart Matching Engine: Automatically pairs users based on complementary skills (Offer vs Need).
-Real-time Chat: Continuous messaging system with strict sender/receiver alignment.
-Security Check: Password recovery flow using personal security questions.

---

## Implementation

### For Software:

#### Installation
```bash
# 1. Install XAMPP
# 2. Copy 'skillswap' folder to C:\xampp\htdocs\
# 3. Start Apache and MySQL in XAMPP Control Panel
```

#### Run
```bash
# Open in Browser:
http://localhost/skillswap/setup_v3.php  # (First time only)
http://localhost/skillswap/index.php     # (To use the app)
```

### For Hardware:

#### Components Required
Not Applicable (Software-only project)

#### Circuit Setup
Not Applicable

---

## Project Documentation

### For Software:

#### Screenshots (Add at least 3)

<img width="1920" height="1200" alt="Screenshot (52)" src="https://github.com/user-attachments/assets/9a9a67ef-aa1b-46dc-ab52-c2d257f40187" />
first time login

<img width="1920" height="1200" alt="Screenshot (51)" src="https://github.com/user-attachments/assets/1e7925e2-c6a9-46f2-9863-a8962ff5c53e" />
login page

<img width="1920" height="1200" alt="Screenshot (50)" src="https://github.com/user-attachments/assets/a7dabecf-5dbb-4784-b7b2-19e7b6ed99b5" />
home page

<img width="1920" height="1200" alt="Screenshot (31)" src="https://github.com/user-attachments/assets/31a17ae4-6f36-4a1f-9364-c74dba6f6eb2" />
chat between mentor and learner





#### Diagrams

**System Architecture**
An explanation of the LAMP/WAMP stack component interaction and a Mermaid diagram visualizing the Client -> Server -> DB flow.
**Application Workflow**
A step-by-step user journey description and a Mermaid sequence diagram showing the Registration -> Matching -> Chat process.

---

### For Hardware:

#### Schematic & Circuit
*Not Applicable for this software-only project.*

![Circuit](N/A)
*No circuit required as this runs on standard PC hardware.*

![Schematic](N/A)

#### Build Photos

![IMG_9978](https://github.com/user-attachments/assets/db7b025b-9a24-4828-bb28-dde47a05ba71)


![Components]

<img width="1920" height="1200" alt="Screenshot (50)" src="https://github.com/user-attachments/assets/a7dabecf-5dbb-4784-b7b2-19e7b6ed99b5" />
home page
1. Frontend Pages (User Interface)

index.php
: The public landing page.

register.php
: User registration form (with Unique ID & Security Questions).

login.php
: Secure login interface.

home_page.php
: The main user dashboard that displays profile info, notifications, and matches.

chat.php
: The real-time messaging interface.

forgot_password.php
: Password recovery page.

style.css
: Contains all visual styles (Glassmorphism, Gradients, Responsive Layouts).

2. Backend Logic (Core System)

<img width="1027" height="498" alt="Screenshot 2026-02-14 070607" src="https://github.com/user-attachments/assets/84c09e74-4391-49ee-aacd-f56651067f92" />

db.php
: Database connection handler.

auth.php
: Session management and security checks.

match_engine.php
: The algorithm that automatically finds and pairs users based on "Skills Offered" vs "Skills Needed".

fetch_messages.php
: API endpoint that retrieves chat history (JSON).

send_message.php
: API endpoint that saves new messages to the database.

setup_v3.php
: Utility script to initialize the database schema.

3. Database Components (MySQL Tables)

<img width="1920" height="1200" alt="Screenshot (54)" src="https://github.com/user-attachments/assets/46cd4491-ff28-4a7c-b58a-3ed00379e4ed" />

users: Stores user profiles (Unique ID, Hashed Password, Skills).
matches: Tracks active mentorship pairs.
messages: Stores chat history.
notifications: Stores system alerts.
que_ans: Stores security answers for password recovery.


![Build](Add photos of build process here)
*Explain the build steps*

![Final](Add photo of final product here)
*Explain the final build*

---

## Additional Documentation

### For Web Projects with Backend:

#### API Documentation

**Base URL:** https://skillswaps.page.gd/

##### Endpoints

**GET /api/endpoint**
- **Description:** connect two people to share there skills and grow
- **Parameters:**
  - `param1` (string): [Description]
  - `param2` (integer): [Description]
- **Response:**
```json
{
  "status": "success",
  "data": {}
}
```

**POST /api/endpoint**
- **Description:** [What it does]
- **Request Body:**
```json
{
  "field1": "value1",
  "field2": "value2"
}
```
- **Response:**
```json
{
  "status": "success",
  "message": "Operation completed"
}
```

[Add more endpoints as needed...]

---

### For Mobile Apps:

#### App Flow Diagram
not designed for mobile

### For Hardware Projects:

#### Bill of Materials (BOM)
free

**Total Estimated Cost:** 0₹

#### Assembly Instructions

Get in the link provided
create your profile by answering some questions
login withpassword and user ID
chat with mentors and learners


#### Demo Output

## Project Demo

### Video
https://drive.google.com/file/d/1QrNI8on9oa30P8pycfav7fmbUzLGXdjI/view?usp=drive_link


### Additional Demos
[Add any extra demo materials/links - Live site, APK download, online demo, etc.]

---

## AI Tools Used (Optional - For Transparency Bonus)

If you used AI tools during development, document them here for transparency:

**Tool Used:** [e.g., GitHub Copilot, v0.dev, Cursor, ChatGPT, Claude]

**Purpose:** [What you used it for]
- Example: "Generated boilerplate React components"
- Example: "Debugging assistance for async functions"
- Example: "Code review and optimization suggestions"

**Key Prompts Used:**
- "Create a REST API endpoint for user authentication"
- "Debug this async function that's causing race conditions"
- "Optimize this database query for better performance"

**Percentage of AI-generated code:** [Approximately X%]

**Human Contributions:**
- Architecture design and planning
- Custom business logic implementation
- Integration and testing
- UI/UX design decisions


Made with ❤️ at TinkerHub
