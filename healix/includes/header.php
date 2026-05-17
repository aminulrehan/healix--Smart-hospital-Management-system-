<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Healix — <?= $page_title ?? 'Hospital Management' ?></title>

    <!-- Google Fonts: DM Serif Display + DM Sans -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ============================================================
           HEALIX — Global Design System
           Medical aesthetic: clean, trustworthy, professional
        ============================================================ */

        :root {
            --primary:      #0a5c6b;   /* Deep teal */
            --primary-light:#0e8fa8;
            --primary-pale: #e6f5f8;
            --accent:       #f0a500;   /* Amber accent */
            --accent-pale:  #fff8e6;
            --danger:       #d63c3c;
            --success:      #1a8a5a;
            --warning:      #e88c00;
            --neutral-50:   #f8fafb;
            --neutral-100:  #f0f4f5;
            --neutral-200:  #dde5e8;
            --neutral-700:  #3d5a61;
            --neutral-900:  #0d2226;
            --font-heading: 'DM Serif Display', Georgia, serif;
            --font-body:    'DM Sans', system-ui, sans-serif;
            --radius:       10px;
            --shadow-sm:    0 2px 8px rgba(10,92,107,0.08);
            --shadow-md:    0 4px 20px rgba(10,92,107,0.12);
            --shadow-lg:    0 8px 40px rgba(10,92,107,0.16);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background-color: var(--neutral-100);
            color: var(--neutral-900);
            margin: 0;
            min-height: 100vh;
        }

        /* ---- NAVBAR ---- */
        .healix-navbar {
            background: var(--primary);
            padding: 0 0;
            box-shadow: 0 2px 12px rgba(10,92,107,0.25);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .healix-navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .navbar-brand-healix {
            font-family: var(--font-heading);
            font-size: 1.75rem;
            color: #fff !important;
            text-decoration: none;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-brand-healix .brand-dot {
            width: 8px; height: 8px;
            background: var(--accent);
            border-radius: 50%;
            display: inline-block;
        }
        .navbar-links {
            display: flex;
            gap: 4px;
            align-items: center;
        }
        .navbar-links a {
            color: rgba(255,255,255,0.78);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 6px 14px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .navbar-links a:hover,
        .navbar-links a.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
        }
        .navbar-links a.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        /* ---- PAGE WRAPPER ---- */
        .page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 36px 24px 60px;
        }

        /* ---- PAGE HEADER ---- */
        .page-header {
            margin-bottom: 32px;
        }
        .page-header h1 {
            font-family: var(--font-heading);
            font-size: 2rem;
            color: var(--primary);
            margin: 0 0 4px;
        }
        .page-header p {
            color: var(--neutral-700);
            font-size: 0.95rem;
            margin: 0;
        }

        /* ---- CARDS ---- */
        .healix-card {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--neutral-200);
            box-shadow: var(--shadow-sm);
            padding: 28px;
        }
        .healix-card .card-title {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--primary-pale);
        }

        /* ---- FORM ELEMENTS ---- */
        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--neutral-700);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .form-control, .form-select {
            border: 1.5px solid var(--neutral-200);
            border-radius: 7px;
            padding: 10px 14px;
            font-family: var(--font-body);
            font-size: 0.95rem;
            color: var(--neutral-900);
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--neutral-50);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(14,143,168,0.12);
            outline: none;
            background: #fff;
        }

        /* ---- BUTTONS ---- */
        .btn-healix-primary {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 11px 20px;
            font-weight: 600;
            font-size: 0.95rem;
            font-family: var(--font-body);
            width: 100%;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-healix-primary:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }
        .btn-healix-accent {
            background: var(--accent);
            color: #fff;
        }
        .btn-healix-accent:hover { background: var(--warning); }

        /* ---- TABLE ---- */
        .healix-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }
        .healix-table thead th {
            background: var(--primary-pale);
            color: var(--primary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 12px 16px;
            border-bottom: 2px solid var(--neutral-200);
            white-space: nowrap;
        }
        .healix-table tbody tr {
            transition: background 0.15s;
        }
        .healix-table tbody tr:hover {
            background: var(--neutral-50);
        }
        .healix-table tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid var(--neutral-100);
            color: var(--neutral-900);
            vertical-align: middle;
        }
        .healix-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ---- BADGES ---- */
        .badge-healix {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .badge-success  { background: #e6f5ee; color: var(--success); }
        .badge-danger   { background: #fdeaea; color: var(--danger); }
        .badge-warning  { background: var(--accent-pale); color: var(--warning); }
        .badge-secondary{ background: var(--neutral-100); color: var(--neutral-700); }
        .badge-primary  { background: var(--primary-pale); color: var(--primary); }

        /* ---- ALERT ---- */
        .healix-alert {
            border-radius: 8px;
            padding: 13px 18px;
            font-size: 0.92rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            animation: slideIn 0.3s ease;
        }
        .healix-alert-success {
            background: #e6f5ee;
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        .healix-alert-danger {
            background: #fdeaea;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ---- STATS ---- */
        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--neutral-200);
            padding: 22px 24px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s;
        }
        .stat-card:hover { box-shadow: var(--shadow-md); }
        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .stat-icon-teal   { background: var(--primary-pale); color: var(--primary); }
        .stat-icon-amber  { background: var(--accent-pale);  color: var(--warning); }
        .stat-icon-green  { background: #e6f5ee; color: var(--success); }
        .stat-icon-red    { background: #fdeaea; color: var(--danger); }
        .stat-value {
            font-family: var(--font-heading);
            font-size: 2rem;
            color: var(--primary);
            line-height: 1;
        }
        .stat-label {
            font-size: 0.82rem;
            color: var(--neutral-700);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 3px;
        }

        /* ---- EMPTY STATE ---- */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--neutral-700);
        }
        .empty-state i {
            font-size: 3rem;
            opacity: 0.3;
            display: block;
            margin-bottom: 12px;
        }

        /* ---- RESPONSIVE ---- */
        @media (max-width: 768px) {
            .page-wrapper { padding: 20px 16px 40px; }
            .healix-table { font-size: 0.82rem; }
            .healix-table thead th,
            .healix-table tbody td { padding: 10px 10px; }
        }
    </style>
</head>
<body>

<?php
// ============================================================
// AUTO-DETECT BASE URL — works no matter where the folder lives
// e.g. localhost/healix OR localhost/healix_project/healix
// ============================================================
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];          // e.g. /healix/pages/patients.php

// Walk up the directory tree to find index.php (the project root)
$parts    = explode('/', trim($script, '/'));  // ['healix', 'pages', 'patients.php']
$depth    = (in_array('pages', $parts)) ? 1 : 0;  // pages/ is 1 level deep
$base     = $protocol . '://' . $host . '/' . implode('/', array_slice($parts, 0, count($parts) - $depth - 1));

// $base is now e.g. http://localhost/healix  OR  http://localhost/healix_project/healix
?>

<!-- ============================================================
     NAVIGATION BAR
============================================================ -->
<nav class="healix-navbar">
    <div class="container">
        <!-- Brand Logo -->
        <a href="<?= $base ?>/index.php" class="navbar-brand-healix">
            <span class="brand-dot"></span> Healix
        </a>

        <!-- Navigation Links -->
        <div class="navbar-links">
            <a href="<?= $base ?>/index.php" class="<?= ($page_title == 'Dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="<?= $base ?>/pages/patients.php" class="<?= ($page_title == 'Patients') ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Patients
            </a>
            <a href="<?= $base ?>/pages/doctors.php" class="<?= ($page_title == 'Doctors') ? 'active' : '' ?>">
                <i class="bi bi-person-badge"></i> Doctors
            </a>
            <a href="<?= $base ?>/pages/appointments.php" class="<?= ($page_title == 'Appointments') ? 'active' : '' ?>">
                <i class="bi bi-calendar-check"></i> Appointments
            </a>
            <a href="<?= $base ?>/pages/prescriptions.php" class="<?= ($page_title == 'Prescriptions') ? 'active' : '' ?>">
                <i class="bi bi-file-earmark-medical"></i> Prescriptions
            </a>
            <a href="<?= $base ?>/pages/bills.php" class="<?= ($page_title == 'Bills') ? 'active' : '' ?>">
                <i class="bi bi-receipt"></i> Bills
            </a>
        </div>
    </div>
</nav>

<!-- ============================================================
     PAGE CONTENT STARTS HERE
============================================================ -->
<div class="page-wrapper">
