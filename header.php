<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Expense Manager</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --bg-color: #f8f9fd;
            --sidebar-width: 250px;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* Modern Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, #2b2d42 0%, #1a1b2e 100%);
            color: white;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 15px 25px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            border-left: 4px solid transparent;
            text-decoration: none;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.05);
            color: white !important;
            border-left: 4px solid var(--primary-color);
        }

        .nav-link i { margin-right: 15px; width: 20px; }

        /* Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: all 0.3s;
        }

        .top-nav {
            background: white;
            padding: 15px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Stats Cards */
        .stat-card {
            border: none;
            border-radius: 15px;
            padding: 25px;
            background: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
            transition: transform 0.3s;
        }

        .stat-card:hover { transform: translateY(-5px); }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .btn-primary { background-color: var(--primary-color); border: none; border-radius: 8px; padding: 10px 20px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="fw-bold mb-0 text-white">ExpensePro</h4>
        <small class="text-muted">Smart Tracker</small>
    </div>
    <div class="mt-4">
        <a href="index.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : '' ?>">
            <i class="fas fa-th-large"></i> Dashboard
        </a>

        <a href="add_expense.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'add_expense.php') ? 'active' : '' ?>">
            <i class="fas fa-plus-circle"></i> Add Expense
        </a>

        <a href="view_expenses.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'view_expenses.php') ? 'active' : '' ?>">
            <i class="fas fa-history"></i> Transaction History
        </a>

        <hr class="mx-3 my-2 opacity-10"> <a href="settings.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : '' ?>">
            <i class="fas fa-sliders-h"></i> Budget Settings
        </a>

        <a href="reports.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'reports.php') ? 'active' : '' ?>">
            <i class="fas fa-chart-line"></i> Analytics Reports
        </a>

        <a href="manage_categories.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'manage_categories.php') ? 'active' : '' ?>">
            <i class="fas fa-tags"></i> Categories
        </a>

        <hr class="mx-3 my-2 opacity-10">

        <a href="profile.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : '' ?>">
            <i class="fas fa-user-cog"></i> My Profile
        </a>

        <a href="about.php" class="nav-link <?= (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : '' ?>">
            <i class="fas fa-info-circle"></i> About Project
        </a>

        <a href="logout.php" class="nav-link mt-4 text-danger border-0">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <div class="top-nav">
        <h5 class="mb-0 fw-bold">Overview</h5>
        <div class="d-flex align-items-center">
            <span class="me-3">Hi, <strong>Sahil</strong></span>
            <img src="https://ui-avatars.com/api/?name=Sahil+Rakshe&background=4361ee&color=fff" class="rounded-circle" width="35">
        </div>
    </div>