<?php 
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cache control
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
include 'functions.php'; 
include 'header.php'; 

$user_id = $_SESSION['user_id']; 

// Delete Logic with Security Check
if(isset($_GET['del'])) { 
    $del_id = (int)$_GET['del'];
    if(deleteExpense($del_id, $user_id)) {
        echo "<script>alert('Record Deleted Successfully!'); window.location.href='view_expenses.php';</script>";
    }
}

// Search & Filter Logic
$from = $_GET['from'] ?? null;
$to = $_GET['to'] ?? null;
$key = $_GET['keyword'] ?? null;
$data = searchExpenses($user_id, $from, $to, $key); 
?>

<style>
    /* Table & UI styles */
    .table-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: none; overflow: hidden; }
    .table thead th { background-color: #f8f9fa; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; font-weight: 700; color: #6c757d; padding: 15px 20px; border-bottom: 1px solid #eee; }
    .table tbody td { padding: 15px 20px; vertical-align: middle; font-size: 14px; color: #444; border-bottom: 1px solid #f8f9fa; }
    .category-badge { padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; background: #eef2ff; color: #4361ee; }
    .amount-text { font-weight: 700; color: #2d3436; }
    .btn-icon { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: 0.3s; border: none; text-decoration: none; }
    .btn-edit { background: #eef2ff; color: #4361ee; }
    .btn-edit:hover { background: #4361ee; color: white; }
    .btn-delete { background: #fff1f0; color: #ff4d4f; }
    .btn-delete:hover { background: #ff4d4f; color: white; }
    .filter-section { background: white; border-radius: 15px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Expense History</h4>
        <p class="text-muted small mb-0">Manage and track all your recorded spends.</p>
    </div>
    <a href="export_csv.php" class="btn btn-outline-success btn-sm px-3 rounded-pill">
        <i class="fas fa-file-excel me-2"></i>Export CSV
    </a>
</div>

<div class="filter-section">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">From Date</label>
            <input type="date" name="from" class="form-control form-control-sm border-0 bg-light" value="<?= htmlspecialchars($from ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-bold text-muted">To Date</label>
            <input type="date" name="to" class="form-control form-control-sm border-0 bg-light" value="<?= htmlspecialchars($to ?? '') ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-bold text-muted">Keyword</label>
            <input type="text" name="keyword" class="form-control form-control-sm border-0 bg-light" placeholder="Search in notes..." value="<?= htmlspecialchars($key ?? '') ?>">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill">
                <i class="fas fa-filter me-1"></i> Apply
            </button>
            <a href="view_expenses.php" class="btn btn-light btn-sm rounded-pill" title="Reset">
                <i class="fas fa-undo"></i>
            </a>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i><br>
                            No matching records found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($data as $r): ?>
                    <tr>
                        <td class="fw-medium text-muted"><?= date('M d, Y', strtotime($r['expense_date'])) ?></td>
                        <td><span class="category-badge"><?= htmlspecialchars($r['category_name']) ?></span></td>
                        <td class="amount-text">₹<?= number_format($r['amount'], 2) ?></td>
                        <td class="text-truncate" style="max-width: 200px;"><?= htmlspecialchars($r['description']) ?></td>
                        <td class="text-center">
                            <a href="edit_expense.php?id=<?= $r['id'] ?>" class="btn-icon btn-edit me-1" title="Edit">
                                <i class="fas fa-pen-nib"></i>
                            </a>
                            <a href="?del=<?= $r['id'] ?>" 
                               class="btn-icon btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this record?')"
                               title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>