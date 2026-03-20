<?php 
ob_start(); // १. हेडर एरर्स टाळण्यासाठी
session_start();

// जर आधीच लॉगिन असेल तर डॅशबोर्डवर पाठवा
if(isset($_SESSION['user_id'])) { 
    header("Location: index.php"); 
    exit(); 
}

include 'functions.php'; 

if(isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    global $pdo; 

    try {
        // १. ईमेलनुसार युझर शोधा
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // २. पासवर्ड व्हेरिफिकेशन
        if($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username']; // तुझ्या DB नुसार 'username' किंवा 'name' तपासा
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password. Please try again.";
        }
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ExpensePro</title>
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
            background: radial-gradient(circle at 10% 10%, rgba(67, 97, 238, 0.12) 0%, transparent 40%);
            z-index: -1;
        }

        .glass-container {
            display: flex;
            width: 85%;
            max-width: 950px;
            height: 520px; /* Same as Register Page */
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
            padding: 35px 40px; /* Consistent Padding */
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .form-label { font-size: 10px; font-weight: 700; color: var(--muted); margin-bottom: 4px; }

        .form-control {
            border: 1.5px solid #eef2f6;
            padding: 10px 14px; /* Consistent with Register Inputs */
            border-radius: 10px;
            background: #f8fafc;
            font-size: 13px;
        }

        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.08); }

        .btn-primary {
            background: var(--dark); border: none; padding: 12px;
            border-radius: 10px; font-weight: 700; font-size: 13px;
            width: 100%; margin-top: 15px;
        }

        .auth-footer { margin-top: 20px; text-align: center; font-size: 12px; color: var(--muted); }
        .auth-footer a { color: var(--primary); font-weight: 700; text-decoration: none; }

        @media (max-width: 768px) { .brand-content { display: none; } .glass-container { height: auto; } }
    </style>
</head>
<body>

<div class="bg-mesh"></div>

<div class="glass-container">
    <div class="brand-content">
        <h2 class="mb-2">Welcome Back.</h2>
        <p class="text-secondary small">Login to access your financial dashboard and track your daily spending.</p>
        <div class="mt-3 small text-secondary">
            <i class="fas fa-shield-alt text-primary me-2"></i> Session Protected Access
        </div>
    </div>

    <div class="auth-card-wrapper">
        <div class="auth-card text-dark">
            <h4 class="fw-bold mb-1">Sign In</h4>
            <p class="small text-muted mb-4">Enter your credentials below.</p>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger py-1 small mb-3 border-0" style="border-radius: 8px;"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-uppercase">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="sahil@example.com" required>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label text-uppercase">Password</label>
                        <a href="#" class="small text-decoration-none text-primary fw-bold" style="font-size: 10px;">Forgot?</a>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary">
                    SIGN IN <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="auth-footer">
                New to ExpensePro? <a href="register.php">Create Account</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>