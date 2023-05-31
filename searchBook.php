<?php

session_start();



if(!isset($_SESSION['id']) && empty($_SESSION['id'])){
    header("Location:log_in.php");
    exit;
}



?>
<html>
    <head>
        <title>Library Managemant System</title>
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
        <div style = "text-align:center">
            <form method = "post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <h2 style="color:aquamarine;">Search For A Book In The Library</h2>
            <input size="24" type="text" name="search" placeholder="Enter the search for Book ISBN" required> <br><br>
            <input type="submit" value="search" name="submit"><br><br>
            
           <?php
           include_once 'connection.php';
           $error_msg = "";
           if($_SERVER['REQUEST_METHOD']=='POST'){
            if(isset($_POST['submit'])){
                $isbn = htmlspecialchars($_POST['search'],ENT_QUOTES,'UTF-8');
                //retrieve data from db
               

                $sql = "select * from books where book_isbn = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s",$isbn);
               
                if($stmt->execute()){
                $result = $stmt->get_result();
                 if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                    echo '<table>';
                        echo '<tr>';
                           
                           echo '<th>Book ID </th>';
                           echo '<th>Book Title </th>';
                           echo '<th>Book Author</th>';
                           echo '<th>Book ISBN </th>';
                        echo '</tr>';
                       
                        echo '<tr>';
                           echo '<td>' . $row['book_id'] . '</td>';
                           echo '<td>' . $row['book_title'] . '</td>';
                           echo '<td>' . $row['book_author'] . '</td>';
                           echo '<td>' . $row['book_isbn'] . '</td>';
                        echo '</tr>';

                    echo '</table>';
                    }
                    $stmt->close();
                }
                else{
                    $error_msg = "Your book are not available";
                }
            }
            else{
                $error_msg = "Invalid Book ISBN";
            }
            }
        }
        $conn->close()
        ?>
         <?php if(isset($error_msg)): ?>
                <span style="color:red"><?php echo $error_msg; ?></span>
                <?php endif; ?>
                <br><br>
        <a style = "text-decoration: none;color:blue;" href="main.php">Back</a>
        </form>
        
        </div>
    </body>
</html>