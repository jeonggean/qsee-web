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
  <link rel="stylesheet" href="Masuk-TP.css">
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
              <p class="role">Tim Produksi</p>
            </div> 
        </header>
        <div class="judul">
            <h1>LAPORAN PEMBUANGAN</h1>
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
                      <th>ID_LAPORAN</th>
                      <th>PRODUK</th>
                      <th>ID_PRODUK</th>
                      <th>AKSI</th>
                    </tr>
                  </thead>
                  <?php
                    include '../global/connect.php';
                    $result = mysqli_query($connect, "SELECT k.ID_Laporan, k.ID_user, a.ID_Pembuangan, p.ID_produk, p.Nama FROM kualitas_produk k INNER JOIN pembuangan a ON  k.ID_Laporan=a.ID_Laporan INNER JOIN produk p ON k.ID_produk=p.ID_produk WHERE a.evaluasi IS NOT NULL and a.evaluasi!= '';");
                  ?>
                      <tbody>
                  <?php
                      while  ($data = mysqli_fetch_assoc($result)){
                      ?> 
                          <tr>
                            <td><?= $data['ID_Laporan'] ?></td>
                            <td><?= $data['Nama'] ?></td>
                            <td><?= $data['ID_produk'] ?></td>
                            <td>
                              <button class="btn here" onclick="window.location.href='Hasil-TP.php?ID_Laporan=<?=$data['ID_Laporan']?>'">Here</button>
                            </td>
                          </tr>
                  <?php
                      }
                  ?>
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
