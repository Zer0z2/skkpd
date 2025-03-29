<?php
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

// Generate ID Jurusan otomatis
function generateIdKategori($koneksi) {
    $query = "SELECT MAX(id_kategori) as max_id FROM kategori";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['max_id']) {
        $lastId = (int) substr($row['max_id'], 3); // Ambil angka setelah 'KTG'
        $newId = 'KTG' . ($lastId + 1);
    } else {
        $newId = 'KTG1';
    }
    return $newId;
}

// Handle tambah kategori
if (isset($_POST['tambah_kategori'])) {
    $id = generateIdKategori($koneksi);
    $kategori = trim($_POST['kategori']);
    $sub_kategori = trim($_POST['sub_kategori']);

    // Cek apakah kategori kosong
    if (empty($kategori)) {
        echo "<script>alert('Kategori tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    $stmt = $koneksi->prepare("INSERT INTO kategori (id_kategori, kategori, sub_kategori) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $id, $kategori, $sub_kategori);
    
    if ($stmt->execute()) {
        echo "<script>window.location.href='index.php?page=kategori';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan kategori!'); window.history.back();</script>";
    }

    $stmt->close();
    exit();
}


// Handle ubah kategori
if (isset($_POST['ubah_kategori'])) {
    $id = $_POST['id_kategori'];
    $kategori = $_POST['kategori'];
    $sub_kategori = $_POST['sub_kategori'];

    $stmt = $koneksi->prepare("UPDATE kategori SET kategori=?, sub_kategori=? WHERE id_kategori=?");
    $stmt->bind_param("sss", $kategori, $sub_kategori, $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>window.location.href='index.php?page=kategori';</script>";
    exit();
}


// Handle hapus kategori
if (isset($_POST['hapus_id_kategori'])) {
    $id = $_POST['hapus_id_kategori'];

    $stmt = $koneksi->prepare("DELETE FROM kategori WHERE id_kategori=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>window.location.href='index.php?page=kategori';</script>";
    exit();
}


?>
<style>
    #kategori-table {
    max-height: 500px; /* Sesuaikan tinggi */
    overflow-y: auto; /* Mengaktifkan scroll jika tabel terlalu panjang */
    display: block; /* Agar scroll bisa diterapkan */
}
#kategori-table thead,
#kategori-table tbody {
    display: block;
    width: 100%;
}
#kategori-table tbody {
    max-height: 450px; /* Atur tinggi bagian body */
    overflow-y: auto;
}

</style>
<div class="main-content">
<div class="ui container">
    <div class="ui two column very relaxed grid">
		<div class="column">	
			<h1>Daftar Kategori</h1>		
		</div>
        <div class="column right aligned">
            <button class="ui labeled icon primary button" id="tambahModal">
                <i class="add icon"></i>Tambah
            </button>
        </div>
	</div>
    <div class="ui divider"></div>
    <table class="ui fixed sortable blue scrolling table" id="kategori-table">
        <thead>
            <tr>
                <th class="one wide">No</th>
                <th class="two wide">Kategori</th>
                <th>Sub Kategori</th>
                <th class="two wide">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=1;
            $hasil = mysqli_query($koneksi, "SELECT kategori.* FROM kategori");
            while ($kategori = mysqli_fetch_array($hasil)) {
                $warna_kategori = ($kategori['kategori'] === 'Wajib') ? 'green' : 'yellow';
                echo "<tr>
                        <td class='one wide'>{$no}</td>
                        <td class='two wide'><div class='ui {$warna_kategori} label'>{$kategori['kategori']}</div></td>
                        <td>{$kategori['sub_kategori']}</td>
                        <td class='two wide'>
                            <button class='ui icon yellow button editBtn'
                              data-id_kategori='{$kategori['id_kategori']}'
                              data-kategori='{$kategori['kategori']}'
                              data-sub_kategori='{$kategori['sub_kategori']}'
                            ><i class='edit icon'></i></button>
                            <button class='ui icon red button deleteBtn' data-id_kategori='{$kategori['id_kategori']}'><i class='trash icon'></i></button>

                        </td>
                    </tr>";
                    $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include "modals/kategori/tambah_kategori.php";
include "modals/kategori/edit_kategori.php";
?>

</div>
<script>
$(document).ready(function() {
    $('#tambahModal').click(function() {
        $('#modalTambah').modal('show');
    });

    $('.editBtn').click(function() {
        $('#editId').val($(this).data('id_kategori'));
        $('#editKategori').val($(this).data('kategori')).change();
        $('#editSub').val($(this).data('sub_kategori'));
        $('#modalEdit').modal('show');
    });

    $('.deleteBtn').click(function() {
        var id_kategori = $(this).data('id_kategori');
        if (confirm("Hapus kategori ini?")) {
            $.post("index.php?page=kategori", { hapus_id_kategori: id_kategori }, function(response) {
                location.reload(); // Refresh halaman setelah hapus
            });
        }
    });
});
</script>

