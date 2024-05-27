<?php
include ('partials/header.php');
?>

<div class="form_container">
  <div class="overlay">
    <!-- This doesn't have content -->
  </div>

  <div class="titileDiv">
    <h1 class="title">Register</h1>
  </div>

  <!-- Form section -->
  <form action="" method="POST">
    <div class="rows grid">
      <!-- Username -->
      <div class="row">
        <label for="username">User Name</label>
        <input type="text" id="username" name="userName" placeholder="Enter User Name" required />
      </div>

      <!-- Password -->
      <div class="row">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required />
      </div>

      <!-- Phone number -->
      <div class="row">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" placeholder="Enter Phone Number" required />
      </div>

      <!-- Submit button -->
      <div class="row">
        <input type="submit" id="submitBtn" name="submit" value="Register" required />

        <span class="registerLink">
          Have an account already? <a href="index.php">Login</a>
        </span>
      </div>
    </div>
  </form>
</div>

<?php
include ('partials/footer.php');
?>

<?php
if (isset($_POST['submit'])) {
  // Sanitize inputs
  $userName = mysqli_real_escape_string($conn, $_POST['userName']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // SQL query with placeholders
  $sql = "INSERT INTO admin (username, password, phone) VALUES (?, ?, ?)";

  // Prepare and execute the query
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "sss", $userName, $hashedPassword, $phone);
    $res = mysqli_stmt_execute($stmt);

    // Check if query is executed successfully
    if ($res) {
      // Message to show account created successfully
      $_SESSION['accountCreated'] = '<span class="addedAccount">Account ' . $userName . ' created!</span>';
      header('Location: ' . SITEURL . 'index.php');
      exit();
    } else {
      // Message to show that account failed to be created
      $_SESSION['unSuccessful'] = '<span class="fail">Account ' . $userName . ' failed!</span>';
      header('Location: ' . SITEURL . 'register.php');
      exit();
    }
  } else {
    // Handle error in preparing the statement
    echo "Error preparing the SQL statement.";
  }
}

?>