<?php


session_start();
include_once 'connection.php';

//include_once 'expireTime.php';
// registration success msg
if(isset($_SESSION['register_success_msg']) && !empty($_SESSION['register_success_msg'])){
    $register_success_msg = $_SESSION['register_success_msg'];
     //Remove the session variable to avoid displaying the message again
    unset($_SESSION['register_success_msg']);
}
//check if user already login
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    header("location: main.php");
    exit;
}

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') ?? "";
        $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') ?? "";

        // connect to the database

        $sql = "SELECT * FROM register WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hash_password = $row['password'];

            if (password_verify($password, $hash_password)) {
                //log success
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
              

                // Update login_time in the database
               date_default_timezone_set('Asia/Kolkata');
               $login_time = date('Y-m-d H-i-s');
               $sql = "UPDATE register set login_time = ? where id = ? ";
               $stmt=$conn->prepare($sql);
               $stmt->bind_param("si",$login_time,$_SESSION['id']);
               $stmt->execute();
               $stmt->close();
               header("location: main.php");
               exit;
            }
             else {
                $error_msg = "Invalid password";
            }
        } else {
            $error_msg = "Invalid email";
        }
    }
}
?>

<html>
<head>
    <title>Library Management System</title>
    <style>
        .input-container {
            text-align: center;
        }

        input[name='submit'] {
            margin-right: 20px;
        }
    </style>
</head>
<body>
<div class="input-container">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <h2 style="color:purple">Library Management System</h2>
        <input type="email" name="email" placeholder="Enter your email"><br><br>
        <input type="password" name="password" placeholder="Enter your password"><br>
        <?php if (isset($error_msg)) : ?>
            <p style="color:red; margin-right: 20;"><?php echo $error_msg; ?></p>
        <?php endif; ?>
        <input style="color:white; background-color:mediumblue" type="submit" name="submit" value="Login"><br><br>
        <a style="text-decoration: none;" href="register.php">CREATE NEW ACCOUNT</a><br><br>

        <?php if(isset($register_success_msg)): ?>
            <p style = "color:green"><?php echo $register_success_msg?></p>
            <?php endif; ?>
    </form>
</div>
</body>
</html>
