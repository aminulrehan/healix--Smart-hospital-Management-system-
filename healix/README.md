# 🏥 Healix — Smart Hospital Management System
### University Project | PHP + MySQL

---

## 📁 Project Folder Structure

```
healix/
│
├── index.php                  ← Dashboard (main landing page)
│
├── config/
│   └── db.php                 ← Database connection (edit credentials here)
│
├── includes/
│   ├── header.php             ← Navbar + global CSS (shared across all pages)
│   └── footer.php             ← Footer + Bootstrap JS
│
├── pages/
│   ├── patients.php           ← Register & view patients
│   ├── doctors.php            ← Add & view doctor profiles
│   ├── appointments.php       ← Book & manage appointments
│   ├── prescriptions.php      ← Issue & view prescriptions
│   └── bills.php              ← Generate & track bills
│
└── sql/
    └── healix.sql             ← Full database schema + sample data
```

---

## ⚙️ Setup Instructions (Step by Step)

### Step 1 — Install XAMPP
Download and install XAMPP from https://www.apachefriends.org  
Start **Apache** and **MySQL** from the XAMPP Control Panel.

### Step 2 — Create the Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click **"New"** in the left sidebar
3. Type database name: `healix` → Click **Create**
4. Click the **Import** tab at the top
5. Click **Choose File** → select `healix/sql/healix.sql`
6. Click **Go** at the bottom

### Step 3 — Place Project Files
Copy the entire `healix/` folder into:
```
C:\xampp\htdocs\healix\
```

### Step 4 — Configure Database Connection
Open `healix/config/db.php` and check these values:
```php
define('DB_HOST', 'localhost');   // Usually 'localhost'
define('DB_USER', 'root');        // Default XAMPP username
define('DB_PASS', '');            // Default XAMPP password (empty)
define('DB_NAME', 'healix');      // Your database name
```

### Step 5 — Run the Project
Open your browser and go to:
```
http://localhost/healix/
```

---

## 🖥️ Pages & Features

| Page | URL | Feature |
|------|-----|---------|
| Dashboard | `/index.php` | Overview stats + today's appointments |
| Patients | `/pages/patients.php` | Register patients, view list |
| Doctors | `/pages/doctors.php` | Add doctors, view profiles |
| Appointments | `/pages/appointments.php` | Book, update, cancel appointments |
| Prescriptions | `/pages/prescriptions.php` | Issue prescriptions for completed visits |
| Bills | `/pages/bills.php` | Generate bills, mark payments |

---

## 🗄️ Database Tables

| Table | Description |
|-------|-------------|
| `patients` | Patient personal info (name, age, gender, phone, blood group) |
| `doctors` | Doctor profiles (name, specialty, availability) |
| `appointments` | Appointment bookings (links patient + doctor + date) |
| `prescriptions` | Medicines and notes per appointment |
| `bills` | Financial records per patient |

---

## 🔧 Technologies Used

- **PHP 8+** — Server-side logic and database queries
- **MySQL** — Relational database (via XAMPP/phpMyAdmin)
- **Bootstrap 5** — Responsive grid and components
- **Bootstrap Icons** — Icon library
- **Google Fonts** — DM Serif Display + DM Sans typography

---

## 📌 Key Features

- ✅ Patient registration with blood group, contact, address
- ✅ Doctor management with specialty and availability
- ✅ Appointment booking with date, time, and reason
- ✅ Status management (Scheduled → Completed / Cancelled)
- ✅ Prescription issuance linked to completed appointments
- ✅ Billing system with payment tracking (Paid/Unpaid/Partial)
- ✅ Dashboard with live stats and today's schedule
- ✅ Clean, professional UI with a medical color theme
- ✅ SQL injection protection via `real_escape_string()`
- ✅ Commented code for easy understanding and presentation

---

*Built with PHP & MySQL for university submission.*
