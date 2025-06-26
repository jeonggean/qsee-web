<?php
session_start();
if (!isset($_SESSION["login"])){
  header("Location: ../global/login.php");
  exit;
}
if ($_SESSION["jabatan"]!="Tim_QC"){
  header("Location: ../global/login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Masuk Tim QC</title>
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
        <a href="#" >
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span  style="color: #003d64;font-weight: bold;">Laporan Masuk</span>
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
        </header>
        <div class="judul">
            <h1>LAPORAN MASUK</h1>
             <!-- TERDAPAT SEARCHING BAR -->
            <div class="search-container">
                <input type="text" placeholder="Search..." name="search">
                <img src="../gambar/search.png" alt="Search Icon">
            </div>
        </div>
        <main>
                    <div class="container">
                          <table class="laporan-table">
                            <thead>
                              <tr>
                                <th>ID_PRODUK</th>
                                <th>ID_LAPORAN</th>
                                <th>NAMA PRODUK</th>
                                <th>STATUS</th>
                                <th>LAPORAN</th>
                              </tr>
                            </thead>
                    <?php
                      include '../global/connect.php';
                      $result = mysqli_query($connect, "SELECT k.ID_Laporan, p.ID_produk, p.Nama 
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
                      ) ORDER BY k.Tanggal_produksi DESC;
                      ");
                    ?>
                    <tbody>
                    <?php while ($data = mysqli_fetch_assoc($result)) { ?>
                      <tr>
                          <td><?= $data['ID_produk'] ?></td>
                          <td><?= $data['ID_Laporan'] ?></td>
                          <td><?= $data['Nama'] ?></td>
                          <td><button class="btn laporan">⏳ Tunggu Penilaian</button></td>
                          <td>
                              <button class="btn laporan" onclick="window.location.href='Input-Masuk-QC.php?ID_Laporan=<?= $data['ID_Laporan'] ?>'">Here</button>
                          </td>
                      </tr>
                  <?php } 
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