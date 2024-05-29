<?php
include ('config.php');

// Fetch latest 3 incomes
$latest_incomes = $conn->query("SELECT * FROM incomes ORDER BY date DESC LIMIT 3");

// Fetch latest 3 expenses
$latest_expenses = $conn->query("SELECT * FROM expenses ORDER BY date DESC LIMIT 3");

// Calculate total income
$total_income = $conn->query("SELECT SUM(amount) AS total FROM incomes")->fetch_assoc()['total'];

// Calculate total expense
$total_expense = $conn->query("SELECT SUM(amount) AS total FROM expenses")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Uto-Farm Finance</title>
  <link rel="stylesheet" href="assets/css/dashstyle.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <section class="home">
    <div class="dashboard-header">
      <div class="total-box">
        <h2>Total Income: <span id="totalIncome"><?php echo $total_income; ?></span></h2>
      </div>
      <div class="total-box">
        <h2>Total Expense: <span id="totalExpenses"><?php echo $total_expense; ?></span></h2>
      </div>
    </div>

    <div class="dashboard-content">
      <div class="latest-box">
        <h3>Latest Incomes</h3>
        <ul>
          <?php while ($income = $latest_incomes->fetch_assoc()): ?>
            <li>
              <?php echo $income['name'] . ' - ' . $income['amount'] . ' - ' . $income['category'] . ' - ' . $income['date']; ?>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
      <div class="latest-box">
        <h3>Latest Expenses</h3>
        <ul>
          <?php while ($expense = $latest_expenses->fetch_assoc()): ?>
            <li>
              <?php echo $expense['name'] . ' - ' . $expense['amount'] . ' - ' . $expense['category'] . ' - ' . $expense['date']; ?>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
      <div class="chart-placeholder">
        <h3>Charts will be displayed here</h3>
      </div>
    </div>
  </section>

  <script src="assets/js/script.js"></script>
</body>

</html>