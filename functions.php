<?php
// 1. Database Connection (PDO)
$host = "localhost";
$port = "5432";
$db   = "expense_tracker";
$user = "postgres"; 
$pass = "1234"; // तुझा PSQL पासवर्ड

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// --- EXPENSE FUNCTIONS ---

// खर्च ॲड करण्यासाठी
function addExpense($user_id, $category_id, $amount, $description, $date) {
    global $pdo;
    $sql = "INSERT INTO expenses (user_id, category_id, amount, description, expense_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$user_id, $category_id, $amount, $description, $date]);
}

// सर्व खर्च मिळवण्यासाठी
function getAllExpenses($user_id) {
    global $pdo;
    $sql = "SELECT e.*, c.name as category_name FROM expenses e 
            JOIN categories c ON e.category_id = c.id 
            WHERE e.user_id = ? ORDER BY e.expense_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// डॅशबोर्डवरील आकडेवारी (Stats) मिळवण्यासाठी
function getStats($user_id) {
    global $pdo;
    $stats = [];

    // Total Expenses
    $stmt1 = $pdo->prepare("SELECT SUM(amount) FROM expenses WHERE user_id = ?");
    $stmt1->execute([$user_id]);
    $stats['total'] = $stmt1->fetchColumn() ?: 0;

    // Monthly Expenses (PSQL Logic)
    $stmt2 = $pdo->prepare("SELECT SUM(amount) FROM expenses WHERE user_id = ? AND expense_date >= DATE_TRUNC('month', CURRENT_DATE)");
    $stmt2->execute([$user_id]);
    $stats['month'] = $stmt2->fetchColumn() ?: 0;

    // Today's Expenses
    $stmt3 = $pdo->prepare("SELECT SUM(amount) FROM expenses WHERE user_id = ? AND expense_date = CURRENT_DATE");
    $stmt3->execute([$user_id]);
    $stats['today'] = $stmt3->fetchColumn() ?: 0;

    return $stats;
}

// बजेट स्टेटस चेक करण्यासाठी
function getBudgetStatus($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT monthly_budget FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $budget = $stmt->fetchColumn() ?: 0;

    $stmt_spent = $pdo->prepare("SELECT SUM(amount) FROM expenses WHERE user_id = ? AND expense_date >= DATE_TRUNC('month', CURRENT_DATE)");
    $stmt_spent->execute([$user_id]);
    $spent = $stmt_spent->fetchColumn() ?: 0;

    $percentage = ($budget > 0) ? ($spent / $budget) * 100 : 0;
    return ['spent' => $spent, 'budget' => $budget, 'percent' => $percentage];
}

// सर्च आणि फिल्टरसाठी
function searchExpenses($user_id, $from_date = null, $to_date = null, $keyword = null) {
    global $pdo;
    $sql = "SELECT e.*, c.name as category_name FROM expenses e JOIN categories c ON e.category_id = c.id WHERE e.user_id = ?";
    $params = [$user_id];

    if ($from_date && $to_date) {
        $sql .= " AND e.expense_date BETWEEN ? AND ?";
        array_push($params, $from_date, $to_date);
    }
    if ($keyword) {
        $sql .= " AND e.description ILIKE ?"; 
        array_push($params, "%$keyword%");
    }
    $sql .= " ORDER BY e.expense_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// कॅटेगरीनुसार डेटा (Pie Chart साठी)
function getCategoryData($user_id) {
    global $pdo;
    $sql = "SELECT c.name as category, SUM(e.amount) as total 
            FROM expenses e 
            JOIN categories c ON e.category_id = c.id 
            WHERE e.user_id = ? 
            GROUP BY c.name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// विशिष्ट ID चा खर्च मिळवण्यासाठी
function getExpenseById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// खर्च अपडेट करण्यासाठी
function updateExpense($id, $category_id, $amount, $description, $date) {
    global $pdo;
    $sql = "UPDATE expenses SET category_id = ?, amount = ?, description = ?, expense_date = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$category_id, $amount, $description, $date, $id]);
}

// खर्च डिलीट करण्यासाठी
function deleteExpense($id, $user_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    return $stmt->execute([$id, $user_id]);
}

// सर्व कॅटेगरीज मिळवण्यासाठी
function getAllCategories() {
    global $pdo;
    return $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
}

// मंथली बजेट अपडेट करण्यासाठी
function updateMonthlyBudget($user_id, $amount) {
    global $pdo;
    return $pdo->prepare("UPDATE users SET monthly_budget = ? WHERE id = ?")->execute([$amount, $user_id]);
}

function updateBudget($user_id, $amount) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET monthly_budget = ? WHERE id = ?");
    return $stmt->execute([$amount, $user_id]);
}
?>