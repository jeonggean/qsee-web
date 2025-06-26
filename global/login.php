<?php
  session_start();
  include 'connect.php';
    if (isset($_POST["login"])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $result = mysqli_query($connect, "SELECT * FROM user WHERE `e-mail` = '$username'");

        // Cek username
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            // Cek password
            if ($password == $row['password']) { //belum dienkripsi
                // Set session
                $_SESSION["login"] = true;
                $_SESSION["jabatan"] = $row['Jabatan'];
                $_SESSION["id"] = $row['ID_user'];
                $_SESSION["nama"] = $row['Nama'];
                if ($row['Jabatan']=="Tim_QC") {
                    header("Location: ../QC/monitoring.php");
                } else if ($row['Jabatan']=="Tim_produksi") {
                    header("Location: ../produksi/formulir.php");
                }else if ($row['Jabatan']=="Manajer") {
                    header("Location: ../manajer/monitoring.php");}
                exit;
            } else {
                $error = "Username atau password salah";
            }
        } else {
            $error = "Username atau password salah";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login QC System</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="container-a">
    <div class="left-panel">
      <div class="logo-section">
       <!-- Logo dan Nama Website -->
        <div class="logo-container">
            <img src="../gambar/GambarPerusahaan.png" alt="QSEE Logo" class="logo" /> 
            <p class="brand-name" style="font-size: 80px;">QSEE</p>
        </div>
        <p class="website-QC" style="font-size: 40px;">Sistem Informasi Quality Control<br />PT XYZ</p>
        <img src="../gambar/perusahaan.jpg" alt="Company" class="company-image" />
      </div>
    </div>
    </div>

    <div class="container-b">
        <div class="right-panel">
        <h2>Login</h2>
        <p>Silahkan login terlebih dahulu</p>
        
        <?php if (isset($error)) : ?>
        <p style="color: red; font-style: italic;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="post" id="loginForm">
          <input type="text" name="username" id="username" placeholder="Username" required />
          <input style="margin-top: 20px;"  name="password" type="password" id="password" placeholder="Password" required />
          <button type="submit" name="login" style="margin-top: 40px;" type="submit">Login</button>
        </form>
        </div>
    </div>
  <script src="script.js"></script>
</body>
</html>
