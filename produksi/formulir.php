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
            !isset($id_user) || empty($id_user) ||
            !isset($idproduk) || empty($idproduk) ||
            !isset($tanggal) || empty($tanggal) ||
            !isset($material) || empty($material) ||
            !isset($finishing) || empty($finishing) ||
            !isset($struktur) || empty($struktur) ||
            !isset($dimensi) || empty($dimensi) ||
            !isset($visual) || empty($visual) ||
            !isset($stabilitas) || empty($stabilitas) ||
            !isset($sambungan) || empty($sambungan) ||
            !isset($kapasitas_beban) || empty($kapasitas_beban)
        ) {
            $error = "Lengkapi semua kolom yang tersedia.";
        } else {
            $gambar_name = '';
            if ($_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $gambar_tmp = $_FILES['gambar']['tmp_name'];
                $gambar_name = basename($_FILES['gambar']['name']);
                $gambar_ext = pathinfo($gambar_name, PATHINFO_EXTENSION);
                $gambar_name = uniqid("IMG_") . '.' . $gambar_ext;

                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array(strtolower($gambar_ext), $allowed_ext)) {
                    $error = "Format file tidak didukung!";
                } elseif ($_FILES['gambar']['size'] > 26214400) {
                    $error = "Ukuran file terlalu besar!";
                } else {
                    move_uploaded_file($gambar_tmp, '../uploads/' . $gambar_name);
                }
            }
            // Buat ID_Laporan
            $ymd = date("ymd", strtotime($tanggal)); // ubah ke format YYMMDD
            $prefix = "QC-" . $ymd . "-";

            $query = "SELECT ID_Laporan FROM kualitas_produk 
                    WHERE ID_Laporan LIKE '$prefix%' 
                    ORDER BY ID_Laporan DESC LIMIT 1";
            $result = mysqli_query($connect, $query);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                $lastNumber = (int)substr($row['ID_Laporan'], -3);
                $newNumber = str_pad($lastNumber + 1, 3, "0", STR_PAD_LEFT);
            } else {
                $newNumber = "001";
            }
            $newID = $prefix . $newNumber;

            // Simpan ke database
           $query = mysqli_query($connect, "INSERT INTO kualitas_produk 
            (ID_Laporan, ID_user, ID_produk, Tanggal_produksi, material, finishing, struktur, dimensi, visual, keterangan_fisik, stabilitas, sambungan, kapasitas_beban, keterangan_fungsi, gambar)
            VALUES 
            ('$newID','$id_user', '$idproduk', '$tanggal', '$material', '$finishing', '$struktur', '$dimensi', '$visual', '$keterangan_fisik', '$stabilitas', '$sambungan', '$kapasitas_beban', '$keterangan_fungsi', '$gambar_name')
            ") or die(mysqli_error($connect));

            $_SESSION['popup'] = "Data telah tersimpan!";
            header("Location: ../produksi/Keluar-TP.php");
            exit;
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
                            while ($data = mysqli_fetch_array($query)){
                            ?>  
                            <option value="<?= $data['ID_produk']; ?>" data-nama="<?= $data['Nama']; ?>">
                                <?= $data['Nama']; ?>
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
                    <input type="date" style="padding: 12px;" name="tanggal" />
                    </div>
                </div>
                </div>

                <h2>Pemeriksaan Fisik</h2>
                <div class="row">
                    <div class="label-col">Material & Finishing</div>
                    <div class="desc-col">
                    Warna, tekstur, dan lapisan cat merata
                    <select name="material" required>
                        <option value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                    </div>
                    <div class="desc-col">
                    Finishing permukaan tanpa bintik atau serat kasar
                    <select name="finishing" required>
                        <option value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                    </div>
                </div>
                <div class="row">
                    <div class="label-col">Struktur & Dimensi</div>
                    <div class="desc-col">
                    Ukuran produk sesuai spesifikasi
                    <select name="struktur" required>
                        <option  value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                    </div>
                    <div class="desc-col">
                    Semua bagian tegak lurus dan tidak miring
                    <select name="dimensi" required>
                        <option value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                    </div>
                </div>

                <div class="row">
                    <div class="label-col">Cacat Visual</div>
                    <div class="desc-col">
                    Goresan, retakan, atau penyok pada permukaan
                    <select name="visual" required>
                        <option  value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                    </div>
                </div>

                <div class="row">
                    <div class="label-col">Keterangan Tambahan</div>
                    <div class="desc-col" colspan="2">
                    <textarea placeholder="Tuliskan keterangan lebih lanjut mengenai temuan pada pemeriksaan ini (jika ada)" name="keterangan_fisik"></textarea>
                    </div>
                </div>

                <h2>Pemeriksaan Fungsional</h2>
                <div class="row">
                <div class="label-col">Stabilitas</div>
                <div class="desc-col">
                    Produk stabil saat digunakan
                    <select name="stabilitas" required>
                        <option  value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                </div>
                </div>
                <div class="row">
                <div class="label-col">Sambungan</div>
                <div class="desc-col">
                    Baut, engsel, dan sambungan kayu terpasang kuat
                    <select name="sambungan" required>
                        <option  value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                </div>
                </div>

                <div class="row">
                <div class="label-col">Kapasitas Beban</div>
                <div class="desc-col">
                    Produk mampu menahan beban sesuai spesifikasi
                    <select name="kapasitas_beban" required>
                        <option  value="">Pilihan</option>
                        <option style="color: #1A72A9;" value="Ya">Sesuai</option>
                        <option style="color: #1A72A9;" value="Tidak">Tidak Sesuai</option>
                    </select>
                </div>
                </div>

                <div class="row">
                <div class="label-col">Keterangan Tambahan</div>
                <div class="desc-col" colspan="5">
                    <textarea placeholder="Tuliskan keterangan lebih lanjut mengenai temuan pada pemeriksaan ini (jika ada)" name="keterangan_fungsi"></textarea>
                </div>
                </div>

                <h2>Dokumentasi</h2>
                <div class="upload-box">
                    <img src="../gambar/upload.png">
                    <p style="font-size: 22px; color: #1A72A9;font-weight: 500;">Pilih File untuk Upload</p><br>
                    <input type="file" name="gambar" accept="image/*" style="padding: 10px;">
                </div>
                <button class="submit-btn"  name="kirim" type="submit">SUBMIT</button>
            </form>
            </div>
        </main>
    </div>
    <script>
        const select = document.getElementById('produk-select');
        const idProdukInput = document.getElementById('idproduk');

        select.addEventListener('change', function () {
        idProdukInput.value = this.value;
        });

        function applyFile() {
        const popupInput = document.getElementById("popupFileInput");
        const realInput = document.getElementById("fileInput");

        if (popupInput.files.length > 0) {
            const file = popupInput.files[0];
            const dt = new DataTransfer();
            dt.items.add(file);
            realInput.files = dt.files;

            alert("File berhasil dipilih!");
            closePopup();
        } else {
            alert("Pilih file terlebih dahulu!");
        }
        }
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
</body>
</html>