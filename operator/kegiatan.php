    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

    // Handle tambah kegiatan
    if (isset($_POST['tambah_kegiatan'])) {
        $kegiatan = $_POST['jenis_kegiatan'];
        $kredit = $_POST['angka_kredit'];
        $id_kategori = $_POST['id_kategori'];

        $stmt = $koneksi->prepare("INSERT INTO kegiatan (jenis_kegiatan, angka_kredit, id_kategori) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $kegiatan, $kredit, $id_kategori);    
        $stmt->execute();
        $stmt->close();
        echo "<script>window.location.href='index.php?page=kegiatan';</script>";
    }

    // Handle ubah kegiatan
    if (isset($_POST['ubah_kegiatan'])) {
        $id = $_POST['id_kegiatan'];
        $kegiatan = $_POST['jenis_kegiatan'];
        $kredit = $_POST['angka_kredit'];
        $id_kategori = $_POST['id_kategori'];

        $stmt = $koneksi->prepare("UPDATE kegiatan SET jenis_kegiatan=?, angka_kredit=?, id_kategori=? WHERE id_kegiatan=?");
        $stmt->bind_param("ssss", $kegiatan,$kredit, $id_kategori, $id);
        $stmt->execute();
        $stmt->close();
        echo "<script>window.location.href='index.php?page=kegiatan';</script>";
    }


    // Handle hapus kegiatan
    if (isset($_POST['hapus_id_kegiatan'])) {
        $id = $_POST['hapus_id_kegiatan'];

        $stmt = $koneksi->prepare("DELETE FROM kegiatan WHERE id_kegiatan=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();

        echo "<script>window.location.href='index.php?page=kegiatan';</script>";
        exit();
    }

    ?>
<style>
    #kegiatanTable {
    max-height: 500px; /* Sesuaikan tinggi */
    overflow-y: auto; /* Mengaktifkan scroll jika tabel terlalu panjang */
    display: block; /* Agar scroll bisa diterapkan */
}
#kegiatanTable thead,
#kegiatanTable tbody {
    display: block;
    width: 100%;
}
#kegiatanTable tbody {
    max-height: 450px; /* Atur tinggi bagian body */
    overflow-y: auto;
}

</style>
    <div class="main-content">
    <div class="ui container">
        <div class="ui two column very relaxed grid">
            <div class="column">	
                <h1>Daftar Kegiatan</h1>		
            </div>
            <div class="column right aligned">
                <button class="ui labeled icon primary button" id="tambahModal">
                    <i class="add icon"></i>Tambah
                </button>
            </div>
        </div>
        <div class="ui divider"></div>
        <div class="ui one column very relaxed grid">
        <div class="column">
			<!-- Filter dan Pencarian -->
		<div class="ui form">
        <div class="ui equal width fields">
            <div class="field">
                <label>Filter Kategori</label>
                <select id="filter-kategori" class="ui fluid dropdown">
                    <option value="all">Semua Kategori</option>
                    <option value="Wajib">Wajib</option>
                    <option value="Optional">Optional</option>
                </select>
            </div>
            <div class="field">
                <label>Filter Sub Kategori</label>
                <select id="filter-sub_kategori" class="ui fluid dropdown">
                    <option value='all'>Semua Sub Kategori</option>;
                    <?php
                    $kategori = mysqli_query($koneksi, "SELECT DISTINCT sub_kategori FROM kategori");
                    while ($row = mysqli_fetch_array($kategori)) {
                        echo "<option value='{$row['sub_kategori']}'>{$row['sub_kategori']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="field">
                <label>Cari Kegiatan</label>
                <input type="text" id="search-kegiatan" class="ui fluid input" placeholder="Cari berdasarkan kegiatan..." autocomplete="off">
            </div>
        </div>
        </div>
        </div>

    </div>
        <table class="ui fixed sortable blue scrolling table" id="kegiatanTable">
            <thead>
                <tr>
                    <th class="one wide">No</th>
                    <th>Jenis Kegiatan</th>
                    <th>Angka Kredit</th>
                    <th>Kategori</th>
                    <th>Sub Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            
            <tbody>
            <?php
            $no = 1;
            $hasil = mysqli_query($koneksi, "SELECT kegiatan.*, kategori.* FROM kegiatan INNER JOIN kategori ON kegiatan.id_kategori = kategori.id_kategori");

            while ($kegiatan = mysqli_fetch_array($hasil)) {
                // Tentukan warna label berdasarkan kategori
                $warna_kategori = ($kegiatan['kategori'] === 'Wajib') ? 'green' : 'yellow';
            
                echo "<tr>
                        <td class='one wide'>{$no}</td>
                        <td class=\"kegiatan\">{$kegiatan['jenis_kegiatan']}</td>
                        <td><div class='ui red circular label'>{$kegiatan['angka_kredit']}</div></td>
                        <td class=\"kategori\"><div class='ui {$warna_kategori} label'>{$kegiatan['kategori']}</div></td>                        
                        <td class=\"sub_kategori\">{$kegiatan['sub_kategori']}</td>
                        <td>
                            <button class='ui icon yellow button editBtn'
                            data-id_kegiatan='{$kegiatan['id_kegiatan']}'
                            data-jenis_kegiatan='{$kegiatan['jenis_kegiatan']}'
                            data-angka_kredit='{$kegiatan['angka_kredit']}'
                            data-id_kategori='{$kegiatan['id_kategori']}'
                            ><i class='edit icon'></i></button>
                            <button class='ui icon red button deleteBtn' data-id_kegiatan='{$kegiatan['id_kegiatan']}'><i class='trash icon'></i></button>
                        </td>
                    </tr>";
                $no++;
            }
            ?>
            </tbody>
        </table>
    </div>

    <?php
    include "modals/kegiatan/tambah_kegiatan.php";
    include "modals/kegiatan/edit_kegiatan.php";
    ?>

    </div>
    <script>
    $(document).ready(function() {
        $('#filter-kategori, #filter-sub_kategori, #search-kegiatan').on('input change', function() {
            var kategori = $('#filter-kategori').val().toLowerCase();
            var sub_kategori = $('#filter-sub_kategori').val();
            var kegiatan = $('#search-kegiatan').val().toLowerCase();

            $('#kegiatanTable tbody tr').each(function() {
                var rowKategori = $(this).find('.kategori').text().trim().toLowerCase();
                var rowSubKategori = $(this).find('.sub_kategori').text();
                var rowKegiatan = $(this).find('.kegiatan').text().trim().toLowerCase();

                var matchKategori = (kategori === "all" || rowKategori === kategori);
                var matchSubKategori = (sub_kategori === "all" || rowSubKategori === sub_kategori);
                var matchKegiatan = (kegiatan === "" || rowKegiatan.includes(kegiatan));

                $(this).toggle(matchKategori && matchSubKategori && matchKegiatan);
            });
        });

        $('#tambahModal').click(function() {
            $('#modalTambah').modal('show');
        });

        $('.editBtn').click(function() {
        $('#editId').val($(this).data('id_kegiatan'));
        $('#editKegiatan').val($(this).data('jenis_kegiatan'));
        $('#editKredit').val($(this).data('angka_kredit'));

        let kategoriValue = $(this).data('id_kategori');

        // Set the dropdown value in Fomantic UI
        $('#editKategoriDropdown').dropdown('set selected', kategoriValue);

        // Show the modal
        $('#modalEdit').modal('show');
        });

        // Initialize dropdown when the page loads
        $('#editKategoriDropdown').dropdown();

        $('.deleteBtn').click(function() {
            var id_kegiatan = $(this).data('id_kegiatan');
            if (confirm("Hapus kegiatan ini?")) {
                $.post("index.php?page=kegiatan", { hapus_id_kegiatan: id_kegiatan }, function(response) {
                    location.reload(); // Refresh halaman setelah hapus
                });
            }
        });

        $('.ui.dropdown').dropdown();
    });
    </script>