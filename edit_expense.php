<?php 
include 'functions.php'; 
include 'header.php'; 

$id = $_GET['id']; // URL मधून ID घेणे
$expense = getExpenseById($id); // जुना डेटा मिळवणे

if(isset($_POST['update'])){
    $cat = $_POST['category_id'];
    $amt = $_POST['amount'];
    $note = $_POST['description'];
    $date = $_POST['expense_date'];
    
    if(updateExpense($id, $cat, $amt, $note, $date)) {
        echo "<script>alert('Record Updated Successfully!'); window.location.href='view_expenses.php';</script>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4 shadow-sm border-0">
            <h4 class="text-center mb-4 text-primary"><i class="fas fa-edit me-2"></i>Edit Expense</h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Date:</label>
                    <input type="date" name="expense_date" class="form-control" value="<?= $expense['expense_date'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount (₹):</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="<?= $expense['amount'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category:</label>
                    <select name="category_id" class="form-select" required>
                        <option value="1" <?= $expense['category_id'] == 1 ? 'selected' : '' ?>>Food</option>
                        <option value="2" <?= $expense['category_id'] == 2 ? 'selected' : '' ?>>Transport</option>
                        <option value="3" <?= $expense['category_id'] == 3 ? 'selected' : '' ?>>Shopping</option>
                        <option value="4" <?= $expense['category_id'] == 4 ? 'selected' : '' ?>>Rent</option>
                        <option value="5" <?= $expense['category_id'] == 5 ? 'selected' : '' ?>>Others</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note:</label>
                    <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($expense['description']) ?></textarea>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" name="update" class="btn btn-primary">Update Changes</button>
                    <a href="view_expenses.php" class="btn btn-light">Back to List</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>