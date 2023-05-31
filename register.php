<?php
    session_start();
    include_once 'connection.php';
 


   
if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
    header("Location:main.php");
    exit;
}
    $error_msg = "";
    if($_SERVER['REQUEST_METHOD']='POST'){
        if(isset($_POST['submit'])){
            $name    =  htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8') ?? "";
            $email    =  htmlspecialchars($_POST['email'],ENT_QUOTES,'UTF-8') ?? "";
            $password =  $_POST['password'];// No need for htmlspecialchars() as it's not displayed in HTML
            $c_password =  $_POST['c_password'];
            //email exists
            $checkMail = "select email from register where email = ?";
            $stmt= $conn->prepare($checkMail);
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows > 0){
                $error_msg = "This email is already exits!! try to another email";
             }
             
             //password length check
            //  elseif(strlen($password) < 6){
            //     $error_msg = "Password must be 6 characters long";
            //  }
             //password match check
             elseif($password != $c_password){
                $error_msg = "Password must be 6 characters long";
             }
             //insert into db
            else{
            //hash password
            $hashedPassword = password_hash($password,PASSWORD_DEFAULT);
            //insert into database
            $sql = "insert into register (name,email,password) values(?,?,?)";
            $stmt= $conn->prepare($sql);
            $stmt->bind_param("sss",$name,$email,$hashedPassword);
            if($stmt->execute()){
                $_SESSION['register_success_msg'] = "Your registration successfully please login";
                header('Location:log_in.php');
                $stmt->close();
                exit;
            }
            else{
                die('please check the details'.$conn->connect_error);
            }
        }
        
        }
    }
    $conn->close();

    ?>


<html>
    <head>
    <title>Library Management System</title>
    <style>
            .input-container{
                text-align: center;
            }
            input[name='submit']{
                margin-right: 20;
            }
            input[name='check_password']{
                margin-left:-55;
            }

    </style>
    <script>
        function showPassword(){
            var password = document.getElementById('password');
            if(password.type == 'password'){
                password.type = 'text';
            }
            else{
                password.type = 'password';
            }
        }
        /* function mismatch(){
            var password         = document.f1.password.value;
            var confirm_password = document.f1.c_password.value;
            if(password != confirm_password){
                alert('password mismatch please check the password');
                return false;
            }
            else{
                return true;
            }
        }
        function validateForm(){
            var password   =document.getElementById('password').value;
            if(password.length < 6){
                alert('Password must be at least 6 characters long.');
                return false;            
            }
            else{
                return true;
            }
        } */
        
    </script> 
    </head>
    
    <body>
    <div class="input-container">
            <form name = "f1" method = "post" onsubmit ="return mismatch() && validateForm()" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <h2 style="color:blue">Register Your Account</h2>
            <input type="name"     name="name" placeholder="Enter your name" required><br><br>
            <input type="email"    name="email" placeholder="Enter your email" required><br><br>
            <input type="password" name="password" id="password" placeholder="Enter your password" required><br><br>
            <input type="checkbox" name="check_password" onclick ="showPassword()">show password<br><br>
            <input type="password" name="c_password" placeholder="confirm your password" required><br>
            
            <?php if(isset($error_msg)):
            echo '<p style = "color:red ;">'.$error_msg.'</p>';
            endif;
            ?>
            <input style="color:white; background-color:mediumblue" type="submit" name="submit" value="Signup"><br><br>
            <a style="text-decoration: none;" href="log_in.php">Already have an account</a>
        </form>
        </div>
    </body>
</html>