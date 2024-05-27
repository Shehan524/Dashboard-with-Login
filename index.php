<?php
include ('partials/header.php');
?>

<?php
if (isset($_SESSION['accountCreated'])) {
  echo $_SESSION['accountCreated'];
  unset($_SESSION['accountCreated']);
}
?>
<div class="form_container">
  <div class="overlay">
    <!-- This doesn't have content -->
  </div>

  <div class="titileDiv">
    <img src="assets/images/logo.png" alt="Logo" class="logo" />
    <h1 class="title">Login</h1>
    <span class="subTitle">Welcome back!</span>
  </div>

  <?php
  if (isset($_SESSION['noAdmin'])) {
    echo $_SESSION['noAdmin'];
    unset($_SESSION['noAdmin']);
  }
  ?>

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
      <!-- Submit button -->
      <div class="row">
        <input type="submit" id="submitBtn" name="submit" value="Login" required />

        <span class="registerLink">Don't have an account? <a href="register.php">Register</a></span>
      </div>
    </div>
  </form>
</div>

<?php
include ('partials/footer.php');
?>

<!-- Login to database -->
<?php
if (isset($_POST['submit'])) {
  // Sanitize inputs
  $userName = mysqli_real_escape_string($conn, $_POST['userName']);
  $passWord = mysqli_real_escape_string($conn, $_POST['password']);

  // SQL query with placeholders
  $sql = "SELECT * FROM admin WHERE username = ?";

  // Prepare and execute the query
  if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    // Verify the password
    if ($row && password_verify($passWord, $row['password'])) {
      // Message to welcome admin to the dashboard
      $_SESSION['loginMessage'] = '<span class="success">Welcome ' . $userName . ' </span>';
      header('Location: ' . SITEURL . 'dashboard.php');
      exit();
    } else {
      // Message if the admin account is not in the database
      $_SESSION['noAdmin'] = '<span class="fail"> ' . $userName . ' is not registered!</span>';
      header('Location: ' . SITEURL . 'index.php');
      exit();
    }
  } else {
    // Handle error in preparing the statement
    echo "Error preparing the SQL statement.";
  }
}

?>