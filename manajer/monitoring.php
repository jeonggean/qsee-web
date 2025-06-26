<?php
session_start();
if (!isset($_SESSION["login"])){
  header("Location: ../global/login.php");
  exit;
}
if ($_SESSION["jabatan"]!="Manajer"){
  header("Location: ../global/login.php");
  exit;
}?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Manager</title>
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
          <span style="color: #003d64;font-weight: bold;">Dashboard</span>
        </a>
        <a href="Laporan-Tahunan.php">
          <img src="../gambar/LaporanKeluar (2).png" alt="Laporan Keluar Icon" style="width: 45px;">
          <span>Laporan Keluar</span>
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
<main>
    <section class="dashboard-cards">
      <?php
        include '../global/connect.php';
        $result3 = mysqli_query($connect, "SELECT COUNT(*) AS total FROM kualitas_produk k 
            INNER JOIN pembuangan a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk 
            WHERE evaluasi = ''");

        $result2 = mysqli_query($connect, "SELECT COUNT(*) AS total FROM kualitas_produk k 
            INNER JOIN perbaikan a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk 
            WHERE rekomendasi_perbaikan = ''");

        $result1 = mysqli_query($connect, "SELECT COUNT(*) AS total FROM kualitas_produk k 
            INNER JOIN approved a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk 
            WHERE a.statuses = 'Belum_Terbaca' OR a.statuses = ''");

        $data1 = mysqli_fetch_assoc($result1);
        $data2 = mysqli_fetch_assoc($result2);
        $data3 = mysqli_fetch_assoc($result3);

        $total = $data1['total'] + $data2['total'] + $data3['total'];

        $hasil1 = mysqli_query($connect, "SELECT COUNT(*) AS total FROM kualitas_produk k 
            INNER JOIN approved a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk 
            WHERE NOT (a.statuses = 'Belum_Terbaca' OR a.statuses = '')");

        $hasil2 = mysqli_query($connect, "SELECT COUNT(*) AS total FROM kualitas_produk k 
            INNER JOIN perbaikan a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk 
            WHERE NOT (rekomendasi_perbaikan = '')");

        $hasil3 = mysqli_query($connect, "SELECT COUNT(*) AS total FROM kualitas_produk k 
            INNER JOIN pembuangan a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk 
            WHERE NOT (evaluasi = '')");

        $info1 = mysqli_fetch_assoc($hasil1);
        $info2 = mysqli_fetch_assoc($hasil2);
        $info3 = mysqli_fetch_assoc($hasil3);
        $keluar_app = $info1['total'];
        $keluar_per = $info2['total'];
        $keluar_pem = $info3['total'];

        $history1 = mysqli_query($connect, "SELECT k.ID_produk, COUNT(a.ID_perbaikan) AS total, p.Nama
            FROM kualitas_produk k  
            INNER JOIN perbaikan a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk
            GROUP BY k.ID_produk
            ORDER BY total DESC
            LIMIT 3
            ");

        $history2 = mysqli_query($connect, "SELECT k.ID_produk, COUNT(a.ID_pembuangan) AS total, p.Nama
            FROM kualitas_produk k  
            INNER JOIN pembuangan a ON k.ID_Laporan = a.ID_Laporan 
            INNER JOIN produk p ON k.ID_produk = p.ID_produk
            GROUP BY k.ID_produk
            ORDER BY total DESC
            ");
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
                <h4 style="color: #ffffff;">APPROVED</h4>
                <p style="color: #ffffff;"><?=$keluar_app?></p>
              </div>
              <div class="sub-card perbaikan">
                <h4 style="color: #ffffff;">PERBAIKAN</h4>
                <p style="color: #ffffff;"><?=$keluar_per?></p>
              </div>
              <div class="sub-card pembuangan">
                <h4 style="color: #ffffff;">PEMBUANGAN</h4>
                <p style="color: #ffffff;"><?=$keluar_pem?></p>
              </div>
            </div>
        </div>  
        <?php
        $tahunList = ['2023', '2024', '2025'];
        $dataChart = [];

        foreach ($tahunList as $tahun) {
            $dataChart[$tahun] = [];

            for ($bulan = 1; $bulan <= 12; $bulan++) {
                $approved = mysqli_fetch_assoc(mysqli_query($connect,
                    "SELECT COUNT(*) AS total FROM kualitas_produk k 
                    INNER JOIN approved a ON k.ID_Laporan = a.ID_Laporan AND MONTH(Tanggal_produksi) = $bulan AND YEAR(Tanggal_produksi) = $tahun"
                ))['total'];

                $perbaikan = mysqli_fetch_assoc(mysqli_query($connect,
                    "SELECT COUNT(*) AS total FROM kualitas_produk k 
                      INNER JOIN perbaikan a ON k.ID_Laporan = a.ID_Laporan AND MONTH(Tanggal_produksi) = $bulan AND YEAR(Tanggal_produksi) = $tahun"
                ))['total'];

                $pembuangan = mysqli_fetch_assoc(mysqli_query($connect,
                    "SELECT COUNT(*) AS total FROM kualitas_produk k 
                    INNER JOIN pembuangan a ON k.ID_Laporan = a.ID_Laporan AND MONTH(Tanggal_produksi) = $bulan AND YEAR(Tanggal_produksi) = $tahun"
                ))['total'];

                $dataChart[$tahun][] = [
                    'approved' => (int)$approved,
                    'perbaikan' => (int)$perbaikan,
                    'pembuangan' => (int)$pembuangan,
                ];
            }
        }
        ?>
         <div class="card laporan-keluar grafik-card-qc">
           <div class="grafik-container">
            <div class="header">
            <div class="title-legend">
              <h2>GRAFIK PRODUK</h2>
              <div class="legend">
                <span><span class="dot approved"></span> Approved</span>
                <span><span class="dot perbaikan"></span> Perbaikan</span>
                <span><span class="dot pembuangan"></span> Pembuangan</span>
              </div>
            </div>
            <div class="filter">
              <label for="tahun">Pilih Tahun:</label>
              <select id="tahun" onchange="updateChart()">
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025" selected>2025</option>
              </select>
            </div>
            </div>
            <div class="grafik-content">
              <div class="y-axis" id="y-axis"></div>
              <div class="chart" id="chart"></div>
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
        
              <div class="tables-container">
                  <!-- Tabel Riwayat Perbaikan Produk Teratas -->
                  <div class="table-wrapper">
                    <div class="table-header perbaikan">
                      <div class="image">
                      <img src="../gambar/perbaikan.png" alt="Repair Icon">
                      </div>
                      <span>Riwayat Perbaikan Produk Teratas</span>
                    </div>
                    <table class="riwayat-table">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Produk</th>
                          <th>Jumlah</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $no = 1;
                        while  ($riwayat1 = mysqli_fetch_assoc($history1)){?>
                          <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $riwayat1['Nama'] ?></td>
                              <td><?= $riwayat1['total'] ?></td>
                          </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>

                  <!-- Tabel Riwayat Pembuangan Produk Teratas -->
                  <div class="table-wrapper">
                    <div class="table-header pembuangan">
                      <div class="image">
                      <img src="../gambar/pembuangan.png" alt="Repair Icon">
                      </div>
                      <span>Riwayat Pembuangan Produk Teratas</span>
                    </div>
                    <table class="riwayat-table">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Nama Produk</th>
                          <th>Jumlah</th>
                        </tr>
                      </thead>
                     <tbody>
                        <?php 
                        $no1 = 1;
                        while  ($riwayat2 = mysqli_fetch_assoc($history2)){?>
                          <tr>
                              <td><?= $no1++ ?></td>
                              <td><?= $riwayat2['Nama'] ?></td>
                              <td><?= $riwayat2['total'] ?></td>
                          </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
        </div>  
    </section>
      
</main>
    </div>
    <script>
      const data = <?php echo json_encode($dataChart); ?>;

        function updateChart() {
            const selectedYear = document.getElementById("tahun").value;
            const chart = document.getElementById("chart");
            chart.innerHTML = "";
            generateYAxis();

            data[selectedYear].forEach((bulanData, i) => {
            const bulan = ["JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGU", "SEP", "OKT", "NOV", "DES"][i];

            const bulanEl = document.createElement("div");
            bulanEl.classList.add("bulan");

            const barGroup = document.createElement("div");
            barGroup.classList.add("bar-group");

            const approvedBar = createBar("approved", bulanData.approved);
            const perbaikanBar = createBar("perbaikan", bulanData.perbaikan);
            const pembuanganBar = createBar("pembuangan", bulanData.pembuangan);

            barGroup.appendChild(approvedBar);
            barGroup.appendChild(perbaikanBar);
            barGroup.appendChild(pembuanganBar);

            const label = document.createElement("div");
            label.classList.add("label");
            label.innerText = bulan;

            bulanEl.appendChild(barGroup);
            bulanEl.appendChild(label);
            chart.appendChild(bulanEl);
            });
        }

        function createBar(type, heightValue) {
            const bar = document.createElement("div");
            bar.classList.add("bar", type);
            bar.style.height = `${heightValue * 2.5}px`;
            bar.setAttribute("data-tooltip", `${heightValue}`);
            return bar;
        }

        function generateYAxis() {
            const yAxis = document.getElementById("y-axis");
            yAxis.innerHTML = "";
            const maxY = 100;
            const steps = 5;
            for (let i = steps; i >= 0; i--) {
            const value = Math.floor((maxY / steps) * i);
            const label = document.createElement("div");
            label.innerText = value;
            yAxis.appendChild(label);
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            updateChart();

            document.getElementById("tahun").addEventListener("change", updateChart);
        });
      </script>
      
</body>
</html>