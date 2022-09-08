<?php

// login.php

// Inisialisasi session
session_start();
 
// Cek jika user sudah login, jika sudah maka akan dialihkan ke home
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
// Tambahkan config database
require_once "config.php";
 
// Mendefinisikan masing-masing variabel dengan nilai kosong
$name = $password = "";
$name_err = $password_err = $login_err = "";
 
// Proses data jika form sudah disubmit
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Cek jika nama kosong
    if(empty(trim($_POST["name"]))){
        $name_err = "Silahkan isi nama anda.";
    } else{
        $name = trim($_POST["name"]);
    }
    
    // Cek jika password kosong
    if(empty(trim($_POST["password"]))){
        $password_err = "Silahkan isi password anda.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validasi nama dan password
    if(empty($name_err) && empty($password_err)){

        // Hubungkan dengan database
        $sql = "SELECT id, name, password FROM users WHERE name = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){

            // Ikatkan variabel kedalam statement sebagai parameter 
            mysqli_stmt_bind_param($stmt, "s", $param_name);
            
            // Set parameter
            $param_name = $name;
            
            // Lakukan eksekusi
            if(mysqli_stmt_execute($stmt)){

                // Simpan hasil
                mysqli_stmt_store_result($stmt);
                
                // Cek jika nama sudah digunakan, jika iya lakukan validasi selanjutnya
                if(mysqli_stmt_num_rows($stmt) == 1){   

                    // Ikatkan hasil variabel
                    mysqli_stmt_bind_result($stmt, $id, $name, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)){
                        
                        // Lakukan validasi password
                        if(password_verify($password, $hashed_password)){

                            // Jika password berhasil, mulai kedalam session
                            session_start();
                            
                            // Masukan data kedalam varibel session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;                            
                            
                            // Alihkan user ke halaman home
                            header("location: home.php");
                        } else{
                            // Menampilkan error jika password tidak sesuai
                            $login_err = "Password tidak sesuai.";
                        }
                    }
                } else {
                    // Menampilkan error jika nama atau password tidak sesuai
                    $login_err = "Nama atau password tidak sesuai.";
                }
            } else {
                echo "Sepertinya error, silahkan coba lagi nanti!";
            }

            // Tutup statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Tutup koneksi
    mysqli_close($conn);
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font-family: Helvetica, sans-serif ;: 14px ; background: #FFFF8F;}
        .wrapper{ width: 380px; padding: 20px; margin:0 auto; display: block; margin-top: 60px; background: #fffff0;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Masuk</h2>
        <p>Selamat datang kembali!</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Belum memiliki akun? <a href="registration.php">Daftar disini!</a>.</p>
        </form>
    </div>
</body>
</html> 