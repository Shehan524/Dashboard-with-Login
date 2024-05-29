<?php
include ('config.php');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Login_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding expenses
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_expense'])) {
    $category = $_POST['category'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $sql = "INSERT INTO expenses (category, date, description, amount)
            VALUES ('$category', '$date', '$description', '$amount')";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle form submission for updating expenses
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_expense'])) {
    $id = $_POST['expense_id'];
    $category = $_POST['category'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $sql = "UPDATE expenses SET category='$category', date='$date', description='$description', amount='$amount' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle form submission for deleting expenses
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_expense'])) {
    $id = $_POST['expense_id'];

    $sql = "DELETE FROM expenses WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch existing records
$sql = "SELECT * FROM expenses";
$result = $conn->query($sql);

// Fetch the expense to be updated
$updateExpense = null;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $updateSql = "SELECT * FROM expenses WHERE id='$id'";
    $updateResult = $conn->query($updateSql);
    $updateExpense = $updateResult->fetch_assoc();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expense Manager</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .form-container, .display-container {
            width: 45%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="date"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 10px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            background-color:#007bff ;
            margin:1px;
            
        }
        input[type="submit"]:hover {
            opacity: 0.8;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            width: 100%;
        }
        .btn-edit {
            background-color: #28a745;
            color: white;
            width: 120%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #dee2e6;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        .action-buttons form {
            display: inline-block;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2><?php echo isset($updateExpense) ? 'Edit Expense' : 'Add Expense'; ?></h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="hidden" name="expense_id" value="<?php echo isset($updateExpense) ? $updateExpense['id'] : ''; ?>">
        
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" value="<?php echo isset($updateExpense) ? $updateExpense['category'] : ''; ?>" required><br><br>
        
        <label for="date">Date:</label><br>
        <input type="date" id="date" name="date" value="<?php echo isset($updateExpense) ? $updateExpense['date'] : ''; ?>" required><br><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo isset($updateExpense) ? $updateExpense['description'] : ''; ?></textarea><br><br>
        
        <label for="amount">Amount:</label><br>
        <input type="number" id="amount" name="amount" step="0.01" value="<?php echo isset($updateExpense) ? $updateExpense['amount'] : ''; ?>" required><br><br>
        
        <input type="submit" name="<?php echo isset($updateExpense) ? 'update_expense' : 'add_expense'; ?>" value="<?php echo isset($updateExpense) ? 'Update Expense' : 'Add Expense'; ?>">
    </form>
</div>

<div class="display-container">
    <h2>Expenses</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"]. "</td><td>" . $row["category"]. "</td><td>" . $row["date"]. "</td><td>" . $row["description"]. "</td><td>" . $row["amount"]. "</td><td class='action-buttons'>";
                echo '<form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">';
                echo '<input type="hidden" name="expense_id" value="'.$row["id"].'">';
                echo '<input type="submit" name="delete_expense" value="Delete" class="btn-delete">';
                echo '</form>';
                echo '<form method="get" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">';
                echo '<input type="hidden" name="edit" value="'.$row["id"].'">';
                echo '<input type="submit" value="Edit" class="btn-edit">';
                echo '</form>';
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No expenses found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
