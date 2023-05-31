<?php
session_start();


include_once 'connection.php';

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location:log_in.php");
    exit;
}
$error_msg = "";

if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['submit'])){
        $bookId = htmlspecialchars($_POST['bookId'],ENT_QUOTES,'UTF-8');
        
        // retrieve the data from db
       
        $sql = "delete  from books where book_id= ?";
        $stmt= $conn->prepare($sql);
        $stmt->bind_param("s",$bookId);
       
    
        if( $stmt->execute()){
            $_SESSION['remove_msg'] = "One Row Book Was Removed Successfully ";
            header("Location:main.php");
            $stmt->close();
        }
        else{
            $error_msg = "Invalid Book Id!!!";
        }
    }
}
$conn->close();
?>

<html>
    <head>
        <title>Library Managemant System</title>
    </head>
    <body>
        <div style= "text-align:center">
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <h2 style = "color:crimson;" >Remove Your Book</h2>
            <input  size="40" type="text" name="bookId" placeholder="Enter the Book id of the book you want to remove" required><br><br>
            <input type="submit" name="submit" value="Remove"><br><br>
            <a style="text-decoration: none; color:blue" href="main.php">Back</a>
            <?php if(isset($error_msg)):?>
                <p style="color:red"><?php echo $error_msg ; ?></p>
                <?php endif; ?>
            


            </form>
        </div>
        
    </body>
</html>