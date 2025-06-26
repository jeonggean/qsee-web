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
    
    if (isset($_POST["hapus"])) {
    $id = $_GET['ID_Laporan'];
    $query = mysqli_query($connect, "DELETE FROM kualitas_produk WHERE ID_Laporan = '$id'");
    if ($query) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='Keluar-TP.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data');</script>";
    }
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
  <title>Laporan Keluar Tim Produksi</title>
  <link rel="stylesheet" href="Keluar-TP.css">
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
          <span style="color: #003d64;font-weight: bold;">Laporan Keluar</span>
        </a>
        <a href="Masuk-TP-app.php" class="dropdown-toggle">
            <img src="../gambar/LaporanMasuk (2).png" alt="Laporan Masuk Icon" style="width: 45px;">
            <span>Laporan Masuk</span>
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
            <h1>LAPORAN KELUAR</h1>
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
                      <th>STATUS</th>
                    </tr>
                  </thead>
                    <?php
                        include '../global/connect.php';
                        $id = $_SESSION["id"];
                        $result = mysqli_query($connect, "SELECT k.ID_Laporan,p.ID_produk, p.Nama FROM kualitas_produk k INNER JOIN produk p ON k.ID_produk=p.ID_produk WHERE k.ID_user = '$id' ORDER BY k.tanggal_produksi");
                    ?>
                    <?php
                        while ($data = mysqli_fetch_assoc($result)){
                    ?> 
                    <tbody>
                        <tr>
                            <td><?= $data['ID_Laporan'] ?></td>
                            <td><?= $data['Nama'] ?></td>
                            <td><?= $data['ID_produk'] ?></td>
                    <?php
                            $laporan = $data['ID_Laporan'];
                            $result1 = mysqli_query($connect, "SELECT * FROM approved WHERE ID_Laporan = '$laporan'");
                            $result2 = mysqli_query($connect, "SELECT * FROM perbaikan WHERE ID_Laporan = '$laporan'");
                            $result3 = mysqli_query($connect, "SELECT * FROM pembuangan WHERE ID_Laporan = '$laporan'");
                            //cek username
                            if (mysqli_num_rows($result1) || mysqli_num_rows($result2) || mysqli_num_rows($result3)== 1){ 
                    ?>
                            <td>
                            <button class="btn unavailable">Update</button> 
                            <button class="btn unavailable">delete</button>
                            </td>
                            <td><span class="status sudah">✔ Sudah Dicek</span></td>
                        </tr>
                    </tbody>
                    <?php
                    } else{
                    ?> 
                        <td>
                          <button class="btn update" onclick="window.location.href='edit-formulir.php?ID_Laporan=<?= $data['ID_Laporan'] ?>'">Update</button>
                          <form method="post" action="?ID_Laporan=<?= $data['ID_Laporan'] ?>" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                              <button class="btn delete" name="hapus" type="submit">Delete</button>
                          </form>
                        </td>
                        <td><span class="status belum">✖ Belum Dicek</span></td>
                        </tr>
                    </tbody>
                    <?php } 
                    } ?>
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