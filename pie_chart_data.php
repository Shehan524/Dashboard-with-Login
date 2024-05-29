<?php
include ('config.php');

// Fetch total income
$incomeQuery = "SELECT SUM(amount) AS total_income FROM incomes";
$incomeResult = $conn->query($incomeQuery);
$incomeRow = $incomeResult->fetch_assoc();
$totalIncome = $incomeRow['total_income'];

// Fetch total expenses
$expenseQuery = "SELECT SUM(amount) AS total_expenses FROM expenses";
$expenseResult = $conn->query($expenseQuery);
$expenseRow = $expenseResult->fetch_assoc();
$totalExpenses = $expenseRow['total_expenses'];

$data = [
    'totalIncome' => $totalIncome,
    'totalExpenses' => $totalExpenses
];

echo json_encode($data);
?>