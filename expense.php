<?php
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO expenses (name, amount, category, date, description) VALUES ('$name', '$amount', '$category', '$date', '$description')";
    $conn->query($sql);
}

$expenses = $conn->query("SELECT * FROM expenses ORDER BY date DESC LIMIT 5");
$total_expense = $conn->query("SELECT SUM(amount) AS total FROM expenses")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Boxicons css -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <section class="home">
        <div class="text">Add Expense</div>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="date" name="date" placeholder="Date" required>
            <textarea name="description" placeholder="Description"></textarea>
            <button type="submit">Add Expense</button>
        </form>

        <h2>Latest Expenses</h2>
        <ul>
            <?php while ($expense = $expenses->fetch_assoc()): ?>
                <li><?php echo $expense['name'] . ' - ' . $expense['amount'] . ' - ' . $expense['category'] . ' - ' . $expense['date']; ?>
                </li>
            <?php endwhile; ?>
        </ul>

        <h2>Total Expense: <?php echo $total_expense; ?></h2>
    </section>

    <script src="assets/js/script.js"></script>
</body>

</html>