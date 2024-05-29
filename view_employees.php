<?php
include ('config.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_manager";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete employee
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM employees WHERE id=$id") or die($conn->error());
}

// Fetch existing records
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Employees</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
        }
        h2 {
            margin-bottom: 20px;
            color: #fff;
        }
        table {
            width: 90%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background: #667eea;
            color: white;
        }
        tr:nth-child(even) {
            background: #e9ecef;
        }
        tr:hover {
            background: #dee2e6;
        }
        .actions a {
            margin-right: 10px;
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .actions a.delete {
            color: #dc3545;
        }
        .actions a.delete:hover {
            color: #c82333;
        }
        a.add-employee {
            display: inline-block;
            margin-top: 20px;
            color: #fff;
            text-decoration: none;
            background: #667eea;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: background 0.3s, transform 0.2s;
        }
        a.add-employee:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<h2>Employees</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Contact No</th>
        <th>Address</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"]. "</td>
                    <td>" . $row["name"]. "</td>
                    <td>" . $row["age"]. "</td>
                    <td>" . $row["contact_no"]. "</td>
                    <td>" . $row["address"]. "</td>
                    <td class='actions'>
                        <a href='add_employee.php?edit=" . $row["id"] . "'>Edit</a>
                        <a href='view_employees.php?delete=" . $row["id"] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No employees found</td></tr>";
    }
    $conn->close();
    ?>
</table>

<a href="add_employee.php" class="add-employee">Add Employee</a>

</body>
</html>
