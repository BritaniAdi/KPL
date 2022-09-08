<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Halo! <?php echo htmlspecialchars($_SESSION["name"]); ?> - Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; background: #efefef;}
    </style>
</head>
<body>
    <h1 class="mt-5">Hello, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>. Selamat datang kembali.</h1>
    <p class="mb-4">Berhasil login, silahkan jika ingin menganti password bisa dengan klik tombol reset password dibawah</p>
    <p>
        <a href="reset-password.php" class="btn btn-primary">Reset password</a>
        <a href="logout.php" class="btn btn-danger ml-3">Logout</a>
    </p>
</body>
</html> 