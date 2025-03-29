<style>
    .statistic-card {
        text-align: center;
    }

</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-content">
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";
    
    $query = mysqli_query($koneksi, "
        SELECT 
            (SELECT COUNT(*) FROM siswa) AS total_siswa,
            (SELECT COUNT(*) FROM jurusan) AS total_jurusan,
            (SELECT COUNT(*) FROM sertifikat) AS total_sertifikat,
            (SELECT COUNT(*) FROM kegiatan) AS total_kegiatan,
            (SELECT COUNT(*) FROM kategori) AS total_kategori
    ");
    $data = mysqli_fetch_assoc($query);
    
    // $chart_kelas = mysqli_query($koneksi, "
    //     SELECT siswa.kelas, COUNT(sertifikat.nis) AS jumlah_sertifikat
    //     FROM siswa
    //     LEFT JOIN sertifikat ON siswa.nis = sertifikat.nis
    //     GROUP BY siswa.kelas
    //     ORDER BY siswa.kelas;
    // ");
    
    $chart_jurusan = mysqli_query($koneksi, "
        SELECT jurusan.jurusan, COUNT(sertifikat.nis) AS jumlah_sertifikat
        FROM siswa
        JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan
        LEFT JOIN sertifikat ON siswa.nis = sertifikat.nis
        GROUP BY jurusan.jurusan
        ORDER BY jurusan.jurusan;
    ");
    
    $chart_angkatan = mysqli_query($koneksi, "
        SELECT siswa.angkatan, COUNT(sertifikat.nis) AS jumlah_sertifikat
        FROM siswa
        LEFT JOIN sertifikat ON siswa.nis = sertifikat.nis
        GROUP BY siswa.angkatan
        ORDER BY siswa.angkatan;
    ");

    function fetchChartData($query, $label, $data) {
        $labels = [];
        $dataPoints = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $labels[] = $row[$label];
            $dataPoints[] = (int) $row[$data];
        }
        return ["labels" => $labels, "data" => $dataPoints];
    }
    
    $chart_data = [
        "jurusan" => fetchChartData($chart_jurusan, "jurusan", "jumlah_sertifikat"),
        // "kelas" => fetchChartData($chart_kelas, "kelas", "jumlah_sertifikat"),
        "angkatan" => fetchChartData($chart_angkatan, "angkatan", "jumlah_sertifikat")
    ];
    
    ?>

    <div class="home-container">
        <div class="ui five stackable cards">
        <?php
        $cards = [
            ["Total Siswa", $data['total_siswa'], "users", "blue"],
            ["Total Jurusan", $data['total_jurusan'], "graduation cap", "purple"],
            ["Total Kategori", $data['total_kategori'], "clipboard list", "red"],
            ["Total Kegiatan", $data['total_kegiatan'], "clipboard list", "orange"],
            ["Total Sertifikat", $data['total_sertifikat'], "certificate", "green"]
        ];
        foreach ($cards as $card) {
            echo "<div class='ui card statistic-card'>
                    <div class='content'>
                            <div class='ui {$card[3]} massive circular label'>{$card[1]}</div>
                            <div class='ui hidden divider'></div>
                            <div class='ui {$card[3]} bottom attached label'><i class='{$card[2]} icon'></i> {$card[0]}</div>
                    </div>
                  </div>";
        }
        ?>
        </div>
        
        <div class="ui two stackable cards">
            <div class="card">
                <div class="content">
                    <div class="ui large grey center aligned top attached label"><i class="certificate icon"></i> Sertifikat Menunggu Validasi</div>
                    <table class="ui celled scrolling table">
                        <thead>
                            <tr>
                                <th class="two wide">No</th>
                                <th>Nama Siswa</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Kegiatan</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "
                            SELECT 
                                sertifikat.id_sertifikat, 
                                siswa.nama_siswa, 
                                sertifikat.tgl_upload, 
                                kegiatan.jenis_kegiatan, 
                                kategori.kategori
                            FROM sertifikat 
                            JOIN siswa ON sertifikat.nis = siswa.nis 
                            JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan 
                            JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori 
                            WHERE sertifikat.status = 'menunggu validasi' 
                            ORDER BY sertifikat.tgl_upload ASC;
                                ");
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>
                                        <td class='two wide'>{$no}</td>
                                        <td>{$row['nama_siswa']}</td>
                                        <td>{$row['tgl_upload']}</td>
                                        <td>{$row['jenis_kegiatan']}</td>
                                        <td>{$row['kategori']}</td>
                                      </tr>";
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="content">
                <div class="ui large grey center aligned top attached label"><i class="certificate icon"></i> Jumlah Sertifikat</div>
                <div class="ui form">
                    <div class="field">
                        <label>Pilih Kategori</label>
                        <select id="categorySelect" class="ui dropdown">
                            <option value="jurusan">Jurusan</option>
                            <!-- <option value="kelas">Kelas</option> -->
                            <option value="angkatan">Angkatan</option>
                        </select>
                    </div>
                </div>
                <canvas id="certChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.ui.dropdown').dropdown();
    });
    
    let chartData = <?php echo json_encode($chart_data); ?>;
    let ctx = document.getElementById('certChart').getContext('2d');
    let colors = ['#3498db', '#2ecc71','#f39c12' , '#9b59b6', '#e74c3c'];
    let certChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.jurusan.labels,
            datasets: [{
                label: 'Jumlah Sertifikat',
                data: chartData.jurusan.data,
                backgroundColor: colors, // Warna lebih menarik
            borderColor: colors.map(color => color.replace('0.5', '1')), // Border lebih tegas
            borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
            
        }
    });
    
    $('#categorySelect').change(function() {
        let category = $(this).val();
        certChart.data.labels = chartData[category].labels;
        certChart.data.datasets[0].data = chartData[category].data;
        certChart.update();
    });
    document.addEventListener("DOMContentLoaded", function () {
        let numbers = document.querySelectorAll(".statistic-card .header");
        numbers.forEach(num => {
            let target = +num.innerText;
            let count = 0;
            let speed = Math.floor(2000 / target); 
            let interval = setInterval(() => {
                count++;
                num.innerText = count;
                if (count >= target) clearInterval(interval);
            }, speed);
        });
    });
</script>
