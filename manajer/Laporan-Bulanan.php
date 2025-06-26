<?php
include '../global/connect.php';
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../global/login.php");
    exit;
}

if ($_SESSION["jabatan"] != "Manajer") {
    header("Location: ../global/login.php");
    exit;
}?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Keluar Manajer QC</title>
  <link rel="stylesheet" href="Laporan-Bulanan.css">
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
        <a href="#">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span style="color: #003d64;font-weight: bold;">Laporan Keluar</span>
        </a>
        <a href="Masuk-Manajer-app.php" class="dropdown-toggle">
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span>Laporan Masuk</span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="Masuk-Manajer-app.php">Approved</a></li>
            <li><a href="Masuk-Manajer-per.php">Perbaikan</a></li>
            <li><a href="Masuk-Manajer-pem.php">Pembuangan</a></li>
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
        <div class="judul">
          <div class="judul-kiri">
            <a href="LaporanTahunan-MJ.html" class="back-button">
            <img src="../gambar/PanahKiri.png" alt="Back" class="icon-back">
            </a> 
            <h1>LAPORAN BULANAN <?=$_GET['tahun']?></h1>
          </div>
             <!-- TERDAPAT SEARCHING BAR -->
          <div class="search-container">
                <input type="text" placeholder="Search...">
                <img src="../gambar/search.png" alt="Search Icon">
            </div>
        </div>
        <main>
            <?php
            $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
            ?>
              <div class="tahun-container">
                <?php
            $bulan = [
            "JANUARI", "FEBRUARI", "MARET", "APRIL", "MEI", "JUNI",
            "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER"
            ];

            for ($i = 0; $i < count($bulan); $i++) {
            $namaBulan = $bulan[$i];
            $angkaBulan = str_pad($i + 1, 2, "0", STR_PAD_LEFT); // hasil: 01, 02, ..., 12

            echo <<<HTML
                <div class="tahun-card">
                <h3>$namaBulan</h3>
                <div class="bulan-buttons">
                    <a href="Keluar-Manajer.php?tahun=$tahun&bulan=$angkaBulan&tipe=approved" class="bulan-btn">Approved</a>
                    <a href="Keluar-Manajer.php?tahun=$tahun&bulan=$angkaBulan&tipe=perbaikan" class="bulan-btn">Perbaikan</a>
                    <a href="Keluar-Manajer.php?tahun=$tahun&bulan=$angkaBulan&tipe=pembuangan" class="bulan-btn">Pembuangan</a>
                </div>
                </div>
            HTML;
            }
            ?>

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