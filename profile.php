<?php 
session_start();
include 'functions.php'; 
include 'header.php'; 

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<div class="container mt-4 text-center">
    <div class="stat-card border-0 shadow-sm p-5 bg-white mx-auto" style="border-radius: 25px; max-width: 500px;">
        <img src="https://ui-avatars.com/api/?name=<?= $user['username'] ?>&background=4361ee&color=fff&size=128" class="rounded-circle mb-3 shadow">
        <h3 class="fw-bold"><?= htmlspecialchars($user['username']) ?></h3>
        <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
        <hr>
        <div class="row mt-4">
            <div class="col-6 text-start">
                <small class="text-muted d-block">Monthly Budget</small>
                <span class="fw-bold text-primary">₹<?= number_format($user['monthly_budget'], 2) ?></span>
            </div>
            <div class="col-6 text-end">
                <small class="text-muted d-block">Account Status</small>
                <span class="badge bg-success">Active</span>
            </div>
        </div>
        <a href="settings.php" class="btn btn-outline-primary btn-sm mt-4 rounded-pill">Edit Budget Settings</a>
    </div>
</div>
<?php include 'footer.php'; ?>