<?php
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

// Ambil kategori unik
$queryKategori = "SELECT DISTINCT kategori FROM kategori";
$resultKategori = $koneksi->query($queryKategori);
$categories = [];

while ($row = $resultKategori->fetch_assoc()) {
    $categories[] = $row['kategori'];
}

// Ambil sub kategori berdasarkan kategori
$querySubKategori = "SELECT id_kategori, kategori, sub_kategori FROM kategori";
$resultSubKategori = $koneksi->query($querySubKategori);
$subCategories = [];

while ($row = $resultSubKategori->fetch_assoc()) {
    $subCategories[$row['kategori']][] = [
        'id_kategori' => $row['id_kategori'],
        'sub_kategori' => $row['sub_kategori']
    ];
}

// Ambil jenis kegiatan berdasarkan id_kategori
$queryKegiatan = "SELECT id_kategori, jenis_kegiatan FROM kegiatan";
$resultKegiatan = $koneksi->query($queryKegiatan);
$jenisKegiatan = [];

while ($row = $resultKegiatan->fetch_assoc()) {
    $jenisKegiatan[$row['id_kategori']][] = $row['jenis_kegiatan'];
}


if (isset($_POST['tambah_sertifikat'])) {
    $nis = $_COOKIE['nis']; // NIS dari cookie
    $kegiatan = $_POST['id_kegiatan'];

    // Cek apakah kegiatan valid
    $ubah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id_kegiatan FROM kegiatan WHERE jenis_kegiatan = '$kegiatan'"));
    
    if (!$ubah) {
        echo "<script>alert('Kegiatan tidak ditemukan. Pastikan memilih Kegiatan yang tersedia.'); window.history.back();</script>";
        exit();
    }

    $id_kegiatan = $ubah['id_kegiatan'];
    $tgl_upload = date("Y-m-d H:i:s"); // Tanggal saat ini
    $status = "Menunggu Validasi";

    // Proses upload file PDF
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/skkpd/sertifikat/";
    $file_ext = strtolower(pathinfo($_FILES["sertifikat"]["name"], PATHINFO_EXTENSION));

    // Pastikan hanya PDF yang bisa diupload
    if ($file_ext != "pdf") {
        echo "<script>alert('Hanya file PDF yang diperbolehkan.'); window.history.back();</script>";
        exit();
    }

    // Ganti nama file menjadi NIS_sertif.pdf
    $new_file_name = $nis . "_sertif.pdf";
    $target_file = $target_dir . $new_file_name;

    // Pastikan folder tujuan ada dan bisa ditulisi
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($_FILES["sertifikat"]["tmp_name"], $target_file)) {
        $stmt = $koneksi->prepare("INSERT INTO sertifikat (tgl_upload, sertifikat, status, nis, id_kegiatan) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $tgl_upload, $new_file_name, $status, $nis, $id_kegiatan);
        $stmt->execute();
        $stmt->close();
        
        echo "<script>alert('Sertifikat berhasil ditambahkan!'); window.location.href='index.php?page=sertifikat';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal mengunggah sertifikat. Coba lagi.'); window.history.back();</script>";
    }
}



?>
<style>
        .tab.segment {
        max-height: 500px; /* Sesuaikan tinggi sesuai kebutuhan */
        overflow-y: auto;
    }
</style>
<div class="main-content">
<div class="ui container">
    <div class="ui two column very relaxed grid">
		<div class="column">	
			<h1>Daftar Sertifikat</h1>		
		</div>
        <div class="column right aligned">
            <button class="ui labeled icon primary button" id="tambahModal">
                <i class="add icon"></i>Upload
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
        $query = "SELECT sertifikat.*, kegiatan.jenis_kegiatan, kategori.kategori, kategori.sub_kategori
                  FROM sertifikat 
                  JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan 
                  JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori
                  WHERE sertifikat.nis = '" . $_COOKIE['nis'] . "' AND sertifikat.status = 'Menunggu Validasi'";
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
                        <div class="left aligned"><i class="calendar icon"></i> <?= $sertifikat['tgl_upload'] ?></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div class="ui bottom attached tab segment" data-tab="valid">
    <div class="ui stackable three column grid">
        <?php
        $query = "SELECT sertifikat.*, kegiatan.jenis_kegiatan, kategori.kategori, kategori.sub_kategori 
                  FROM sertifikat 
                  JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan 
                  JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori
                  WHERE sertifikat.nis = '" . $_COOKIE['nis'] . "' AND sertifikat.status = 'Valid'";
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
                        <span class="right aligned"><i class="calendar icon"></i> <?= $sertifikat['tgl_upload'] ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


<div class="ui bottom attached tab segment" data-tab="tidak-valid">
    <div class="ui stackable three column grid">
        <?php
        $query = "SELECT sertifikat.*, kegiatan.jenis_kegiatan, kategori.kategori, kategori.sub_kategori 
                  FROM sertifikat 
                  JOIN kegiatan ON sertifikat.id_kegiatan = kegiatan.id_kegiatan 
                  JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori
                  WHERE sertifikat.nis = '" . $_COOKIE['nis'] . "' AND sertifikat.status = 'Tidak Valid'";
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
                        <span class="right aligned"><i class="calendar icon"></i> <?= $sertifikat['tgl_upload'] ?></span>
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

<!-- Modal Tambah Sertifikat -->
<div class="ui tiny modal" id="modalTambahSertifikat">
    <div class="header">Tambah Sertifikat</div>
    <div class="content">
        <form class="ui form" method="POST" enctype="multipart/form-data">
            <div class="field">
                <label>Pilih Kategori</label>
                <div class="ui selection dropdown" id="categoryDropdown">
                    <input type="hidden" name="category">
                    <i class="dropdown icon"></i>
                    <div class="default text">Pilih Kategori</div>
                    <div class="menu">
                        <?php foreach ($categories as $category): ?>
                            <div class="item" data-value="<?= $category ?>"><?= $category ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="field">
                <label>Pilih Sub Kategori</label>
                <div class="ui selection dropdown disabled" id="subCategoryDropdown">
                    <input type="hidden" name="id_kategori">
                    <i class="dropdown icon"></i>
                    <div class="default text">Pilih Sub Kategori</div>
                    <div class="menu"></div>
                </div>
            </div>

            <div class="field">
                <label>Pilih Jenis Kegiatan</label>
                <div class="ui selection dropdown disabled" id="jenisKegiatanDropdown">
                    <input type="hidden" name="id_kegiatan">
                    <i class="dropdown icon"></i>
                    <div class="default text">Pilih Jenis Kegiatan</div>
                    <div class="menu"></div>
                </div>
            </div>

            <div class="field">
                <label>Upload Sertifikat (PDF)</label>
                <input type="file" name="sertifikat" accept="application/pdf" required>
            </div>

            <button class="ui fluid primary button" type="submit" name="tambah_sertifikat">Tambah</button>
        </form>
    </div>
</div>
</div>

<script>
$(document).ready(function () {
        $('.ui.dropdown').dropdown();

        // Data dari PHP ke JavaScript
        const subCategoryOptions = <?php echo json_encode($subCategories); ?>;
        const jenisKegiatanOptions = <?php echo json_encode($jenisKegiatan); ?>;

        // Event saat kategori berubah
        $('#categoryDropdown').dropdown({
            onChange: function (value) {
                const subCategoryDropdown = $('#subCategoryDropdown');
                const menu = subCategoryDropdown.find('.menu');

                // Kosongkan dropdown sub-kategori
                menu.empty();

                if (value && subCategoryOptions[value]) {
                    subCategoryOptions[value].forEach(item => {
                        menu.append(`<div class="item" data-value="${item.id_kategori}" data-sub="${item.sub_kategori}">${item.sub_kategori}</div>`);
                    });

                    subCategoryDropdown.removeClass('disabled').dropdown('clear').dropdown('refresh');
                } else {
                    subCategoryDropdown.addClass('disabled').dropdown('clear');
                }

                // Reset dropdown jenis kegiatan
                $('#jenisKegiatanDropdown').addClass('disabled').dropdown('clear').find('.menu').empty();
            }
        });

        // Event saat sub kategori berubah
        $('#subCategoryDropdown').dropdown({
            onChange: function (value, text, $selectedItem) {
                const jenisKegiatanDropdown = $('#jenisKegiatanDropdown');
                const menu = jenisKegiatanDropdown.find('.menu');
                const idKategori = value; // id_kategori diambil dari data-value

                // Kosongkan dropdown jenis kegiatan
                menu.empty();

                if (idKategori && jenisKegiatanOptions[idKategori]) {
                    jenisKegiatanOptions[idKategori].forEach(item => {
                        menu.append(`<div class="item" data-value="${item}">${item}</div>`);
                    });

                    jenisKegiatanDropdown.removeClass('disabled').dropdown('clear').dropdown('refresh');
                } else {
                    jenisKegiatanDropdown.addClass('disabled').dropdown('clear');
                }
            }
        });

        // Tampilkan modal saat tombol tambah ditekan
        $('#tambahModal').on('click', function () {
            $('#modalTambahSertifikat').modal('show');
        });
    });
</script>