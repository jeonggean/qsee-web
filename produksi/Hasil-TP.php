<?php
    include '../global/connect.php';
    session_start();
    if (!isset($_SESSION["login"])){
    header("Location: ../global/login.php");
    exit;
    }
    if ($_SESSION["jabatan"]!="Tim_produksi"){
    header("Location: ../global/login.php");
    exit;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Masuk Tim Produksi</title>
  <link rel="stylesheet" href="Hasil-TP.css">
</head>
<body>
 <aside>
    <div>
      <h1 style="text-align: center; font-size: 70px;">QSEE</h1>
      <div class="logo">
        <img src="../gambar/GambarPerusahaan.png" alt="QSEE Logo">
      </div>
      <nav>
        <a href="formulir.php">
          <img src="../gambar/FORMULIR (3).png" alt="Formulir Icon" style="width: 45px;">
          <span>Formulir</span>
        </a>
        <a href="Keluar-TP.php">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span>Laporan Keluar</span>
        </a>
        <a href="#" class="dropdown-toggle">
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span  style="color: #003d64;font-weight: bold;">Laporan Masuk</span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="Masuk-TP-app.php">Approved</a></li>
            <li><a href="Masuk-TP-per.php">Perbaikan</a></li>
            <li><a href="Masuk-TP-pem.php">Pembuangan</a></li>
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
    <p class="role">Tim Produksi</p>
  </div> 
  <!-- <span>Siti - Tim Produksi</span> -->
</header>

  <?php
      $id = $_GET['ID_Laporan'];
      $hasil1=mysqli_query($connect, "SELECT * FROM approved WHERE ID_Laporan = '$id';");
      $hasil2=mysqli_query($connect, "SELECT * FROM perbaikan WHERE ID_Laporan = '$id';");
      $hasil3=mysqli_query($connect, "SELECT * FROM pembuangan WHERE ID_Laporan = '$id';");
  ?>

<main>
    <div class="container">
      <?php
        include '../global/connect.php';
        $id = $_GET['ID_Laporan'];

        $id = mysqli_real_escape_string($connect, $_GET['ID_Laporan']); // cegah SQL injection

        if (mysqli_num_rows($hasil1) > 0) {
            // Approved
            $query1 = mysqli_query($connect, 
                "SELECT k.ID_Laporan, p.ID_produk, p.Nama, a.ID_Approved, k.tanggal_produksi 
                FROM kualitas_produk k  
                INNER JOIN produk p ON k.ID_produk = p.ID_produk 
                INNER JOIN approved a ON k.ID_Laporan = a.ID_Laporan  
                WHERE k.ID_Laporan = '$id'");
            
            $data1 = mysqli_fetch_array($query1);
            $ID_Laporan = $data1['ID_Approved'];

            $query2 = mysqli_query($connect, 
                "SELECT a.ID_Approved, a.tanggal_approved, a.ID_user, u.Nama 
                FROM approved a 
                INNER JOIN user u ON a.ID_user = u.ID_user 
                WHERE a.ID_Approved = '$ID_Laporan'");
            
            $data2 = mysqli_fetch_array($query2);
            $penilaian = "APPROVED";
            $tanggal_periksa = $data2["tanggal_approved"];

        } else if (mysqli_num_rows($hasil2) > 0) {
            // Perbaikan
            $query1 = mysqli_query($connect, 
                "SELECT k.ID_Laporan, p.ID_produk, p.Nama, a.ID_Perbaikan, k.tanggal_produksi 
                FROM kualitas_produk k  
                INNER JOIN produk p ON k.ID_produk = p.ID_produk 
                INNER JOIN perbaikan a ON k.ID_Laporan = a.ID_Laporan  
                WHERE a.ID_Laporan = '$id';");
            
            $data1 = mysqli_fetch_array($query1);
            $ID_Laporan = $data1['ID_Perbaikan'];

            $query2 = mysqli_query($connect, 
                "SELECT a.ID_perbaikan, a.tanggal_pemeriksaan, a.ID_user, u.Nama, a.evaluasi, a.rekomendasi_perbaikan
                FROM perbaikan a 
                INNER JOIN user u ON a.ID_user = u.ID_user 
                WHERE a.ID_perbaikan = '$ID_Laporan';");
            $data2 = mysqli_fetch_array($query2);
            $penilaian = "PERBAIKAN";
            $tanggal_periksa = $data2["tanggal_pemeriksaan"];

        } else if (mysqli_num_rows($hasil3) > 0) {
            // Pembuangan
            $query1 = mysqli_query($connect, 
                "SELECT k.ID_Laporan, p.ID_produk, p.Nama, a.ID_Pembuangan, k.tanggal_produksi 
                FROM kualitas_produk k  
                INNER JOIN produk p ON k.ID_produk = p.ID_produk 
                INNER JOIN pembuangan a ON k.ID_Laporan = a.ID_Laporan  
                WHERE a.ID_Laporan = '$id';");
            
            $data1 = mysqli_fetch_array($query1);
            $ID_Laporan = $data1['ID_Pembuangan'];

            $query2 = mysqli_query($connect, 
                "SELECT a.ID_Pembuangan, a.tanggal_pemeriksaan, a.ID_user, u.Nama , a.evaluasi
                FROM pembuangan a 
                INNER JOIN user u ON a.ID_user = u.ID_user 
                WHERE a.ID_Pembuangan = '$ID_Laporan'");
            
            $data2 = mysqli_fetch_array($query2);
            $penilaian = "PEMBUANGAN";
            $tanggal_periksa = $data2["tanggal_pemeriksaan"];
        }
        ?>
        <div class="judul">    
          <h1>HASIL LAPORAN <?=$penilaian?> </h1>
        </div>
          <h2>IDENTITAS QC</h2>
          <div class="info-column">
            <div class="info-item">
              <div class="label">ID QC</div>
              <div class="value"><?=$data2['ID_user']?></div>
            </div>
            <div class="info-item">
              <div class="label">Nama QC</div>
              <div class="value"><?=$data2['Nama']?></div>
            </div>
            <div class="info-item">
              <div class="label">TGL Pembuangan</div>
              <div class="value"><?=$tanggal_periksa?></div>
            </div>
          </div>
        
          <h2>IDENTITAS PRODUK</h2>
          <div class="info-column">
            <div class="info-item">
              <div class="label">Nama Produk</div>
              <div class="value"><?=$data1['Nama']?></div>
            </div>
            <div class="info-item">
              <div class="label">ID Produk</div>
              <div class="value"><?=$data1['ID_produk']?></div>
            </div>
            <div class="info-item">
              <div class="label">TGL Produksi</div>
              <div class="value"><?=$data1['tanggal_produksi']?></div>
            </div>
          </div>
          <?php
          if ((mysqli_num_rows($hasil3) > 0)||(mysqli_num_rows($hasil2) > 0)){?>
            <h2>TINJAUAN MANAJER QC</h2>
            <div class="box">
              <div class="box-title">EVALUASI</div>
              <p><?=$data2['evaluasi']?></p>
            </div>
          <?php if ((mysqli_num_rows($hasil2) > 0)){?>
            <div class="box">
            <div class="box-title">REKOMENDASI PERBAIKAN</div>
            <p><?=$data2['rekomendasi_perbaikan']?></p>
          </div>
          <?php }
          }
          ?>
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