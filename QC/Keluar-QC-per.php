<?php
session_start();
if (!isset($_SESSION["login"])){
  header("Location: login.php");
  exit;
}
if ($_SESSION["jabatan"]!="Tim_QC"){
  header("Location: login.php");
  exit;
}
$pesan = "";
if (isset($_SESSION['pesan'])) {
    $pesan = $_SESSION['pesan'];
    unset($_SESSION['pesan']); // hapus setelah ditampilkan agar tidak muncul terus
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
  <title>Laporan Keluar Tim QC</title>
  <link rel="stylesheet" href="KeluarQC.css">
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
        <a href="Masuk-QC.php">
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span >Laporan Masuk</span>
        </a>
        <a href="#" class="dropdown-toggle">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span  style="color: #003d64;font-weight: bold;">Laporan Keluar</span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="Keluar-QC-App.php">Approved</a></li>
            <li><a href="#">Perbaikan</a></li>
            <li><a href="Keluar-QC-pem.php">Pembuangan</a></li>
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
                <p class="role">Tim QC</p>
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
            <h1>LAPORAN PERBAIKAN</h1>
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
                      <th>ID_PRODUK</th>
                      <th>ID_LAPORAN</th>
                      <th>ID_PERBAIKAN</th>
                      <th>NAMA PRODUK</th>
                      <th>LAPORAN</th>
                    </tr>
                  </thead>
                  <?php
                  include '../global/connect.php';
                  $result = mysqli_query($connect, "SELECT k.ID_Laporan, r.ID_user, r.ID_perbaikan, p.ID_produk, p.Nama FROM kualitas_produk k INNER JOIN perbaikan r ON  k.ID_Laporan=r.ID_Laporan INNER JOIN produk p ON k.ID_produk=p.ID_produk;");
                  ?>
                  <?php
                    while  ($data = mysqli_fetch_assoc($result)){
                    ?> 
                    <tbody>
                        <tr>
                          <td><?= $data['ID_user'] ?></td>
                          <td><?= $data['ID_produk'] ?></td>
                          <td><?= $data['ID_Laporan'] ?></td>
                          <td><?= $data['ID_perbaikan'] ?></td>
                          <td><?= $data['Nama'] ?></td>
                          <td>
                            <button class="btn laporan" onclick="window.location.href='Hasil-QC.php?ID_Laporan=<?=$data['ID_Laporan']?>'">Here</button>
                          </td>
                        </tr>
                    </tbody>
                    <?php
                    }
                  ?> 
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