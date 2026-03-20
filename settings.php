<?php 
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include 'functions.php'; 
include 'header.php'; 

$user_id = $_SESSION['user_id'];
$message = "";

// बजेट अपडेट करण्याचे लॉजिक
if(isset($_POST['update_budget'])) {
    $new_budget = $_POST['monthly_budget'];
    if(updateBudget($user_id, $new_budget)) {
        $message = "<div class='alert alert-success'>Budget updated successfully!</div>";
    }
}

// युझरची सध्याची माहिती मिळवा
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
                <h4 class="fw-bold mb-4"><i class="fas fa-sliders-h me-2 text-primary"></i>Budget Settings</h4>
                
                <?= $message ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Monthly Spending Limit (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">₹</span>
                            <input type="number" name="monthly_budget" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $user['monthly_budget'] ?>" required>
                        </div>
                        <p class="text-muted small mt-2">When your spending reaches 80% of this amount, you'll see a warning on the dashboard.</p>
                    </div>

                    <button type="submit" name="update_budget" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                        <i class="fas fa-save me-2"></i>Update Budget
                    </button>
                </form>

                <hr class="my-5 opacity-10">

                <h6 class="fw-bold mb-3">Account Information</h6>
                <div class="p-3 bg-light rounded-3 mb-2">
                    <span class="text-muted small d-block">Full Name</span>
                    <span class="fw-bold"><?= htmlspecialchars($user['username']) ?></span>
                </div>
                <div class="p-3 bg-light rounded-3">
                    <span class="text-muted small d-block">Email Address</span>
                    <span class="fw-bold"><?= htmlspecialchars($user['email']) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>