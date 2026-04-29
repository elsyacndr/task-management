# Task Management System Dashboard

A complete, structured web application built with HTML, CSS, JavaScript, PHP (OOP), and MySQL. This project is designed as a real-world portfolio piece demonstrating CRUD operations, Object-Oriented Programming, database integration, and a clean, modern UI dashboard.

---

## 🚀 Features

- **User Authentication**: Secure registration/login with password hashing & sessions
- **Full CRUD Operations**: Tasks & Projects
- **Task Management**:
  - Create tasks with project assignment, category, due date
  - Overdue detection with visual alerts (pulsing red badges)
  - Status toggle (Pending/Completed)
  - Modal editing without page reload
  - Delete with confirmation
- **Advanced Filtering**: Status, category, search
- **Dashboard Statistics**: Total, completed, pending, overdue counts
- **Project Organization**: Group tasks by projects
- **Responsive Design**: Mobile-first with collapsible sidebar
- **Security**: PDO prepared statements, input sanitization

---

## 🛠 Technology Stack

| Frontend | Backend | Database | Styling | Other |
|----------|---------|----------|---------|-------|
| HTML5, Bootstrap 5 | PHP 8+ OOP | MySQL | Custom CSS (Glassmorphism) | Chart.js |

---

## 🗄 Complete Database Schema

```sql
CREATE DATABASE task_management;
USE task_management;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_id INT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50) DEFAULT 'General',
    status ENUM('pending', 'completed') DEFAULT 'pending',
    due_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);
```

---

## ⚡ Quick Start (XAMPP)

1. **Start Apache + MySQL** (XAMPP Control Panel)
2. **Import DB schema** to phpMyAdmin (`task_management`)
3. **Open browser**: `http://localhost/Task Management/`
4. **Register → Login → Create projects/tasks → Dashboard**

---

## 📁 Project Structure

```
├── index.php (Login)
├── register.php
├── pages/
│   ├── dashboard.php (Stats)
│   ├── tasks.php (List/Filter)
│   ├── create_task.php (Form)
│   ├── projects.php (CRUD)
│   ├── manage_task.php (Actions)
│   └── profile.php
├── classes/ (OOP)
│   ├── Database.php
│   ├── User.php
│   ├── Task.php
│   └── Project.php
├── config/database.php
├── assets/css/style.css (Palette)
├── includes/ (Layout)
└── docs/README.md
```

---

## 🎨 Design System

**Strict Color Palette:**
- Background: `#4C1D3D`
- Primary: `#DC586D`
- Accent: `#FFBB94`
- Cards: Glassmorphism effect

**Key Features Demonstrated:**
- **OOP PHP**: Classes for Database/User/Task/Project
- **Security**: PDO, hashing, sanitization
- **Modern UI**: Glassmorphism, animations, responsive
- **Real-world**: Overdue alerts, project grouping

---

*Production-ready junior developer portfolio project. Perfect for certifications & interviews! 🚀*
