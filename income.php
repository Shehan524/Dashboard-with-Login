<?php
include ('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO incomes (name, amount, category, date, description) VALUES ('$name', '$amount', '$category', '$date', '$description')";
    $conn->query($sql);
}

$incomes = $conn->query("SELECT * FROM incomes ORDER BY date DESC LIMIT 5");
$total_income = $conn->query("SELECT SUM(amount) AS total FROM incomes")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income</title>
    <link rel="stylesheet" href="assets/css/dashstyle.css">
    <!-- Boxicons css -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <section class="home">
        <div class="text">Add Income</div>
        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="number" step="0.01" name="amount" placeholder="Amount" required>
            <input type="text" name="category" placeholder="Category" required>
            <input type="date" name="date" placeholder="Date" required>
            <textarea name="description" placeholder="Description"></textarea>
            <button type="submit">Add Income</button>
        </form>

        <div class="total-box">
            <h2>Total Income</h2>
            <p><?php echo $total_income; ?></p>
        </div>

        <div class="latest-box">
            <h3>Latest Incomes</h3>
            <ul>
                <?php while ($income = $incomes->fetch_assoc()): ?>
                    <li>
                        <span><?php echo $income['name']; ?></span>
                        <span><?php echo $income['amount']; ?></span>
                        <span><?php echo $income['category']; ?></span>
                        <span><?php echo $income['date']; ?></span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </section>

    <script src="assets/js/script.js"></script>
</body>

</html>