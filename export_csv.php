<?php
include 'functions.php';
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="expenses_report.csv"');
$output = fopen('php://output', 'w');
fputcsv($output, ['Date', 'Category', 'Amount', 'Note']);
$expenses = getAllExpenses(1);
foreach ($expenses as $row) {
    fputcsv($output, [$row['expense_date'], $row['category_name'], $row['amount'], $row['description']]);
}
fclose($output);
?>