<?php
session_start();


include_once 'connection.php';

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header("Location:log_in.php");
    exit;
}

?>

<html>
<head>
    <title>Library Management System</title>
    <style>
        table {
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div style="text-align:center">
        <h2 style="color: palevioletred;">List of all your books</h2>
       

        <table>
            <tr>
               
                <th>Book ID</th>
                <th>Book Title</th>
                <th>Book Author</th>
                <th>Book ISBN</th>
            </tr>
           
           <?php
            //Retrieve the data from the database
            $sql = "SELECT * FROM books where name= ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$_SESSION['name']);

            if ($stmt->execute()){
               $result = $stmt->get_result();
              
           //diaplay each book as a table row

           while($row = $result->fetch_assoc()){
            echo '<tr>';
           
            echo '<td>'.$row['book_id'].'</td>';
            echo '<td>'.$row['book_title'].'</td>';
            echo '<td>'.$row['book_author'].'</td>';
            echo '<td>'.$row['book_isbn'].'</td>';
            echo '</tr>';
           }
           $stmt->close();
        }
        else{
            die("something wrong".$conn->error);
        }
        $conn->close();
            ?>
        </table>
        <br><br>
        <a style = "text-decoration: none; color :blue" href="main.php">Back</a>
    </div>
</body>
</html>
