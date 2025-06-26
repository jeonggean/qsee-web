<?php
session_start();
if (!isset($_SESSION["login"])){
  header("Location: ../global/login.php");
  exit;
}
if ($_SESSION["jabatan"]!="Manajer"){
  header("Location: ../global/login.php");
  exit;
}
$popup = "";
    if (isset($_SESSION['popup'])) {
        $popup = $_SESSION['popup'];
        unset($_SESSION['popup']);
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Masuk Manajer QC</title>
  <link rel="stylesheet" href="MasukManajer.css">
</head>
<body>
  <aside>
    <div>
      <h1 style="text-align: center; font-size: 70px;">QSEE</h1>
      <div class="logo">
        <img src="../gambar/GambarPerusahaan.png" alt="QSEE Logo">
      </div>
      <nav>
        <a href="monitoring.php">
          <img src="../gambar/monitor.png" alt="Formulir Icon" style="width: 45px;">
          <span>Dashboard</span>
        </a>
        <a href="Laporan-Tahunan.php">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span>Laporan Keluar</span>
        </a>
        <a href="#" class="dropdown-toggle">
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span  style="color: #003d64;font-weight: bold;">Laporan Masuk</span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="Masuk-Manajer-app.php">Approved</a></li>
            <li><a href="Masuk-Manajer-per.php">Perbaikan</a></li>
            <li><a href="#">Pembuangan</a></li>
        </ul>
      </nav>
    </div>
    <div class="logout">
            <a href="../global/logout.php">
            <img src="../gambar/logout.png" alt="Logout Icon" />
            <span style="text-decoration: none;">Logout</span>
            </a>
      </div>
  </aside>

    <div class="main-container">
        <!-- STICKY -->
        <header>
            <!-- DIUBAH GAMBARNYA -->
            <img src="../gambar/GambarPerusahaan.png" alt="Siti">
            <div class="user-info">
                <p class="name"><?= $_SESSION["nama"]?></p>
                <p class="role">Manager QC</p>
            </div> 
        </header>
        <?php if ($popup): ?>
          <div id="popup" style="position: fixed; top: 20px; right: 20px; background: #4CAF50; color: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.3); z-index: 999;">
            <?= $popup ?>
          </div>
          <script>
            setTimeout(() => {
              document.getElementById("popup").style.display = "none";
            }, 3000); // popup hilang setelah 3 detik
          </script>
        <?php endif; ?>
        <div class="judul">
            <h1>LAPORAN MASUK PEMBUANGAN</h1>
             <!-- TERDAPAT SEARCHING BAR -->
            <div class="search-container">
                <input type="text" placeholder="Search...">
                <img src="../gambar/search.png" alt="Search Icon">
            </div>
        </div>
        <main>
            <div class="container">
                <table class="laporan-table">
                  <thead>
                    <tr>
                      <th>ID_QC</th>
                      <th>ID_PEMBUANGAN</th>
                      <th>ID_LAPORAN</th>
                      <th>NAMA PRODUK</th>
                      <th>LAPORAN</th>
                    </tr>
                  </thead>
                <?php
                    include '../global/connect.php';
                    $result = mysqli_query($connect, "SELECT k.ID_Laporan, a.ID_user, a.ID_pembuangan, p.ID_produk, p.Nama FROM kualitas_produk k INNER JOIN pembuangan a ON  k.ID_Laporan=a.ID_Laporan INNER JOIN produk p ON k.ID_produk=p.ID_produk WHERE evaluasi='';");
                ?>
                    <tbody>
                       <?php while  ($data = mysqli_fetch_assoc($result)){?>
                        <tr>
                            <td><?= $data['ID_user'] ?></td>
                            <td><?= $data['ID_pembuangan'] ?></td>
                            <td><?= $data['ID_Laporan'] ?></td>
                            <td><?= $data['Nama'] ?></td>
                            <td><button class="btn laporan" onclick="window.location.href='Hasil-Masuk-manajer.php?ID_Laporan=<?=$data['ID_Laporan']?>'">Here</button></td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table> 
            </div>
        </main>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
          const dropdownToggle = document.querySelector(".dropdown-toggle");
          const dropdownMenu = document.querySelector(".dropdown-menu");
      
          dropdownToggle.addEventListener("click", function (e) {
            e.preventDefault();
            dropdownToggle.classList.toggle("active");
          });
        });
      </script>   
</body>
</html>