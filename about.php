<?php 
session_start();
include 'functions.php'; 
include 'header.php'; 
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="stat-card border-0 shadow-sm p-5 bg-white mb-4 text-center" style="border-radius: 30px;">
                <div class="icon-box bg-primary text-white mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 20px;">
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
                <h2 class="fw-bold text-dark">About ExpensePro</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    A comprehensive Full-Stack Wealth Management and Expense Tracking solution designed to help individuals monitor their financial health with precision and ease.
                </p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <span class="badge bg-light text-primary border px-3 py-2">PHP 8.2</span>
                    <span class="badge bg-light text-primary border px-3 py-2">PostgreSQL</span>
                    <span class="badge bg-light text-primary border px-3 py-2">Bootstrap 5</span>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="stat-card border-0 shadow-sm p-4 bg-white h-100" style="border-radius: 20px;">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-shield-alt me-2"></i>Secure Authentication</h6>
                        <p class="small text-muted">User accounts are protected using session-based authentication and secure password hashing (BCRYPT), ensuring personal financial data remains private.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card border-0 shadow-sm p-4 bg-white h-100" style="border-radius: 20px;">
                        <h6 class="fw-bold text-success mb-3"><i class="fas fa-chart-line me-2"></i>Advanced Analytics</h6>
                        <p class="small text-muted">Integrated with Google Charts API to provide visual feedback through Pie Charts and Bar Graphs for category-wise spending analysis.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card border-0 shadow-sm p-4 bg-white h-100" style="border-radius: 20px;">
                        <h6 class="fw-bold text-warning mb-3"><i class="fas fa-bell me-2"></i>Smart Budgeting</h6>
                        <p class="small text-muted">Dynamic budget tracking with visual indicators. The system calculates real-time usage and alerts users when they approach their set monthly limits.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stat-card border-0 shadow-sm p-4 bg-white h-100" style="border-radius: 20px;">
                        <h6 class="fw-bold text-info mb-3"><i class="fas fa-file-export me-2"></i>Data Management</h6>
                        <p class="small text-muted">Full CRUD capabilities for expenses and categories, with search filters and an export feature for offline record keeping.</p>
                    </div>
                </div>
            </div>

            <div class="stat-card border-0 shadow-sm p-4 bg-white mt-4" style="border-radius: 20px;">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-1">Developed By</h5>
                        <p class="text-primary fw-bold mb-0">Sahil Rakshe</p>
                        <p class="text-muted small">T.Y.B.Sc Data Science & Computer Science</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="index.php" class="btn btn-dark rounded-pill px-4">
                            Back to Dashboard <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>