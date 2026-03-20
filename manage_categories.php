<?php 
session_start();
include 'functions.php'; 
include 'header.php'; 

if(isset($_POST['add_cat'])) {
    $cat_name = $_POST['cat_name'];
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?) ON CONFLICT (name) DO NOTHING");
    $stmt->execute([$cat_name]);
    echo "<script>alert('Category Added!');</script>";
}

$stmt = $pdo->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-5">
            <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
                <h5 class="fw-bold mb-3">Add New Category</h5>
                <form method="POST">
                    <input type="text" name="cat_name" class="form-control mb-3 border-0 bg-light" placeholder="e.g. Health, Bills" required>
                    <button type="submit" name="add_cat" class="btn btn-primary w-100 rounded-pill shadow-sm">Add Category</button>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
                <h5 class="fw-bold mb-3">Existing Categories</h5>
                <div class="d-flex flex-wrap gap-2">
                    <?php foreach($categories as $c): ?>
                        <span class="badge bg-light text-dark p-2 border"><?= htmlspecialchars($c['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>