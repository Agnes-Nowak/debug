<?php 
session_start();
require_once '../components/db_connect.php';

if (isset($_SESSION['user']) != "") {
    header("Location: ../home.php");
    exit;
 }
 
 if (! isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php" );
     exit;
 }

$sql = "SELECT * FROM hotels";
$result = mysqli_query($connect ,$sql);
$tbody=''; //this variable will hold the body for the table
if(mysqli_num_rows($result)  > 0) {     
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){         
        $tbody .= "<tr>
            <td><img class='img-thumbnail' src='../pictures/" .$row['hotelImage']."'</td>
            <td>" .$row['hotelName']."</td>
            <td>" .$row['hotelLoc']."</td>
            <td>" .$row['hotelPrice']."</td>
            <td><a href='update.php?id=" .$row['hotel_id']."'><button class='btn btn-primary btn-sm' type='button'>Edit</button></a>
            <a href='delete.php?id=" .$row['hotel_id']."'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a></td>
            </tr>";
            // M.: id in url is a parameter for get in delete or update
            //id is a parameter that comes from the URL; question mark means that what comes after it comes from the GET method, equal sign means that we're giving a value to the parameter
    };
} else {
    $tbody =  "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
}

$connect->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PHP CRUD</title>
        <?php require_once '../components/boot.php'?>
        <style type="text/css">
            .manageProduct {           
                margin: auto;
            }
            .img-thumbnail {
                width: 70px !important;
                height: 70px !important;
            }
            td {          
                text-align: left;
                vertical-align: middle;
            }
            tr {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="manageProduct w-75 mt-3">    
            <div class='mb-3'>
                <a href= "create.php"><button class='btn btn-primary'type="button" >Add Hotel</button></a>
            </div>
            <p class='h2'>Hotels</p>
            <table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>Image</th>
                        <th>Hotel Name</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $tbody;?>
                </tbody>
            </table>
        </div>
    </body>
</html>