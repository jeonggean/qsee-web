<?php
session_start();
include '../global/connect.php';
if (!isset($_SESSION["login"])){
  header("Location: login.php");
  exit;
}
if ($_SESSION["jabatan"]!="Tim_QC"){
  header("Location: login.php");
  exit;
}?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulir Pemeriksaan Produk</title>
  <link rel="stylesheet" href="monitoring.css">
</head>
<body>
  <aside>
    <div>
      <h1 style="text-align: center; font-size: 70px;">QSEE</h1>
      <div class="logo">
        <img src="../gambar/GambarPerusahaan.png" alt="QSEE Logo">
      </div>
      <nav>
        <a href="#">
          <img src="../gambar/monitor.png" alt="Formulir Icon" style="width: 45px;">
          <span  style="color: #003d64;font-weight: bold;">Dashboard</span>
        </a>
        <a href="Masuk-QC.php" >
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span >Laporan Masuk</span>
        </a>
        <a href="Keluar-QC-App.php" class="dropdown-toggle">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span>Laporan Keluar</span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="Keluar-QC-App.php">Approved</a></li>
            <li><a href="Keluar-QC-per.php">Perbaikan</a></li>
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
  <!-- <span>Siti - Tim Produksi</span> -->
</header>
<div class="judul">
    <h1>HASIL LAPORAN APPROVED</h1>
</div>

<main>
    <section class="dashboard-cards">
        <?php
        $result = mysqli_query($connect, "SELECT COUNT(*) AS total 
        FROM kualitas_produk k
        INNER JOIN produk p ON k.ID_produk = p.ID_produk
        WHERE NOT EXISTS (
            SELECT 1 FROM approved a WHERE a.ID_Laporan = k.ID_Laporan
        )
        AND NOT EXISTS (
            SELECT 1 FROM perbaikan r WHERE r.ID_Laporan = k.ID_Laporan
        )
        AND NOT EXISTS (
            SELECT 1 FROM pembuangan b WHERE b.ID_Laporan = k.ID_Laporan
        )");

        $data = mysqli_fetch_assoc($result);
        $total = $data['total'];
        ?>
        <div class="card laporan-masuk">
            <div class="card-header">
              <div class="circle-icon">
                <img src="../gambar/LaporanMasuk (2).png" alt="Icon Masuk">
              </div>
              <h3>LAPORAN MASUK</h3>
            </div>
            <p class="jumlah"><?=$total?></p>
        </div>
          
        <div class="card laporan-keluar">
            <div class="card-header">
              <div class="circle-icon">
                <img src="../gambar/LaporanKeluar (2).png" alt="Icon Keluar">
              </div>
              <h3>LAPORAN KELUAR</h3>
            </div>
            <div class="sub-cards">
              <div class="sub-card approved">
                <?php
                $resultApproved = mysqli_query($connect, "SELECT COUNT(*) AS total_approved 
                    FROM approved");

                    $dataApproved = mysqli_fetch_assoc($resultApproved);
                    $totalApproved = $dataApproved['total_approved'];
                ?>
                <h4 style="color: #155B87;">APPROVED</h4>
                <p style="color: #155B87;"><?=$totalApproved?></p>
              </div>
              <div class="sub-card pembuangan">
                <?php
                $resultPembuangan = mysqli_query($connect, "SELECT COUNT(*) AS total_pembuangan 
                FROM pembuangan");

                $dataPembuangan = mysqli_fetch_assoc($resultPembuangan);
                $totalPembuangan = $dataPembuangan['total_pembuangan'];
                ?>
                <h4 style="color: #155B87;">PEMBUANGAN</h4>
                <p style="color: #155B87;"><?=$totalPembuangan?></p>
              </div>
              <div class="sub-card perbaikan">
                <?php
                $resultPerbaikan = mysqli_query($connect, "SELECT COUNT(*) AS total_perbaikan 
                FROM perbaikan");

                $dataPerbaikan = mysqli_fetch_assoc($resultPerbaikan);
                $totalPerbaikan = $dataPerbaikan['total_perbaikan'];
                ?>
                <h4 style="color: #155B87;">PERBAIKAN</h4>
                <p style="color: #155B87;"><?=$totalPerbaikan?></p>
              </div>
            </div>
        </div>  

        <div class="card laporan-masuk kriteria-card-qc">
            <div class="card-header">
              <div class="circle-icon">
                <img src="../gambar/Penilaian.png" alt="Icon Keluar">
              </div>
              <h3>KRITERIA PENILAIAN QUALITY CONTROL</h3>
            </div>
        
            <div class="sub-cards">
              <div class="sub-card kriteria-card approved">
                <h4>✅</h4>
                <h4>APPROVED</h4>
                <p style="font-size: 22px;">Produk memenuhi seluruh standar kualitas tanpa cacat.</p>
              </div>
              <div class="sub-card kriteria-card perbaikan">
                <h4>🔧</h4>
                <h4 style="color: rgb(81, 76, 42);">PERBAIKAN</h4>
                <p style="font-size: 22px; color: rgb(81, 76, 42);">Produk memiliki cacat ringan yang masih dapat diperbaiki.</p>
              </div>
              <div class="sub-card kriteria-card pembuangan">
                <h4>🚫</h4>
                <h4>PEMBUANGAN</h4>
                <p style="font-size: 22px;">Produk rusak berat atau tidak sesuai spesifikasi yang ditetapkan.</p>
              </div>
            </div>
          </div>  
    </section>
      
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