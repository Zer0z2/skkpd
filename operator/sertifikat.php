    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

    // Ambil daftar kategori unik
    $kategori_hasil = mysqli_query($koneksi, "SELECT DISTINCT kategori.id_kategori, kategori.sub_kategori FROM kategori INNER JOIN kegiatan ON kategori.id_kategori = kegiatan.id_kategori");

    // Ambil semua data sertifikat
    $sertifikat_hasil = mysqli_query($koneksi, "
        SELECT sertifikat.*, kategori.sub_kategori, kegiatan.id_kategori 
        FROM sertifikat 
        INNER JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan
        INNER JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori
    ");
    ?>

    <div class="main-content">
    <div class="ui container">
        <div class="ui two column very relaxed grid">
            <div class="column">	
                <h1>Daftar Sertifikat</h1>		
            </div>
            <div class="column right aligned">
                <button class="ui labeled icon grey button" id="tambahModal">
                    <i class="file export icon"></i>Export
                </button>
            </div>
        </div>
        <div class="ui divider"></div>

            <div class="ui top attached tabular menu">
                <a class="item active" data-tab="menunggu">Menunggu Validasi</a>
                <a class="item" data-tab="valid">Valid</a>
                <a class="item" data-tab="tidak-valid">Tidak Valid</a>
            </div>
            <div class="ui bottom attached tab segment" data-tab="menunggu">
        <div class="ui stackable three column grid">
            <?php
            $query = "SELECT sertifikat.*, kegiatan.jenis_kegiatan, kategori.kategori, kategori.sub_kategori, siswa.nis, siswa.nama_siswa
                    FROM sertifikat 
                    JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan 
                    JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori
                    JOIN siswa ON sertifikat.nis = siswa.nis
                    WHERE sertifikat.status = 'Menunggu Validasi'";
            $hasil = mysqli_query($koneksi, $query);
            
            while ($sertifikat = mysqli_fetch_array($hasil)) {
                $ribbonColor = ($sertifikat['kategori'] == 'Wajib') ? 'green' : 'yellow';
                ?>
                <div class="column">
                    <div class="ui centered card">
                        <div class="content">
                            <div class="ui <?= $ribbonColor ?> ribbon label"><?= $sertifikat['kategori'] ?></div>
                            <div class="header" style="margin-top: 10px;"><?= $sertifikat['jenis_kegiatan'] ?></div>
                            <div class="meta"><?= $sertifikat['sub_kategori'] ?></div>
                        </div>
                        <div class="content">
                                <i class="file pdf outline icon"></i>
                                <a href="index.php?page=cek_sertifikat&id=<?=$sertifikat['id_sertifikat']?>&file=<?=$sertifikat['sertifikat']?>">Lihat Sertifikat</a>
                        </div>
                        <div class="extra content">
                        <div class="right floated">
                            <i class="calendar icon"></i> <?= $sertifikat['tgl_upload'] ?>
                        </div>

                            <div><?= $sertifikat['nis'] ?> | <?= $sertifikat['nama_siswa'] ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="ui bottom attached tab segment" data-tab="valid">
        <div class="ui stackable three column grid">
            <?php
            $query = "SELECT sertifikat.*, kegiatan.jenis_kegiatan, kategori.kategori, kategori.sub_kategori, siswa.nis, siswa.nama_siswa  
                    FROM sertifikat 
                    JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan 
                    JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori
                    JOIN siswa ON sertifikat.nis = siswa.nis
                    WHERE sertifikat.status = 'Valid'";
            $hasil = mysqli_query($koneksi, $query);
            
            while ($sertifikat = mysqli_fetch_array($hasil)) {
                $ribbonColor = ($sertifikat['kategori'] == 'Wajib') ? 'green' : 'yellow';
                ?>
                <div class="column">
                    <div class="ui centered card">
                        <div class="content">
                            <div class="ui <?= $ribbonColor ?> ribbon label"><?= $sertifikat['kategori'] ?></div>
                            <div class="header" style="margin-top: 10px;"><?= $sertifikat['jenis_kegiatan'] ?></div>
                            <div class="meta"><?= $sertifikat['sub_kategori'] ?></div>
                        </div>
                        <div class="content">
                            <div class="ui list">
                                <div class="item">
                                    <i class="file pdf outline icon"></i>
                                    <div class="content">
                                        <a href="index.php?page=cek_sertifikat&id=<?=$sertifikat['id_sertifikat']?>&file=<?=$sertifikat['sertifikat']?>">Lihat Sertifikat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="extra content">
                        <div class="right floated">
                            <i class="calendar icon"></i> <?= $sertifikat['tgl_upload'] ?>
                        </div>

                            <div><?= $sertifikat['nis'] ?> | <?= $sertifikat['nama_siswa'] ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="ui bottom attached tab segment" data-tab="tidak-valid">
        <div class="ui stackable three column grid">
            <?php
            $query = "SELECT sertifikat.*, kegiatan.jenis_kegiatan, kategori.kategori, kategori.sub_kategori, siswa.nis, siswa.nama_siswa 
                    FROM sertifikat 
                    JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan 
                    JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori
                    JOIN siswa ON sertifikat.nis = siswa.nis
                    WHERE sertifikat.status = 'Tidak Valid'";
            $hasil = mysqli_query($koneksi, $query);
            
            while ($sertifikat = mysqli_fetch_array($hasil)) {
                $ribbonColor = ($sertifikat['kategori'] == 'Wajib') ? 'green' : 'yellow';
                ?>
                <div class="column">
                    <div class="ui centered card">
                        <div class="content">
                            <div class="ui <?= $ribbonColor ?> ribbon label"><?= $sertifikat['kategori'] ?></div>
                            <div class="header" style="margin-top: 10px;"><?= $sertifikat['jenis_kegiatan'] ?></div>
                            <div class="meta"><?= $sertifikat['sub_kategori'] ?></div>
                        </div>
                        <div class="content">
                            <div class="ui list">
                                <div class="item">
                                    <i class="file pdf outline icon"></i>
                                    <div class="content">
                                        <a href="index.php?page=cek_sertifikat&id=<?=$sertifikat['id_sertifikat']?>&file=<?=$sertifikat['sertifikat']?>">Lihat Sertifikat</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="extra content">
                        <div class="right floated">
                            <i class="calendar icon"></i> <?= $sertifikat['tgl_upload'] ?>
                        </div>

                            <div><?= $sertifikat['nis'] ?> | <?= $sertifikat['nama_siswa'] ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('.menu .item').tab();
    });
    </script>
    </div>

    <!-- Modal Export -->
    <div class="ui tiny modal" id="modalExportLaporan">
        <div class="header">Export Laporan</div>
        <div class="content">
            <form class="ui form">
                <div class="field">
                    <label>Filter Status Sertifikat</label>
                    <div class="ui selection dropdown" id="statusSertifikatDropdown">
                        <input type="hidden" name="status_sertifikat">
                        <i class="dropdown icon"></i>
                        <div class="default text">Pilih Status</div>
                        <div class="menu">
                            <div class="item" data-value="All">Semua Status</div>
                            <div class="item" data-value="Valid">Valid</div>
                            <div class="item" data-value="Tidak Valid">Tidak Valid</div>
                            <div class="item" data-value="Menunggu Validasi">Menunggu Validasi</div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Filter Angkatan</label>
                    <div class="ui selection dropdown" id="angkatanDropdown">
                        <input type="hidden" name="angkatan">
                        <i class="dropdown icon"></i>
                        <div class="default text">Pilih Angkatan</div>
                        <div class="menu">
                            <div class="item" data-value="All">Semua Angkatan</div>
                            <?php
                            // Query to get unique angkatan values
                            $angkatanQuery = mysqli_query($koneksi, "SELECT DISTINCT angkatan FROM siswa ORDER BY angkatan ASC");
                            while ($row = mysqli_fetch_assoc($angkatanQuery)) {
                                $angkatan = $row['angkatan'];
                                echo "<div class='item' data-value='{$angkatan}'>{$angkatan}</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <button class="ui fluid primary button" type="button" id="exportButton">Export</button>
            </form>
        </div>
    </div>


    </div>
    <script>
    $(document).ready(function() {
    $('#statusSertifikatDropdown, #angkatanDropdown').dropdown(); // Hapus dropdown format export

    $('#exportButton').click(function() {
        var selectedStatus = $('input[name="status_sertifikat"]').val();
        var selectedAngkatan = $('input[name="angkatan"]').val();

        // Langsung ekspor ke PDF tanpa pemilihan format
        var exportUrl = 'operator/sertifikat/export_pdf.php';

        // Buat form sementara untuk mengirim data
        var form = $('<form action="' + exportUrl + '" method="POST" target="_blank"></form>').appendTo('body');

        form.append('<input type="hidden" name="status_sertifikat" value="' + selectedStatus + '">');
        form.append('<input type="hidden" name="angkatan" value="' + selectedAngkatan + '">');

        form.submit();
        form.remove();
    });
        // Tampilkan modal saat tombol tambah ditekan
        $('#tambahModal').on('click', function () {
    $('#modalExportLaporan').modal('show');
    });
});


    </script>
