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
  <link rel="stylesheet" href="Laporan-Tahunan.css">
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
            <h1>LAPORAN TAHUNAN</h1>
             <!-- TERDAPAT SEARCHING BAR -->
            <div class="search-container">
                <input type="text" placeholder="Search...">
                <img src="../gambar/search.png" alt="Search Icon">
            </div>
        </div>
        <main>
            <div class="tahun-container">
                <div class="tahun-card">
                    <span>2020</span>
                    <a href="Laporan-Bulanan.php?tahun=2020" class="download-link" title="Lihat laporan 2020">
                    <img src="../gambar/PanahBawah.png" alt="Download 2020">
                    </a>
                </div>
                <div class="tahun-card">
                    <span>2021</span>
                    <a href="Laporan-Bulanan.php?tahun=2021" class="download-link" title="Lihat laporan 2021">
                    <img src="../gambar/PanahBawah.png" alt="Download 2021">
                    </a>
                </div>
                <div class="tahun-card">
                    <span>2022</span>
                    <a href="Laporan-Bulanan.php?tahun=2022" class="download-link" title="Lihat laporan 2022">
                    <img src="../gambar/PanahBawah.png" alt="Download 2022">
                    </a>
                </div>
                <div class="tahun-card">
                    <span>2023</span>
                    <a href="Laporan-Bulanan.php?tahun=2023" class="download-link" title="Lihat laporan 2023">
                    <img src="../gambar/PanahBawah.png" alt="Download 2023">
                    </a>
                </div>
                <div class="tahun-card">
                    <span>2024</span>
                    <a href="Laporan-Bulanan.php?tahun=2024" class="download-link" title="Lihat laporan 2024">
                    <img src="../gambar/PanahBawah.png" alt="Download 2024">
                    </a>
                </div>
                <div class="tahun-card">
                    <span>2025</span>
                    <a href="Laporan-Bulanan.php?tahun=2025" class="download-link" title="Lihat laporan 2025">
                    <img src="../gambar/PanahBawah.png" alt="Download 2025">
                    </a>
                </div>
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