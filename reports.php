<?php 
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include 'functions.php'; 
include 'header.php'; 

$user_id = $_SESSION['user_id'];

// १. कॅटेगरीनुसार डेटा मिळवा (Pie Chart साठी)
$chartData = getCategoryData($user_id);

// २. एकूण खर्च आणि बजेटची तुलना
$stmt = $pdo->prepare("SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND EXTRACT(MONTH FROM expense_date) = EXTRACT(MONTH FROM CURRENT_DATE)");
$stmt->execute([$user_id]);
$currentMonthSpent = $stmt->fetch()['total'] ?? 0;

$stmt = $pdo->prepare("SELECT monthly_budget FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$userBudget = $stmt->fetch()['monthly_budget'] ?? 0;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-chart-pie me-2 text-primary"></i>Spending Analytics</h4>
        <button onclick="window.print()" class="btn btn-outline-dark btn-sm rounded-pill">
            <i class="fas fa-print me-2"></i>Print Report
        </button>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="stat-card border-0 shadow-sm p-4 bg-white h-100" style="border-radius: 20px;">
                <h6 class="text-muted small fw-bold text-uppercase">Monthly Overview</h6>
                <hr class="opacity-10">
                <div class="mb-3">
                    <span class="small text-muted">Total Budget</span>
                    <h4 class="fw-bold text-dark">₹<?= number_format($userBudget, 2) ?></h4>
                </div>
                <div class="mb-3">
                    <span class="small text-muted">Amount Spent</span>
                    <h4 class="fw-bold text-danger">₹<?= number_format($currentMonthSpent, 2) ?></h4>
                </div>
                <div class="progress" style="height: 10px; border-radius: 10px;">
                    <?php 
                    $percent = ($userBudget > 0) ? ($currentMonthSpent / $userBudget) * 100 : 0;
                    $barColor = ($percent > 90) ? 'bg-danger' : (($percent > 70) ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="progress-bar <?= $barColor ?>" style="width: <?= min($percent, 100) ?>%"></div>
                </div>
                <p class="small text-muted mt-2 text-center"><?= round($percent, 1) ?>% of budget used</p>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
                <h6 class="fw-bold mb-3">Category Distribution</h6>
                <div id="report_pie_chart" style="width: 100%; height: 300px;"></div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="stat-card border-0 shadow-sm p-4 bg-white" style="border-radius: 20px;">
                <h6 class="fw-bold mb-4">Detailed Category Breakdown</h6>
                <div class="table-responsive">
                    <table class="table table-hover border-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">Category</th>
                                <th class="border-0">Total Spent</th>
                                <th class="border-0">Impact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($chartData as $row): ?>
                            <tr>
                                <td class="fw-bold text-muted"><?= htmlspecialchars($row['category']) ?></td>
                                <td class="fw-bold">₹<?= number_format($row['total'], 2) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: <?= ($currentMonthSpent > 0) ? ($row['total'] / $currentMonthSpent) * 100 : 0 ?>%"></div>
                                        </div>
                                        <span class="small text-muted"><?= ($currentMonthSpent > 0) ? round(($row['total'] / $currentMonthSpent) * 100, 1) : 0 ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawReportChart);

    function drawReportChart() {
        var data = google.visualization.arrayToDataTable([
            ['Category', 'Amount'],
            <?php 
            foreach($chartData as $row) {
                echo "['" . addslashes($row['category']) . "', " . (float)$row['total'] . "],";
            }
            ?>
        ]);

        var options = {
            pieHole: 0.4,
            colors: ['#4361ee', '#3f37c9', '#4cc9f0', '#f72585', '#7209b7'],
            chartArea: {width: '90%', height: '90%'},
            legend: { position: 'right', alignment: 'center' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('report_pie_chart'));
        chart.draw(data, options);
    }
</script>

<?php include 'footer.php'; ?>