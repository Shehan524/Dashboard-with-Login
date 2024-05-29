<?php
include ('config.php');

if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Calculate total income
    $income_query = $conn->prepare("SELECT SUM(amount) AS total_income FROM incomes WHERE date BETWEEN ? AND ?");
    $income_query->bind_param("ss", $start_date, $end_date);
    $income_query->execute();
    $income_result = $income_query->get_result();
    $total_income = $income_result->fetch_assoc()['total_income'];

    // Calculate total expenses
    $expense_query = $conn->prepare("SELECT SUM(amount) AS total_expenses FROM expenses WHERE date BETWEEN ? AND ?");
    $expense_query->bind_param("ss", $start_date, $end_date);
    $expense_query->execute();
    $expense_result = $expense_query->get_result();
    $total_expenses = $expense_result->fetch_assoc()['total_expenses'];

    echo json_encode([
        'total_income' => $total_income,
        'total_expenses' => $total_expenses
    ]);
}
?>