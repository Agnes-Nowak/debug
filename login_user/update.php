<?php
session_start();
require_once 'components/db_connect.php';
require_once 'components/file_upload.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$backBtn = '';
//if it is a user it will create a back button to home.php
if (isset($_SESSION["user"])) {
    $backBtn = "home.php";
}
//if it is a adm it will create a back button to dashboard.php
if (isset($_SESSION["adm"])) {
    $backBtn = "dashBoard.php";
}
if (isset($_SESSION["user"])) {
    $session = $_SESSION["user"];
} else {
    $session = $_SESSION["adm"];
}
$sql = "SELECT * FROM user WHERE id = {$session}";

$result = mysqli_query($connect, $sql);

$row = $result->fetch_assoc();
// var_dump($row);
//fetch and populate form
if ($row["status"] == "adm") {
    if (isset($_GET['id'])) {
        $id = $_GET['id']; # $_SESSION["user"];
        $sql = "SELECT * FROM user WHERE id = {$id}";
        $result = $connect->query($sql);
        if ($result->num_rows == 1) {
            $data = $result->fetch_assoc();
            $f_name = $data['f_name'];
            $l_name = $data['l_name'];
            $email = $data['email'];
            $date_birth = $data['date_of_birth'];
            $image = $data['image'];
        }
    }
} else {
    $id = $_SESSION["user"];
    $sql = "SELECT * FROM user WHERE id = {$id}";
    $result = $connect->query($sql);
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $f_name = $data['f_name'];
        $l_name = $data['l_name'];
        $email = $data['email'];
        $date_birth = $data['date_of_birth'];
        $image = $data['image'];
    }
}
//update
$class = 'd-none';
if (isset($_POST["submit"])) {
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];
    $id = $_POST['id']; # $_SESSION["user"];
    //variable for upload imas errors is initialized
    $uploadError = '';
    $imageArray = file_upload($_FILES['image']); //file_upload() called
    $image = $imageArray->fileName;
    if ($imageArray->error === 0) {
        ($_POST["imag"] == "avatar.png") ?: unlink("pictures/{$_POST["image"]}");
        $sql = "UPDATE user SET f_name = '$f_name', l_name = '$l_name', email = '$email', date_of_birth = '$date_of_birth', imag = '$imageArray->fileName' WHERE id = {$id}";
    } else {
        $sql = "UPDATE user SET f_name = '$f_name', l_name = '$l_name', email = '$email', date_of_birth = '$date_of_birth' WHERE id = {$id}";
    }
    if ($connect->query($sql) === true) {
        $class = "alert alert-success";
        $message = "The record was successfully updated";
        $uploadError = ($imageArray->error != 0) ? $imageArray->ErrorMessage : '';
        header("refresh:3;url=update.php?id={$id}");
    } else {
        $class = "alert alert-danger";
        $message = "Error while updating record : <br>" . $connect->error;
        $uploadError = ($imageArray->error != 0) ? $imageArray->ErrorMessage : '';
        header("refresh:3;url=update.php?id={$id}");
    }
}
$connect->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <?php require_once 'components/boot.php' ?>
    <style type="text/css">
        fieldset {
            margin: auto;
            margin-top: 100px;
            width: 60%;
        }

        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="<?php echo $class; ?>" role="alert">
            <p><?php echo ($message) ?? ''; ?></p>
            <p><?php echo ($uploadError) ?? ''; ?></p>
        </div>
        <h2>Update</h2>
        <img class='img-thumbnail rounded-circle' src='pictures/<?php echo $data['image'] ?>' alt="<?php echo $f_name ?>">
        <form method="post" enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <th>First Name</th>
                    <td><input class="form-control" type="text" name="f_name" placeholder="First Name" value="<?php echo $f_name ?>" /></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><input class="form-control" type="text" name="l_name" placeholder="Last Name" value="<?= $l_name ?>" /></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo $email ?>" /></td>
                </tr>
                <tr>
                    <th>Date of birth</th>
                    <td><input class="form-control" type="date" name="date_of_birth" placeholder="Date of birth" value="<?php echo $date_birth ?>" /></td>
                </tr>
                <tr>
                    <th>Image
                    </th>
                    <td><input class="form-control" type="file" name="image" /></td>
                </tr>
                <tr>
                
                
                    <input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
                    <input type="hidden" name="image" value="<?php echo $image?>" />
                    <td><button name="submit" class="btn btn-success" type="submit">Save Changes</button></td>
                    <td><a href="<?php echo $backBtn ?>"><button class="btn btn-warning" type="button">Back</button></a></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>