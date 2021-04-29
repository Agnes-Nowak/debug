<?php
session_start();

if (isset($_SESSION[ 'user']) != "") {
   header("Location: ../home.php");
   exit;
}

if (! isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
   header("Location: ../index.php" );
    exit;
}

require_once '../components/db_connect.php' ;

if ($_GET['id']) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM hotels WHERE hotel_id = {$id}";
    // echo $sql;
    $result = $connect->query($sql);
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $name = $data['hotelName'];
        $location = $data['hotelLoc'];
        $price = $data['hotelPrice'];
        $image = $data['hotelImage'];
        $agency = $data['fk_agencyId'];

        $resultAgency = mysqli_query($connect, "SELECT * FROM agency");
        $agencyList = "";
        if(mysqli_num_rows($resultAgency) > 0){
           while ($row = $resultAgency->fetch_array(MYSQLI_ASSOC)){
               if($row['agencyId'] == $agency){
                   $agencyList .= "<option selected value='{$row['agencyId']}'>{$row['agency_name']}</option>";  
               }else {
                   $agencyList .= "<option value='{$row['agencyId']}'>{$row['agency_name']}</option>";
               }}                
           }else{
           $agencyList = "<li>There are no agencies registered</li>";
       }

    } else {
        header("location: error.php");
    }
    $connect->close();
} else {
    header("location: error.php");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Hotel</title>
        <?php require_once '../components/boot.php'?>
        <style type= "text/css">
            fieldset {
                margin: auto;
                margin-top: 100px;
                width: 60% ;
            }  
            .img-thumbnail{
                width: 70px !important;
                height: 70px !important;
            }     
        </style>
    </head>
    <body>
        <fieldset>
            <legend class='h2'>Update request <img class='img-thumbnail rounded-circle' src='../pictures/<?php echo $image ?>' alt="<?php echo $name ?>"></legend>
            <form action="actions/a_update.php"  method="post" enctype="multipart/form-data">
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <td><input class="form-control" type="text"  name="hotel_name" placeholder ="Product Name" value="<?php echo $name ?>"  /></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td><input class="form-control" type="text"  name="location" placeholder ="Location" value="<?php echo $location ?>"  /></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td><input class="form-control" type= "number" name="price" step="any"  placeholder="Price" value ="<?php echo $price ?>" /></td>
                    </tr>
                    <tr>
                        <th>Image</th>
                        <td><input class="form-control" type="file" name= "image" /></td>
                    </tr>
                    <tr>
                    <th>Agency</th>
                    <td>
                    <select select class = "form-select"   name = "agency"   aria-label = "Default select example" >
                        <?php   echo  $agencyList; ?>
                    </select>
                    </td>
                    </tr>
                    <tr>
                        <input type= "hidden" name= "hotel_id" value= "<?php echo $data['hotel_id'] ?>" />
                        <input type= "hidden" name= "image" value= "<?php echo $data['hotelImage'] ?>" />
                        <td><button class="btn btn-success" type= "submit">Save Changes</button></td>
                        <td><a href= "index.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </body>
</html>