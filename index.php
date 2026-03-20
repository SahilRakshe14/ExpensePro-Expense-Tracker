<?php 
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Browser cache prevent logic
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include 'functions.php'; 
include 'header.php'; 

$user_id = $_SESSION['user_id']; 
$user_name = $_SESSION['user_name'] ?? 'User'; 
$warning_limit = 80;

// Fresh Data Fetching from functions.php
$s = getStats($user_id); 
$status = getBudgetStatus($user_id);
$chartData = getCategoryData($user_id); 
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Category', 'Amount'],
            <?php 
            if(empty($chartData)) {
                echo "['No Data Found', 0.1]"; 
            } else {
                foreach($chartData as $row) {
                    echo "['" . addslashes($row['category']) . "', " . (float)$row['total'] . "],";
                }
            }
            ?>
        ]);

        var options = {
            pieHole: 0.4,
            chartArea: {width:'90%', height:'85%'},
            legend: {position: 'bottom', textStyle: {fontSize: 12}},
            colors: ['#4361ee', '#3f37c9', '#4cc9f0', '#f72585', '#7209b7', '#b5179e'],
            backgroundColor: 'transparent'
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Dashboard Overview</h4>
        <p class="text-muted small">Welcome back, <?= htmlspecialchars($user_name) ?>! 👋</p>
    </div>
    <div class="d-flex gap-2">
        <a href="view_expenses.php" class="btn btn-light btn-sm rounded-pill px-3 shadow-sm">
            <i class="fas fa-list me-1"></i> View All
        </a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
    </div>
</div>

<?php if ($status['percent'] >= $warning_limit): ?>
    <div class="alert alert-danger shadow-sm mb-4 border-0 border-start border-5 border-danger animate__animated animate__headShake">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Budget Alert:</strong> You've consumed <b><?= round($status['percent'], 1) ?>%</b> of your monthly budget limit!
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                    <i class="fas fa-wallet fa-lg"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Total Lifetime</h6>
                    <h3 class="fw-bold mb-0">₹<?= number_format($s['total'], 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                    <i class="fas fa-calendar-check fa-lg"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Current Month</h6>
                    <h3 class="fw-bold mb-0">₹<?= number_format($s['month'], 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-info bg-opacity-10 text-info p-3 rounded-circle me-3">
                    <i class="fas fa-history fa-lg"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase mb-1">Spent Today</h6>
                    <h3 class="fw-bold mb-0">₹<?= number_format($s['today'], 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-7">
        <div class="stat-card border-0 shadow-sm p-4 bg-white h-100" style="border-radius: 20px;">
            <h6 class="fw-bold mb-4"><i class="fas fa-chart-pie me-2 text-primary"></i>Category Distribution</h6>
            <div id="donutchart" style="width: 100%; height: 320px;"></div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="stat-card border-0 shadow-sm p-4 bg-white h-100" style="border-radius: 20px;">
            <h6 class="fw-bold mb-4"><i class="fas fa-lightbulb me-2 text-warning"></i>Budget Insights</h6>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="small fw-bold">Monthly Usage Status</span>
                    <span class="small text-muted fw-bold"><?= round($status['percent'], 1) ?>%</span>
                </div>
                <div class="progress" style="height: 12px; border-radius: 10px; background-color: #f0f2f5;">
                    <?php 
                        $p_color = ($status['percent'] > 90) ? 'bg-danger' : (($status['percent'] > 70) ? 'bg-warning' : 'bg-primary');
                    ?>
                    <div class="progress-bar <?= $p_color ?> progress-bar-striped progress-bar-animated" 
                         style="width: <?= min($status['percent'], 100) ?>%"></div>
                </div>
            </div>

            <div class="mt-4 pt-2">
                <div class="d-flex justify-content-between mb-3 p-3 bg-light rounded-3">
                    <span class="text-muted small"><i class="fas fa-chart-line me-1"></i> Daily Avg:</span>
                    <span class="fw-bold small text-primary">₹<?= number_format($s['month'] / max((int)date('d'), 1), 2) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3 p-3 bg-light rounded-3">
                    <span class="text-muted small"><i class="fas fa-piggy-bank me-1"></i> Balance:</span>
                    <span class="fw-bold text-success small">₹<?= number_format(max($status['budget'] - $status['spent'], 0), 2) ?></span>
                </div>
                
                <?php if ($status['percent'] > 85): ?>
                    <div class="p-3 bg-danger bg-opacity-10 rounded-3 border-start border-4 border-danger mt-3">
                        <p class="mb-0 small text-dark" style="font-size: 13px;">
                            <strong>Stop!</strong> You've almost exhausted your budget. Avoid any non-essential spending.
                        </p>
                    </div>
                <?php elseif ($status['percent'] > 50): ?>
                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 border-start border-4 border-warning mt-3">
                        <p class="mb-0 small text-dark" style="font-size: 13px;">
                            <strong>Warning:</strong> You've crossed half your budget. Keep an eye on your expenses.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="p-3 bg-success bg-opacity-10 rounded-3 border-start border-4 border-success mt-3">
                        <p class="mb-0 small text-dark" style="font-size: 13px;">
                            <strong>Great Job!</strong> Your spending is very well-controlled this month. Keep it up!
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2 mt-4">
                <a href="add_expense.php" class="btn btn-primary btn-lg rounded-pill shadow-sm" style="font-size: 16px;">
                    <i class="fas fa-plus-circle me-2"></i>Add New Expense
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>