<?php
    include '../global/connect.php';
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
  <title>Formulir Pemeriksaan Produk</title>
  <link rel="stylesheet" href="Hasil-QC.css">
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
        <a href="Masuk-QC.php" >
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span >Laporan Masuk</span>
        </a>
        <a href="Keluar-QC-App.php" class="dropdown-toggle">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span  style="color: #003d64;font-weight: bold;">Laporan Keluar</span>
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
  <?php
        $id = $_GET['ID_Laporan'];
        $hasil1=mysqli_query($connect, "SELECT * FROM approved WHERE ID_Laporan = '$id';");
        $hasil2=mysqli_query($connect, "SELECT * FROM perbaikan WHERE ID_Laporan = '$id';");
        $hasil3=mysqli_query($connect, "SELECT * FROM pembuangan WHERE ID_Laporan = '$id';");
  ?>
  <div class="judul">
      <h1>HASIL LAPORAN 
        <?php 
          if (mysqli_num_rows($hasil1) > 0){echo "APPROVED";
          } else if (mysqli_num_rows($hasil2) >0){echo "PERBAIKAN";
          } else if (mysqli_num_rows($hasil3) >0) {echo "PEMBUANGAN";
          } ?>
      </h1>
  </div>

<main>
    <div class="container">
      <?php
        include '../global/connect.php';
        $id = $_GET['ID_Laporan'];
        $query1 = mysqli_query($connect, "SELECT * FROM kualitas_produk WHERE ID_Laporan = '$id';");
        $data1 = mysqli_fetch_array($query1);
        $ID_produk = $data1['ID_produk'];
        $query2 = mysqli_query($connect, "SELECT * FROM produk WHERE ID_produk = '$ID_produk';");
        $data2 = mysqli_fetch_array($query2);
      ?>
          <h2>IDENTITAS PRODUK</h2>
          <div class="info-column">
            <div class="info-item">
              <div class="label">Nama Produk</div>
              <div class="value"><?=$data2['Nama']?></div>
            </div>
            <div class="info-item">
              <div class="label">ID Produk</div>
              <div class="value"><?=$data1['ID_produk']?></div>
            </div>
            <div class="info-item">
              <div class="label">TGL Produksi</div>
              <div class="value"><?=$data1['Tanggal_produksi']?></div>
            </div>
          </div>
        
          <!-- Pemeriksaan Fisik -->
                <h2>PEMERIKSAAN FISIK</h2>
                <div class="info-coloumn">
                    <div class="inspection-headers">
                        <div class="header-item">MATERIAL & FINISHING</div>
                        <div class="header-item">STRUKTUR & DIMENSI</div>
                        <div class="header-item">CACAT VISUAL</div>
                        <div class="header-item">KETERANGAN TAMBAHAN</div>
                    </div>
                    <div class="inspection-content">
                        <div class="inspection-column">
                            <div class="inspection-item">
                                <div class="item-text">Warna, tekstur, dan lapisan cat merata</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['material']?>a</div>
                            </div>
                            <div class="inspection-item">
                                <div class="item-text">Finishing permukaan tanpa bintik atau serat kasar</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['finishing']?></div>
                            </div>
                        </div>
                        <div class="inspection-column">
                            <div class="inspection-item">
                                <div class="item-text">Ukuran produk sesuai spesifikasi</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['struktur']?></div>
                            </div>
                            <div class="inspection-item">
                                <div class="item-text">Semua bagian tegak lurus dan tidak miring</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['dimensi']?></div>
                            </div>
                        </div>
                        <div class="inspection-column">
                            <div class="inspection-item">
                                <div class="item-text">Goresan, retakan, atau penyok pada permukaan</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['visual']?></div>
                            </div>
                        </div>
                        <div class="inspection-column">
                            <div class="additional-info"><?=$data1['keterangan_fisik']?></div>
                        </div>
                    </div>
                </div>
                <!-- Pemeriksaan Fungsional -->
                <h2>PEMERIKSAAN FUNGSIONAL</h2>
                <div class="info-coloumn">
                    <div class="inspection-headers">
                        <div class="header-item">STABILITAS</div>
                        <div class="header-item">SAMBUNGAN</div>
                        <div class="header-item">KAPASITAS BEBAN</div>
                        <div class="header-item">KETERANGAN TAMBAHAN</div>
                    </div>
                    <div class="inspection-content">
                        <div class="inspection-column">
                            <div class="inspection-item">
                                <div class="item-text">Produk stabil saat digunakan</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['stabilitas']?></div>
                            </div>
                        </div>
                        <div class="inspection-column">
                            <div class="inspection-item">
                                <div class="item-text">Baut, engsel, dan sambungan kayu terpasang kuat</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['sambungan']?></div>
                            </div>
                        </div>
                        <div class="inspection-column">
                            <div class="inspection-item">
                                <div class="item-text">Produk mampu menahan beban sesuai spesifikasi</div>
                                <div class="divider"></div>
                                <div class="item-status"><?=$data1['kapasitas_beban']?></div>
                            </div>
                        </div>
                        <div class="inspection-column">
                            <div class="additional-info"><?=$data1['keterangan_fungsi']?></div>
                        </div>
                    </div>
                </div>
                <!-- Dokumentasi -->
                <h2>DOKUMENTASI</h2>
                <div class="section-content documentation">
                    <div class="doc-placeholder">
                      <?php if (!empty($data1['gambar'])): ?>
                          <img src="../uploads/<?= htmlspecialchars($data1['gambar']); ?>" alt="Dokumentasi" class="preview-gambar">
                      <?php else: ?>
                          <p><i>Tidak ada dokumentasi gambar</i></p>
                      <?php endif; ?>
                    </div>
                </div>
                <h2>HASIL PENILAIAN</h2>
                <?php
                  $hasil1=mysqli_query($connect, "SELECT * FROM approved WHERE ID_Laporan = '$id';");
                  $hasil2=mysqli_query($connect, "SELECT * FROM perbaikan WHERE ID_Laporan = '$id';");
                  $hasil3=mysqli_query($connect, "SELECT * FROM pembuangan WHERE ID_Laporan = '$id';");
                if (mysqli_num_rows($hasil1) > 0){
                ?>
                  <div class="approved-text" style="text-align : center;">APPROVED</div>
                <?php
                } else if (mysqli_num_rows($hasil2) >0) {?>
                  <div class="perbaikan-text" style="text-align : center;">PERBAIKAN</div>
                <?php
                } else if (mysqli_num_rows($hasil3) >0) {?>
                   <div class="perbaikan-text" style="text-align : center;">PEMBUANGAN</div>
                <?php
                } ?>
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