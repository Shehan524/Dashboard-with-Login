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

// Initialize variables for form fields
$name = $age = $contact_no = $address = "";
$edit_mode = false;

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM employees WHERE id=$id") or die($conn->error());
    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $name = $row['name'];
        $age = $row['age'];
        $contact_no = $row['contact_no'];
        $address = $row['address'];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $contact_no = $_POST['contact_no'];
    $address = $_POST['address'];

    if (isset($_POST['id'])) {
        // Update existing employee
        $id = $_POST['id'];
        $sql = "UPDATE employees SET name='$name', age='$age', contact_no='$contact_no', address='$address' WHERE id=$id";
    } else {
        // Insert new employee
        $sql = "INSERT INTO employees (name, age, contact_no, address) VALUES ('$name', '$age', '$contact_no', '$address')";
    }

    if ($conn->query($sql) === TRUE) {
        $message = $edit_mode ? "Employee updated successfully" : "New employee added successfully";
        echo "<p style='color: green; text-align: center;'>$message</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
    header("Location: employee_manager.php");
    exit;
}

// Delete employee
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM employees WHERE id=$id") or die($conn->error());
    header("Location: employee_manager.php");
    exit;
}

// Fetch existing records
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Manager</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            width: 100%;
            max-width: 1200px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .form-container, .table-container {
            padding: 20px;
            border-radius: 8px;
            flex: 1;
            margin: 10px;
        }
        .form-container {
            max-width: 400px;
            background: #f9f9f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .table-container {
            background: #f9f9f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus, textarea:focus {
            border-color: #667eea;
        }
        input[type="submit"] {
            background: #667eea;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s, transform 0.2s;
        }
        input[type="submit"]:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2><?php echo $edit_mode ? 'Edit' : 'Add'; ?> Employee</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php if ($edit_mode): ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php endif; ?>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo $age; ?>" required>
            
            <label for="contact_no">Contact No:</label>
            <input type="text" id="contact_no" name="contact_no" value="<?php echo $contact_no; ?>" required>
            
            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo $address; ?></textarea>
            
            <input type="submit" value="<?php echo $edit_mode ? 'Update' : 'Add'; ?> Employee">
        </form>
    </div>

    <div class="table-container">
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
                                <a href='employee_manager.php?edit=" . $row["id"] . "'>Edit</a>
                                <a href='employee_manager.php?delete=" . $row["id"] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No employees found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</div>

</body>
</html>
