<?php

include_once 'connection.php';
// session_start();
require_once 'session_expire.php';


// Check if the user is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location: log_in.php");
    exit;
}


//book added success message
if(isset($_SESSION['book_added']) && !empty($_SESSION['book_added'])){
    $success_msg = $_SESSION['book_added'];
     //Remove the session variable to avoid displaying the message again
    unset($_SESSION['book_added']);
}
//book removed message
if(isset($_SESSION['remove_msg']) && !empty($_SESSION['remove_msg'])){
    $success_msg = $_SESSION['remove_msg'];
    unset($_SESSION['remove_msg']);
}




if (isset($_POST['logout'])) {
    header("Location: log_out.php");
    exit;
}
?>

<html>
    <head>
        <title>Library Management System</title>
        <style>
            .input-container {
                text-align: center;
            }
            a {
                color: blue;
                text-decoration: none;
            }
            a:hover {
                color: red;
            }
            a:active {
                color: green;
            }
            input[name='logout'] {
                margin-left: -80px;
            }
        </style>
    </head>
    <body>
        <div class="input-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <h2 style="color:darkgray; margin-right:60">**********|| Welcome <?php echo $_SESSION['name'];?> ||**********</h2>
                <p style="color: pink; margin-right:90">Library Menu<p>
                <a style="margin-right: 120;" href="addBook.php">Add a book to the library</a><br><br>
                <a style="margin-right: 75;"  href="removeBook.php">Remove a book from the library</a><br><br>
                <a style="margin-right: 85;"  href="searchBook.php">Search for a book in the library</a><br><br>
                <a style="margin-right: 10;"href="displayBook.php">Display a list of all the books in the library</a><br><br>
                <input style="background-color: red; color: white;" type="submit" name="logout" value="Logout"><br>
                <?php if(isset($success_msg)): ?>
                <p style = "color:green; margin-right: 40; font-family: sans-serif; ;font-size: large;"><?php echo $success_msg; ?></p>
                <?php endif; ?><br>
                <a style="text-decoration: none; color:darkmagenta; margin-right:80" href="settings.php">Account Setting</a>
            </form>
        </div>
    </body>
</html>
