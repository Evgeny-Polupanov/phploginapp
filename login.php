<?php
// Include db config
require_once 'db.php';

// Init vars
$email = $password = '';
$email_err = $password_err = '';

// Process form when post submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize POST
    $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    // Put POST vars in regular vars
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email
    if (empty($email)) {
        $email_err = 'Please enter an email';
    }

    // Validate password
    if (empty($password)) {
        $password_err = 'Please enter a password';
    } elseif (strlen($password) < 6) {
        $password_err = 'A password must be at least 6 characters';
    }

    // Make sure the errors are empty and login if so
    if (empty($email_err) && empty($password_err)) {
        // Prepare the query
        $sql = 'SELECT name, email, password FROM users WHERE email = :email';

        // Prepare the statement
        if ($stmt = $pdo->prepare($sql)) {
            // Bind params
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);

            // Attempt to execute
            if ($stmt->execute()) {
                // Check if the email exists
                if ($stmt->rowCount() === 1) {
                    if ($row = $stmt->fetch()) {
                        $hashed_password = $row['password'];
                        if (password_verify($password, $hashed_password)) {
                            // Successful login
                            session_start();
                            $_SESSION['email'] = $email;
                            $_SESSION['name'] = $row['name'];
                            header('location: index.php');
                        } else {
                            // Display the wrong password message
                            $password_err = 'The password you entered is not valid';
                        }
                    }
                } else {
                    $email_err = 'No account found for that email';
                }
            } else {
                die('Something went wrong');
            }
        }
        unset($stmt);
    }

    // Close the connection
    unset($pdo);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <title>Login To Your Account</title>
</head>
<body class="bg-primary">
<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <h2>Login</h2>
                <p>Fill in your credentials</p>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" name="email" class="form-control form-control-lg
                        <?php echo (!empty($email_err)) ? ' is-invalid' : ''; ?>" value="<?php echo $email ?>"/>
                        <span class="invalid-feedback"><?php echo $email_err ?></span>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg
                        <?php echo (!empty($password_err)) ? ' is-invalid' : ''; ?>" value="<?php echo $password ?>"/>
                        <span class="invalid-feedback"><?php echo $password_err ?></span>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <input type="submit" value="Login" class="btn btn-success btn-block" />
                        </div>
                        <div class="col">
                            <a href="register.php" class="btn btn-light btn-block">No account? Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
