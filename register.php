<?php 
session_start();
if(isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }

include 'functions.php'; 

if(isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        global $pdo;

        // 1. Email duplicate aahe ka check kara
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        
        if($check->rowCount() > 0) {
            $error = "This email is already registered!";
        } else {
            // 2. Password Hash karne (Security sathi)
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // 3. Data Insert karne
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if($stmt->execute([$name, $email, $hashed])) {
                // Success nantar login page var pathva
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Database error! Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ExpensePro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary: #4361ee; --dark: #0f172a; --muted: #64748b; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .bg-mesh {
            position: absolute; width: 100%; height: 100%;
            background: radial-gradient(circle at 10% 90%, rgba(67, 97, 238, 0.12) 0%, transparent 40%);
            z-index: -1;
        }

        .glass-container {
            display: flex;
            width: 85%;
            max-width: 950px;
            height: 520px; /* Reduced Height */
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(15px);
            border-radius: 35px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.4);
        }

        .brand-content {
            flex: 1; padding: 40px;
            display: flex; flex-direction: column; justify-content: center; color: white;
        }

        .brand-content h2 { font-weight: 800; font-size: 2.5rem; margin-bottom: 10px; }

        .auth-card-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; }

        .auth-card {
            background: #ffffff;
            width: 100%; max-width: 380px;
            border-radius: 28px;
            padding: 30px 35px; /* Reduced Padding */
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .form-label { font-size: 10px; font-weight: 700; color: var(--muted); margin-bottom: 4px; }

        .form-control {
            border: 1.5px solid #eef2f6;
            padding: 8px 12px; /* Slimmer Inputs */
            border-radius: 10px;
            background: #f8fafc;
            font-size: 13px;
        }

        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.08); }

        .btn-primary {
            background: var(--dark); border: none; padding: 10px;
            border-radius: 10px; font-weight: 700; font-size: 13px;
            width: 100%; margin-top: 10px;
        }

        .auth-footer { margin-top: 15px; text-align: center; font-size: 12px; color: var(--muted); }
        .auth-footer a { color: var(--primary); font-weight: 700; text-decoration: none; }

        @media (max-width: 768px) { .brand-content { display: none; } .glass-container { height: auto; } }
    </style>
</head>
<body>

<div class="bg-mesh"></div>

<div class="glass-container">
    <div class="brand-content">
        <h2 class="mb-2">Get Started.</h2>
        <p class="text-secondary small">Create your account to start tracking expenses with ExpensePro v2.0</p>
        <div class="mt-3 small text-secondary">
            <i class="fas fa-check-circle text-primary me-2"></i> Secure PostgreSQL Storage
        </div>
    </div>

    <div class="auth-card-wrapper">
        <div class="auth-card">
            <h4 class="fw-bold text-dark mb-1">Sign Up</h4>
            <p class="small text-muted mb-3">Enter details to register.</p>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-1 small mb-3 border-0" style="border-radius: 8px;"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-2">
                    <label class="form-label">FULL NAME</label>
                    <input type="text" name="name" class="form-control" placeholder="Sahil Rakshe" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">EMAIL ADDRESS</label>
                    <input type="email" name="email" class="form-control" placeholder="sahil@example.com" required>
                </div>

                <div class="row g-2">
                    <div class="col-6 mb-2">
                        <label class="form-label">PASSWORD</label>
                        <input type="password" name="password" class="form-control" placeholder="••••" required>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label">CONFIRM</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••" required>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-primary">
                    CREATE ACCOUNT <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="auth-footer">
                Already a member? <a href="login.php">Sign In</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>