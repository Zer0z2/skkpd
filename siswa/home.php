<style>
    .home-container {
        margin: 20px;
    }
    .progress-container {
        display: flex;
        justify-content: center;
    }
    .progress-circle {
        position: relative;
        width: 100%;
        height: 300px; /* Sesuaikan dengan tinggi chart */
    }

    .chart-container {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .progress-text {
        position: absolute;
        top: 50%; /* Posisikan di tengah vertikal */
        left: 50%; /* Posisikan di tengah horizontal */
        transform: translate(-50%, -50%); /* Pusatkan teks */
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        z-index: 1; /* Pastikan teks di atas chart */
    }
    .statistic-card {
        text-align: center;
    }
    .disabled-link {
        pointer-events: none;
        opacity: 0.5;
        cursor: default;
    }
</style>
<div class="main-content">
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";
    $nis = $_COOKIE['nis'];

    // Ambil data sertifikat
    $query = "SELECT status, COUNT(*) AS total FROM sertifikat WHERE nis = $nis GROUP BY status";
    $result = mysqli_query($koneksi, $query);

    $total_sertif = 0;
    $total_valid = 0;
    $total_tidak_valid = 0;
    $total_tunggu = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $total_sertif += $row['total'];
        if ($row['status'] == 'Valid') {
            $total_valid = $row['total'];
        } elseif ($row['status'] == 'Tidak Valid') {
            $total_tidak_valid = $row['total'];
        } elseif ($row['status'] == 'Menunggu Validasi') {
            $total_tunggu = $row['total'];
        }
    }
    
    // Ambil total poin yang didapat
    $poin = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT IFNULL(SUM(kegiatan.angka_kredit), 0) AS total_kredit
    FROM sertifikat
    JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan
    WHERE sertifikat.status = 'Valid' AND sertifikat.nis = '$nis';
    "))['total_kredit'];

    $maxPoin = 30;
    $sisaPoin = $maxPoin - $poin;

    $chart_kategori = mysqli_query($koneksi, "
        SELECT kategori.kategori, COUNT(sertifikat.nis) AS jumlah_sertifikat
        FROM kategori
        JOIN kegiatan ON kategori.id_kategori = kegiatan.id_kategori
        JOIN sertifikat ON kegiatan.id_kegiatan = sertifikat.id_kegiatan
        WHERE sertifikat.nis = $nis and sertifikat.status = 'Valid'
        GROUP BY kategori.kategori
        ORDER BY kategori.kategori;
    ");
    
    $chart_sub_kategori = mysqli_query($koneksi, "
        SELECT kategori.sub_kategori, COUNT(sertifikat.nis) AS jumlah_sertifikat
        FROM kategori
        JOIN kegiatan ON kategori.id_kategori = kegiatan.id_kategori
        JOIN sertifikat ON kegiatan.id_kegiatan = sertifikat.id_kegiatan
        WHERE sertifikat.nis = $nis and sertifikat.status = 'Valid'
        GROUP BY kategori.sub_kategori
        ORDER BY kategori.sub_kategori;
    ");
    
    $chart_kegiatan = mysqli_query($koneksi, "
        SELECT kegiatan.jenis_kegiatan, COUNT(sertifikat.nis) AS jumlah_sertifikat
        FROM kegiatan
        JOIN sertifikat ON kegiatan.id_kegiatan = sertifikat.id_kegiatan
        WHERE sertifikat.nis = $nis and sertifikat.status = 'Valid'
        GROUP BY kegiatan.jenis_kegiatan
        ORDER BY kegiatan.jenis_kegiatan;
    ");
    
    function fetchChartData($query, $label, $data) {
        $labels = [];
        $dataPoints = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $labels[] = $row[$label] ?? 'Tidak Diketahui';
            $dataPoints[] = (int) $row[$data];
        }
        return ["labels" => $labels, "data" => $dataPoints];
    }
    
    $chart_data = [
        "kategori" => fetchChartData($chart_kategori, "kategori", "jumlah_sertifikat"),
        "sub_kategori" => fetchChartData($chart_sub_kategori, "sub_kategori", "jumlah_sertifikat"),
        "kegiatan" => fetchChartData($chart_kegiatan, "jenis_kegiatan", "jumlah_sertifikat")
    ];
    ?>

<div class="home-container">
    <div class="ui four stackable cards">
        <!-- Kartu Statistik -->
        <?php 
        $cards = [
            ["Total Sertifikat", $total_sertif, "certificate", "black"],
            ["Sertifikat Valid", $total_valid, "check", "green"],
            ["Sertifikat Tidak Valid", $total_tidak_valid, "times", "red"],
            ["Sertifikat Menunggu Validasi", $total_tunggu, "hourglass", "yellow"]
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

    <div class="ui three stackable cards">
        <!-- Kartu Poin Terkumpul -->
        <div class="card">
            <div class="content">
                <div class="ui large blue center aligned top attached label"><i class='star icon'></i>Poin Terkumpul</div>
                <div class="ui hidden divider"></div>
                <div class="description">
                    <div class="progress-container">
                        <div class="progress-circle">
                            <div class="chart-container">
                                <canvas id="poinChart"></canvas>
                                <div class="progress-text"><?php echo $poin; ?>/<?php echo $maxPoin; ?></div>
                            </div>
                            <div class="ui divider"></div>
                            <button class="ui primary fluid button <?php echo ($poin >= $maxPoin) ? '' : 'disabled'; ?>" 
                                    <?php if ($poin >= $maxPoin): ?> 
                                        onclick="window.open('/skkpd/siswa/cetak_sertifikat.php', '_blank')"
                                    <?php else: ?> 
                                        disabled 
                                    <?php endif; ?>>
                                <i class='file pdf icon'></i>
                                Download Sertifikat
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Distribusi Poin -->
        <div class="card">
            <div class="content">
                <div class="ui large blue center aligned top attached label"><i class="star icon"></i>Distribusi Poin</div>
                <div class="description">
                    <div class="ui hidden divider"></div>
                    <div class="chart-container">
                        <canvas id="certChart"></canvas>
                    </div>
                    <div class="ui form">
                        <div class="field">
                            <label>Pilih Kategori</label>
                            <select id="categorySelect" class="ui dropdown">
                                <option value="kategori">Kategori</option>
                                <option value="sub_kategori">Sub Kategori</option>
                                <option value="kegiatan">Kegiatan</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Distribusi Sertifikat -->
        <div class="card">
            <div class="content">
                <div class="ui large blue center aligned top attached label"><i class="certificate icon"></i>Distribusi Sertifikat</div>
                <div class="description">
                    <div class="ui hidden divider"></div>
                    <div class="chart-container">
                        <canvas id="sertifikatChart"></canvas>
                    </div>
                    <div class="ui divider"></div>
                    <div id="legend-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
// Pie Chart untuk Poin Terkumpul
var ctxPoin = document.getElementById('poinChart').getContext('2d');
var poin = <?php echo $poin; ?>;
var maxPoin = <?php echo $maxPoin; ?>;
var sisaPoin = maxPoin - poin;

var dataChart = {
    labels: poin >= maxPoin ? ['Poin Terkumpul'] : ['Poin Terkumpul', 'Sisa Poin'],
    datasets: [{
        data: poin >= maxPoin ? [maxPoin] : [poin, sisaPoin],
        backgroundColor: poin >= maxPoin ? ['#3454d1'] : ['#3454d1', '#ACB9EC'],
        borderWidth: 0
    }]
};

var poinChart = new Chart(ctxPoin, {
    type: 'doughnut',
    data: dataChart,
    options: {
        responsive: true,
        maintainAspectRatio: false, // Nonaktifkan aspect ratio default
        plugins: {
            legend: { display: false },
        }
    }
});
// Chart Distribusi Poin
$(document).ready(function() {
    $('.ui.dropdown').dropdown();
});

let chartData = <?php echo json_encode($chart_data); ?>;
let ctx = document.getElementById('certChart').getContext('2d');
let colors = ['#3498db', '#2ecc71', '#e74c3c', '#9b59b6', '#f39c12'];
var certChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: chartData.kategori.labels,
        datasets: [{
            label: 'Jumlah Sertifikat',
            data: chartData.kategori.data,
            backgroundColor: colors,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // Nonaktifkan aspect ratio default
        plugins: {
            legend: { display: false }
        }
    }
});

$('#categorySelect').change(function() {
    let category = $(this).val();
    certChart.data.labels = chartData[category].labels;
    certChart.data.datasets[0].data = chartData[category].data;
    certChart.update();
});

// Chart Distribusi Sertifikat
var ctx2 = document.getElementById('sertifikatChart').getContext('2d');

var data = {
    labels: ['Valid', 'Tidak Valid', 'Menunggu Validasi'],
    datasets: [{
        data: [<?php echo $total_valid; ?>, <?php echo $total_tidak_valid; ?>, <?php echo $total_tunggu; ?>],
        backgroundColor: ['#21ba45', '#db2828', '#fbbd08']
    }]
};

var sertifikatChart = new Chart(ctx2, {
    type: 'pie',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false } // Sembunyikan legend default
        }
    }
});

// Custom Legend Manual (Horizontal)
var legendHtml = "<table class='ui table' >";

// Awal baris
legendHtml += "<tr>";

// Loop untuk setiap label dan warnanya
data.labels.forEach((label, index) => {
    legendHtml += `
        <td style="padding: 10px; text-align: center;">
            <span style="display:inline-block; width:12px; height:12px; background-color:${data.datasets[0].backgroundColor[index]}; margin-right:5px;"></span>
            ${label}
        </td>`;
});

// Tutup baris
legendHtml += "</tr>";

legendHtml += "</table>";

// Masukkan legend ke dalam elemen HTML
document.getElementById("legend-container").innerHTML = legendHtml;
</script>