<?php

// registration.php
require_once "config.php";
 
$name = $password = $confirm_password = "";
$name_err = $password_err = $confirm_password_err = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["name"]))){
        $name_err = "Silahkan masukan nama.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["name"]))){
        $name_err = "Isi dengan kombinasi kata, nomor atau underscore.";
    } else {
        $sql = "SELECT id FROM users WHERE name = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            // Siapkan parameter nama
            $param_name = trim($_POST["name"]);
            
            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $name_err = "Nama ini sudah terdaftar!";
                } else{
                    $name = trim($_POST["name"]);
                }
            } else{
                echo "Sepertinya terjadi error, silahkan coba lagi nanti.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Silahkan masukan password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password harus diisi dengan maksimal 6 karakter.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Silahkan konfimasi password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password tidak cocok.";
        }
    }
    
    if(empty($name_err) && empty($password_err) && empty($confirm_password_err)){
        
        $sql = "INSERT INTO users (name, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){

            mysqli_stmt_bind_param($stmt, "ss", $param_name, $param_password);
            
            $param_name = $name;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
            } else{
                echo "Sepertinya terjadi error, silahkan coba lagi nanti.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($conn);
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; background: #FFFF8F;}
        .wrapper{ width: 380px; padding: 20px; margin:0 auto; display: block; margin-top: 60px; background: #fffff0;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Daftarkan dirimu!</h2>
        <p>Bergabung dengan kami hari ini.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Sudah memiliki akun? <a href="login.php">Login disini!</a>.</p>
        </form>
    </div>    
</body>
</html> 