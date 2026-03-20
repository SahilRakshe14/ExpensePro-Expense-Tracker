<?php 
session_start();
include 'functions.php'; 

// 1. Session check: युझर लॉगिन नसेल तर लॉगिन पेजवर पाठवा
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Form Submit Logic: 'save' बटनवर क्लिक केल्यावर
if(isset($_POST['save'])) {
    $date = $_POST['date'];   // Form मधून डेट घेतली
    $amt = $_POST['amt'];     // Form मधून अमाऊंट घेतली
    $cat_id = $_POST['cat'];  // Form मधून कॅटेगरी आयडी घेतला
    $note = $_POST['note'];   // Form मधून नोट घेतली

    /**
     * तुझ्या functions.php मधील function चा sequence असा आहे:
     * addExpense($user_id, $category_id, $amount, $description, $date)
     */
    if(addExpense($user_id, $cat_id, $amt, $note, $date)) {
        // जर सक्सेस झाले तर डॅशबोर्डवर पाठवा
        header("Location: index.php?success=Expense Added Successfully"); 
        exit(); 
    } else {
        $error = "डेटाबेसमध्ये खर्च सेव्ह करता आला नाही. कृपया पुन्हा प्रयत्न करा.";
    }
}

// 3. Header include करा (सर्व लॉजिकच्या खालीच असावे जेणेकरून Redirect मध्ये एरर येणार नाही)
include 'header.php'; 
?>

<div class="container mt-5">
    <?php if(isset($error)): ?>
        <div class="alert alert-danger mx-auto mb-3" style="max-width: 500px; border-radius: 10px;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="card p-4 mx-auto border-0 shadow-lg" style="max-width: 500px; border-radius: 20px;">
        <h4 class="text-center fw-bold mb-4" style="color: #0f172a;">Add New Expense</h4>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">TRANSACTION DATE</label>
                <input type="date" name="date" class="form-control" value="<?= date('Y-m-d') ?>" style="border-radius: 10px;">
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">AMOUNT (₹)</label>
                <input type="number" name="amt" class="form-control" placeholder="0.00" step="0.01" required style="border-radius: 10px;">
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">CATEGORY</label>
                <select name="cat" class="form-select" required style="border-radius: 10px;">
                    <option value="" disabled selected>Select a category</option>
                    <?php 
                    // Database मधून कॅटेगरी आणण्यासाठी
                    $categories = getAllCategories(); 
                    foreach($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">DESCRIPTION / NOTE</label>
                <input type="text" name="note" class="form-control" placeholder="What was this for?" style="border-radius: 10px;">
            </div>

            <button type="submit" name="save" class="btn btn-dark w-100 py-2 fw-bold" style="border-radius: 12px;">
                SAVE EXPENSE <i class="fas fa-plus-circle ms-2"></i>
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>