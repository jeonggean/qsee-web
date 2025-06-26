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
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Keluar Manajer QC</title>
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
          <?php
            $tahun = $_GET['tahun'] ?? '';
            $bulan = $_GET['bulan'] ?? '';
            $tipe  = $_GET['tipe'] ?? '';
            $daftarBulan = [
            '01' => 'JANUARI',
            '02' => 'FEBRUARI',
            '03' => 'MARET',
            '04' => 'APRIL',
            '05' => 'MEI',
            '06' => 'JUNI',
            '07' => 'JUNI',
            '08' => 'AGUSTUS',
            '09' => 'SEPTEMBER',
            '10' => 'OKTOBER',
            '11' => 'NOVEMBER',
            '12' => 'DESEMBER'
          ];

          if (array_key_exists($bulan, $daftarBulan)) {
              $namaBulan = $daftarBulan[$bulan];
          }
           ?>
          <div class="judul">
          <h1>LAPORAN <?=$namaBulan?> - 
            <?php 
              if ($tipe == "approved"){
                $nilai = "APPROVED";
                echo $nilai;
                $penilaian = "approved";
              } else if ($tipe == "perbaikan"){
                $nilai = "PERBAIKAN";
                echo $nilai;
                $penilaian = "perbaikan";
              } else if ($tipe == "pembuangan") {
                $nilai = "PEMBUANGAN";
                echo $nilai;
                $penilaian = "pembuangan";
              } 
              ?>- <?=$tahun?>
          </h1>
             <!-- TERDAPAT SEARCHING BAR -->
            <div class="search-container">
                <input type="text" placeholder="Search...">
                <img src="../gambar/search.png" alt="Search Icon">
            </div>
          </div>
        <main>
          <?php
                    include '../global/connect.php';
                    $result = mysqli_query($connect, "SELECT k.ID_Laporan, a.ID_user, a.ID_$penilaian, p.ID_produk, p.Nama FROM kualitas_produk k INNER JOIN $penilaian a ON  k.ID_Laporan = a.ID_Laporan 
                                            INNER JOIN produk p ON k.ID_produk=p.ID_produk
                                            WHERE MONTH(k.tanggal_produksi) = '$bulan' AND YEAR(k.tanggal_produksi) = '$tahun'");
          ?>
            <div class="container">
                <table class="laporan-table">
                  <thead>
                    <tr>
                      <th>ID_QC</th>
                      <th>ID_<?=$nilai?></th>
                      <th>ID_LAPORAN</th>
                      <th>NAMA PRODUK</th>
                      <th>LAPORAN</th>
                    </tr>
                  </thead>
                    <tbody>
                       <?php 
                       $id_penilaian = "ID_" . $penilaian;
                       while  ($data = mysqli_fetch_assoc($result)){
                        ?>
                        <tr>
                            <td><?= $data['ID_user'] ?></td>
                            <td><?= $data[$id_penilaian] ?></td>
                            <td><?= $data['ID_Laporan'] ?></td>
                            <td><?= $data['Nama'] ?></td>
                            <td><button class="btn laporan" onclick="window.location.href='Hasil-Keluar-manajer.php?ID_Laporan=<?=$data['ID_Laporan']?>'">Here</button></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table> 
            </div>
        </main>
    </div>
</body>
</html>