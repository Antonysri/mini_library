<?php
session_start();


include_once 'connection.php';
if(!isset($_SESSION['id']) || empty($_SESSION['id'])){
    header("Location:log_in.php");
    exit;
}
$error_msg = "";
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(isset($_POST['submit'])){
        $id     = filter_var($_POST['bookId'],FILTER_SANITIZE_STRING);
        $title  = filter_var($_POST['bookTitle'],FILTER_SANITIZE_STRING);
        $author = filter_var($_POST['bookAuthor'],FILTER_SANITIZE_STRING);
        $isbn   = filter_var($_POST['bookIsbn'],FILTER_SANITIZE_STRING);

    //storing the database
    //check book are already exist
    $check_book = "select * from books where book_title = ? or book_id = ?";
    $stmt= $conn->prepare($check_book);
    $stmt->bind_param("ss",$title,$id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $error_msg = "This book are ready exists please try another book";
    }
    else{
    $sql = "INSERT INTO books(name,book_id,book_title,book_author,book_isbn)VALUES(?,?,?,?,?)";
    $stmt= $conn->prepare($sql);
    $stmt->bind_param("sssss",$_SESSION['name'],$id,$title,$author,$isbn);
    if($stmt->execute()){
        $_SESSION['book_added'] = "Your book was added successfully";
        header("Location:main.php");
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
            .l1{
                color: green;
            }
            .l2{
                margin-left: 23;
                color: green;
            }
            .l3{
                margin-left: 8;
                color: green;
            }
            .l4{
                margin-left: 13;
                color: green;
            }
        </style>
    </head>
  
    <body>
        <div class="input-container">
    <form method ="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    
    <h3 style="color:deeppink">ADD YOUR BOOK</h3>
    <label class="l1">Enter the book id of the book:</label>
    <input text="text" name=bookId required><br><br>
    <label class="l2">Enter the title of the book:</label>
    <input text="text" name=bookTitle required><br><br>
    <label class="l3">Enter the author of the book:</label>
    <input text="text" name=bookAuthor required><br><br>
    <label class="l4">Enter the ISBN of the book:</label>
    <input text="text" name=bookIsbn required><br><br>
    <button style="background-color:pink;" type="submit" name='submit'>ADD</button><br><br>
    <a style = "text-decoration: none; color :blue" href="main.php">Back</a>
     <?php if (isset($error_msg)) : ?>
            <p style="color:red;"><?php echo $error_msg; ?></p>
        <?php endif; ?>
    </form>
        </div>
    </body>
</html>