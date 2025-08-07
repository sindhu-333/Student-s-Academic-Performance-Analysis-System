
# 🎓 Student Performance Analysis System

A web-based academic monitoring tool that helps schools and colleges track student performance in midterms and final exams. This system provides separate dashboards for students, staff, and admins to manage records, analyze performance, and identify students who require additional focus.

## 🚀 Features

- **Role-Based Dashboards:** Separate login panels for admin, staff, and students  
- **Result Entry:** Staff can add and update marks for midterm or final exams  
- **Focus Required Module:** Highlights underperforming students for extra attention  
- **Performance Reports:** Graphical analysis using bar and pie charts  
- **Password Recovery:** Secure password reset via hint question and answer  
- **Student Registration:** Admin can manage students, staff, and subjects  
- **Responsive UI:** Clean interface built using Bootstrap for all screen sizes  


### ⚙️ Prerequisites

- WAMP/XAMPP (Apache, PHP, MySQL)
- PHP 7.4+ with `mysqli`, `session`, and `openssl` extensions
- MySQL 5.7+ or MariaDB
- Modern web browser (Chrome, Edge, Firefox)

### 🛠️ Installation

#### Clone or Download the Project

```bash
git clone https://github.com/your-username/student-performance-system.git
cd student-performance-system
```

#### Place in Server Directory

Move the project folder to `C:/wamp64/www/` or `htdocs` (XAMPP).

#### Start WAMP/XAMPP

Enable Apache and MySQL.

#### Database Setup

1. Open phpMyAdmin  
2. Create a database: `student_system`  
3. Import `student_system.sql` from the project  

#### Configure Database in `conn.php`

```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "student_system";
$conn = mysqli_connect($host, $user, $pass, $db);
```

### 💾 Database Configuration 

- **Users Table:** Stores login, role, hint question and answer  
- **Students, Staff, Subjects:** For performance and result mapping  
- **Results Table:** Stores midterm/final marks per subject  
- **Hint-based password reset** implemented using fields: `hint_qn` and `hint_ans`  

## 📁 Project Structure

```bash
student-performance-system/
├── assets/                 # CSS, JS, images
├── includes/               # Common includes (conn.php, nav.php)
├── admin/                  # Admin panel
├── staff/                  # Staff login, mark entry, analysis
├── student/                # Student result viewing dashboard
├── charts/                 # Chart data generators
├── reset/                  # Forgot and reset password pages
├── index.html              # Landing/Login page
├── forgot-password.php     # Unified PHP backend for reset
└── student_system.sql      # MySQL database dump
```

## 🧑‍💻 Technologies Used

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap  
- **Backend:** PHP (procedural)  
- **Database:** MySQL  
- **Visualization:** Chart.js  
- **Server:** Apache (via WAMP/XAMPP)  
- **Security:** PHP Sessions, password hashing  

## 📌 How to Use

### 👩‍🏫 For Admin

- Login to the admin dashboard  
- Add or manage students, staff, and subjects  
- View charts and analysis reports  
- Identify students needing academic focus  

### 👨‍🏫 For Staff

- Login with staff credentials  
- Add or update midterm/final marks  
- Use chart modules to visualize results  
- Access “Focus Required” report  

### 👨‍🎓 For Students

- Login using student ID  
- View current and past exam results  
- Analyze subject-wise progress using charts  

## 🔑 Password Reset

- Use the "Forgot Password" link on login page  
- Enter your username and hint answer  
- Set a new password if validated successfully  

## 🔐 Security Features

- Password Hashing with `password_hash()` and `password_verify()`  
- Role-based Session Management for Admin, Staff, and Student  
- Input Validation on both client and server  
- Prepared Statements for SQL (where applicable)  
- Restricted Access to internal pages via session checks  

## ⚙ Configuration Required

- Edit `conn.php` with your own database credentials if different  
- Ensure `assets/`, `charts/`, and `reset/` folders are accessible  
- Enable `session_start()` in all PHP files requiring session control  

## 🐞 Troubleshooting

| Issue                  | Solution                                                                   |
|------------------------|----------------------------------------------------------------------------|
|* Login Not Working     | * Check users table for correct username and password (hashed)             |
|* Charts Not Showing    | * Ensure Chart.js is loaded correctly and PHP chart endpoints are working  |
|* CSS/JS Not Loading    | * Check correct file paths, use browser console to debug 404s              |
|* DB Connection Failed  | * Verify conn.php credentials and MySQL server status                      |
|* Reset Password Fails  | * Confirm hint question and answer match the DB values for that username   |

## 📝 Development Notes

- Use simple PHP for easy portability and student-level clarity  
- Modular folders allow scalable enhancement  
- AJAX used for password reset without page reload  

## 🧑‍💻 Contributing

1. Fork the repository  
2. Create a branch: `git checkout -b feature/new-feature`  
3. Commit: `git commit -m "Added new feature"`  
4. Push: `git push origin feature/new-feature`  
5. Open a pull request  

##📜 License & Credits

**Author:** Sindhu Bhat  
**Institution:** JSS SMI UG & PG Studies, Dharwad  
**License:** Academic Use Only — Do not redistribute without permission.

**Third-Party Libraries Used:**  
- Chart.js – MIT License  
- Bootstrap – MIT License  
- Font Awesome – Free License  
