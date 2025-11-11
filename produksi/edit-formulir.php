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
    if (isset($_POST["kirim"])){
        $id_user = $_SESSION["id"]; 
        $idproduk = $_POST['idproduk'];
        $tanggal = $_POST['tanggal'];
        $material = $_POST['material'];
        $finishing = $_POST['finishing'];
        $struktur = $_POST['struktur'];
        $dimensi = $_POST['dimensi'];
        $visual = $_POST['visual'];
        $stabilitas = $_POST['stabilitas'];
        $sambungan = $_POST['sambungan'];
        $kapasitas_beban = $_POST['kapasitas_beban'];
        $keterangan_fisik = $_POST['keterangan_fisik'] ?: 'Tidak ada keterangan';
        $keterangan_fungsi = $_POST['keterangan_fungsi'] ?: 'Tidak ada keterangan';
        // Validasi: pastikan semua kolom wajib tidak kosong
        if (
            empty($id_user) || empty($idproduk) || empty($tanggal) || 
            empty($material) || empty($finishing) || empty($struktur) || empty($dimensi) ||
            empty($visual) || empty($stabilitas) || empty($sambungan) || empty($kapasitas_beban)
        ) {
           $error = "lengkapi kolom yang tersedia";
        } else {
            $id = $_GET['ID_Laporan'];
            // Update ke database
            $query = mysqli_query($connect, "UPDATE kualitas_produk SET 
                ID_user = '$id_user',
                ID_produk = '$idproduk',
                Tanggal_produksi = '$tanggal',
                material = '$material',
                finishing = '$finishing',
                struktur = '$struktur',
                dimensi = '$dimensi',
                visual = '$visual',
                keterangan_fisik = '$keterangan_fisik',
                stabilitas = '$stabilitas',
                sambungan = '$sambungan',
                kapasitas_beban = '$kapasitas_beban',
                keterangan_fungsi = '$keterangan_fungsi'
                WHERE ID_Laporan = '$id'
            ") or die(mysqli_error($connect));
            $_SESSION['popup'] = "Data telah tersimpan!";
            header("Location: ../produksi/Keluar-TP.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulir Pemeriksaan Produk</title>
  <link rel="stylesheet" href="form.css">
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
          <img src="../gambar/FORMULIR (3).png" alt="Formulir Icon" style="width: 45px;">
          <span style="color: #003d64;font-weight: bold;">Formulir</span>
        </a>
        <a href="Keluar-TP.php">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span>Laporan Keluar</span>
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
                <p class="role">Tim Produksi</p>
            </div>    
        </header>
        <div class="judul">
            <h1>LAPORAN KELUAR</h1>
        </div>
        <main>
            <div class="container">
                <h2>Identitas Produk</h2>

                <?php if (isset($error)) : ?>
                 <p style="color: red; font-style: italic;"><?= $error; ?></p>
                <?php endif; ?>
                <?php
                $id = $_GET['ID_Laporan'];
                $query = mysqli_query($connect, "SELECT * FROM kualitas_produk WHERE ID_Laporan='$id'");
                $data = mysqli_fetch_array($query);
                ?>

                <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                <!-- LEFT COLUMN -->
                <div class="left-column">
                    <div class="form-group">
                        <label>PRODUK</label>
                        <select style="padding: 12px;" name="idproduk" id="produk-select">
                            <option value="">Pilih Produk...</option>
                            <?php
                            include '../global/connect.php';
                            $query = mysqli_query($connect, "SELECT * FROM produk");
                            while ($data1 = mysqli_fetch_array($query)){
                            ?>  
                            <option value="<?= $data1['ID_produk']; ?>" <?=$data['ID_produk'] === $data1['ID_produk'] ? 'selected' : '';?>>
                                <?=$data1['Nama']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ID_PRODUK</label>
                        <input type="text" name="idproduk_display" id="idproduk" style="padding: 12px;" readonly />
                    </div>
                </div>
                    <!-- RIGHT COLUMN -->
                <div class="right-column">
                    <div class="form-group">
                    <label>TGL_PRODUKSI</label>
                    <input type="date" style="padding: 12px;" name="tanggal" value= "<?=$data["Tanggal_produksi"];?>" />
                    </div>
                </div>
                </div>

                <h2>Pemeriksaan Fisik</h2>
                <div class="row">
                    <div class="label-col">Material & Finishing</div>
                    <div class="desc-col">
                    Warna, tekstur, dan lapisan cat merata
                    <select name="material">
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['material'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['material'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                    </div>
                    <div class="desc-col">
                    Finishing permukaan tanpa bintik atau serat kasar
                    <select name="finishing">
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['finishing'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['finishing'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                    </div>
                </div>

                <div class="row">
                    <div class="label-col">Struktur & Dimensi</div>
                    <div class="desc-col">
                    Ukuran produk sesuai spesifikasi
                    <select name="struktur">
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['struktur'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['struktur'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                    </div>
                    <div class="desc-col">
                    Semua bagian tegak lurus dan tidak miring
                    <select name="dimensi">
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['dimensi'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['dimensi'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                    </div>
                </div>

                <div class="row">
                    <div class="label-col">Cacat Visual</div>
                    <div class="desc-col">
                    Goresan, retakan, atau penyok pada permukaan
                    <select name="visual">
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['visual'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['visual'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                </div>
                </div>

                <div class="row">
                    <div class="label-col">Keterangan Tambahan</div>
                    <div class="desc-col" colspan="2">
                    <textarea value= "<?=$data["keterangan_fisik"];?>" name="keterangan_fisik"></textarea>
                    </div>
                </div>

                <h2>Pemeriksaan Fungsional</h2>
                <div class="row">
                <div class="label-col">Stabilitas</div>
                <div class="desc-col">
                    Produk stabil saat digunakan
                    <select name="stabilitas" >
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['stabilitas'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['stabilitas'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                </div>
                </div>
                <div class="row">
                <div class="label-col">Sambungan</div>
                <div class="desc-col">
                    Baut, engsel, dan sambungan kayu terpasang kuat
                    <select name="sambungan">
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['sambungan'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['sambungan'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                </div>
                </div>

                <div class="row">
                <div class="label-col">Kapasitas Beban</div>
                <div class="desc-col">
                    Produk mampu menahan beban sesuai spesifikasi
                    <select name="kapasitas_beban">
                        <option>Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya" <?= $data['kapasitas_beban'] == 'Ya' ? 'selected' : '' ?>>Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak" <?= $data['kapasitas_beban'] == 'Tidak' ? 'selected' : '' ?>>Tidak Sesuai</option>
                    </select>
                </div>
                </div>

                <div class="row">
                <div class="label-col">Keterangan Tambahan</div>
                <div class="desc-col" colspan="5">
                    <textarea value= "<?=$data["keterangan_fungsi"];?>" name="keterangan_fungsi"></textarea>
                </div>
                </div>

                <h2>Dokumentasi</h2>
                <div class="section-content documentation">
                    <div class="doc-placeholder">
                      <?php if (!empty($data['gambar'])): ?>
                          <img src="../uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Dokumentasi" class="preview-gambar">
                      <?php else: ?>
                          <p><i>Tidak ada dokumentasi gambar</i></p>
                      <?php endif; ?>
                    </div>
                </div>
                <button class="submit-btn"  name="kirim" type="submit" >SUBMIT</button>
                </form>
            </div>
        </main>
    </div>
</body>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const selectProduk = document.getElementById("produk-select");
        const inputIDProduk = document.getElementById("idproduk");

        // Saat pilihan berubah, update input ID_produk
        selectProduk.addEventListener("change", function() {
            inputIDProduk.value = this.value;
        });

        // Jika halaman dibuka dalam mode edit, isi langsung field ID_produk
        inputIDProduk.value = selectProduk.value;
    });
    
    document.getElementById("uploadBox").addEventListener("click", function() {
        document.getElementById("fileInput").click();
    });
    </script>
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
</html>