<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
    die("Dead");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Best Dressed</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background: linear-gradient(to bottom, #7ed56f, #28b485);
    }

    .login-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      background-color: rgba(255, 255, 255, 0.8);
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error-message {
      color: #ff0000;
      text-align: center;
      margin-top: 10px;
    }

    .toggle-form {
      text-align: center;
      margin-top: 20px;
    }

    .toggle-form a {
      color: #007bff;
      cursor: pointer;
    }

    .social-buttons {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }
    .social-button {
      padding: 10px 20px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 5px;
      border: none;
      margin: 0 5px;
    }
    .facebook-button {
      background-color: #3b5998;
      color: #fff;
    }
    .google-button {
      background-color: #db4437;
      color: #fff;
    }
    .X-button {
      background-color: black;
      color: #fff;
    }
  </style>
</head>
<body onload="redirect()">
<div class="login-container" id="login-container">
    <h2>Best Dressed</h2>
    <button onclick="continueAsGuest()">Continue as Guest</button>
    <div id="signin-form">
        <form id="create-account-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
          <label for="new-username">Create Username:</label>
          <!-- The PHP login code -->
          <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
          <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
          <label for="new-password">Create Password:</label>
          <!-- The PHP for password -->
          <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
          <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <button type="submit">Create Account</button>
      </form>
      <p id="error-message" class="error-message"></p>
    </div>
    <div class="toggle-form">
      Don't have an account? <a href="#" onclick="toggleForm()">Sign Up</a>
    </div>
  </div>

  <div class="login-container" id="signup-container" style="display: none;">
    <h2>Create Account</h2>
    <form id="create-account-form">
      <div class="form-group">
        <label for="new-username">Create Username:</label>
        <input type="text" id="new-username" name="new-username" required>
      </div>
      <div class="form-group">
        <label for="new-password">Create Password:</label>
        <input type="password" id="new-password" name="new-password" required>
      </div>
      <button type="submit">Create Account</button>
    </form>
    <p id="create-account-message" class="error-message"></p>
    <div class="toggle-form">
      Already have an account? <a href="#" onclick="toggleForm()">Login</a>
    </div>
  </div>

  <script>
    //Needed for opening a PHP file
    function redirect(){
        window.location.href = 'signUp.php';
    }

    function toggleForm() {
      var loginContainer = document.getElementById('login-container');
      var signupContainer = document.getElementById('signup-container');

      if (loginContainer.style.display === 'none') {
        loginContainer.style.display = 'block';
        signupContainer.style.display = 'none';
      } else {
        loginContainer.style.display = 'none';
        signupContainer.style.display = 'block';
      }
    }

    function continueAsGuest() {
      // Implement logic for continuing as a guest here
      console.log('Continuing as Guest...');
      window.location.href = 'guest_home.html'; // Redirect to guest home page
    }

    function loginWithFacebook() {
      // Implement Facebook login logic here
      console.log('Logging in with Facebook...');
    }

    function loginWithGoogle() {
      // Implement Google login logic here
      console.log('Logging in with Google...');
    }

    function loginWithTwitter() {
      // Implement Twitter login logic here
      console.log('Logging in with X...');
    }

    document.getElementById('login-form').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission

      // Get the username and password values
      var username = document.getElementById('username').value;
      var password = document.getElementById('password').value;

      // Basic validation
      if (username === 'admin' && password === 'password') {
        // Successful login
        window.location.href = 'dashboard.html'; // Redirect to dashboard page
      } else {
        // Failed login
        document.getElementById('error-message').innerText = 'Invalid username or password';
      }
    });

    document.getElementById('create-account-form').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission

      // Get the new username and password values
      var newUsername = document.getElementById('new-username').value;
      var newPassword = document.getElementById('new-password').value;

      // Here you can implement logic to create a new account
      // For simplicity, let's just display a message
      document.getElementById('create-account-message').innerText = 'Account created successfully!';
    });
  </script>
</body>
</html>
