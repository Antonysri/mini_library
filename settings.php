<?php


session_start();
include_once 'connection.php';
//update msg

if(!isset($_SESSION['id']) && empty($_SESSION['id'])){
    header("Location:log_in.php");
}
$error_msg = "";
$updated   ="";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['old_password'] ?? "";
    $newPassword = $_POST['new_password'] ?? "";
    $oldPassword = htmlspecialchars($oldPassword, ENT_QUOTES, 'UTF-8');
    $newPassword = htmlspecialchars($newPassword, ENT_QUOTES, 'UTF-8');
    // retrieve db old password
    $sql = "SELECT id, password FROM register WHERE id=?";
    $stm = $conn->prepare($sql);
    $stm->bind_param("i", $_SESSION['id']);
    $stm->execute();
    $result = $stm->get_result();
    $row = $result->fetch_assoc();
    $db_password = $row['password'];
    if(isset($_POST['update']))
    {  
        if (password_verify($oldPassword, $db_password)) 
        {
        // update new password in db
             $hash_password = password_hash($newPassword, PASSWORD_DEFAULT);
             $sql = "UPDATE register SET password = ? WHERE id = ?";
             $stm = $conn->prepare($sql);
             $stm->bind_param("si", $hash_password, $_SESSION['id']);
            if ($stm->execute()) {
                 $updated = "Your password has been successfully updated.";
                 $_SESSION['password-updated'] = $updated;
                 $stm->close();
                 $conn->close();
               
                // header("Refresh:8");
             } 
             else{
                 $error_msg = "Something went wrong.";
             } 
        }      
        else{     
             $error_msg = "Incorrect password";
           
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
        .input-container input[name="clear_check_box"]{
            margin-left: -2;
        }
        .align-label {
            margin-right: -7;
        }
        
    </style>
      <script>
        // clear button is triggered
      function clearPassword() {
        document.getElementById('id_newPassword').value = '';
        document.getElementById('id_oldPassword').value = '';
      }
      // show password check
      function old_Password(){
        var oldPassword =  document.getElementById('id_oldPassword');
        if(oldPassword.type === "password" ){
            oldPassword.type = "text";
        }
        else{
            oldPassword.type = "password";
        }
      }
      function new_Password(){
        var newPassword =  document.getElementById('id_newPassword');
        if(newPassword.type === "password" ){
            newPassword.type = "text";
        }
        else{
            newPassword.type = "password";
        }
      }
    </script>
</head>
<body>
<form method="post">
    <div class="input-container">
        <h2 style="color: darkgray">Password Update</h2>
        <span style="color: red;"><?php echo htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8'); unset($error_msg);?></span><br>
        <label>Old Password</label>
        <input type="password" name="old_password" id="id_oldPassword" required>
        <br><br>
        <input type="checkbox" name="old_password_box" onclick="old_Password()">
        <label>Show old password</label><br><br>

        <label>New Password</label>
        <input type="password" name="new_password" id="id_newPassword" required><br><br>
        
        <input type="checkbox" name="new_password_box" onclick="new_Password()" >
        <label class="align-label">Show new password</label><br><br>

        <input type="button" value="Clear password" onclick="clearPassword()"><br><br>
       
        <input style = "background-color:blue ;color:white" type="submit" name="update" value="Update"><br><br>
         
        <a style="text-decoration: none ; color:blue;" href="main.php">Back</a><br>
       
            <span style="color: green;"><?php echo $updated; ?></span>
        



    </div>
</form>
</body>
</html>
