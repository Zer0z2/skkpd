<?php
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

// Generate ID Jurusan otomatis
function generateIdJurusan($koneksi) {
    $query = "SELECT MAX(id_jurusan) as max_id FROM jurusan";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['max_id']) {
        $lastId = (int) substr($row['max_id'], 1); // Ambil angka setelah 'J'
        $newId = 'J' . ($lastId + 1);
    } else {
        $newId = 'J1';
    }
    return $newId;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_jurusan'])) {
    $id = generateIdJurusan($koneksi);
    $jurusan = $_POST['jurusan'];

    $stmt = $koneksi->prepare("INSERT INTO jurusan (id_jurusan, jurusan) VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $jurusan);
    $stmt->execute();
    $stmt->close();

    // Redirect dengan JavaScript
    echo "<script>window.location.href='index.php?page=jurusan';</script>";
    exit();
}

// Handle ubah jurusan (tidak bisa ubah ID)
if (isset($_POST['ubah_jurusan'])) {
    $id = $_POST['id_jurusan'];
    $jurusan = $_POST['jurusan'];
    
    $stmt = $koneksi->prepare("UPDATE jurusan SET jurusan=? WHERE id_jurusan=?");
    $stmt->bind_param("ss", $jurusan, $id);
    $stmt->execute();
    $stmt->close();
	echo "<script>window.location.href='index.php?page=jurusan';</script>";
	exit();
}

// Handle hapus jurusan (cek apakah digunakan di tabel lain)
function isJurusanUsed($koneksi, $id) {
    $cekQuery = "SELECT COUNT(*) as count FROM siswa WHERE id_jurusan = ?";
    $stmt = $koneksi->prepare($cekQuery);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] > 0;
}

if (isset($_POST['hapus_jurusan'])) {
    $id = $_POST['hapus_jurusan'];
    
    if (isJurusanUsed($koneksi, $id)) {
        echo "<script>alert('Jurusan tidak dapat dihapus karena masih digunakan.'); window.location.href='index.php?page=jurusan';</script>";
        exit();
    }
    
    $stmt = $koneksi->prepare("DELETE FROM jurusan WHERE id_jurusan=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>window.location.href='index.php?page=jurusan';</script>";
    exit();
}
?>

<div class="main-content">
<div class="ui container">
    <div class="ui two column very relaxed grid">
        <div class="column">    
            <h1>Daftar Jurusan</h1>       
        </div>
        <div class="column right aligned">
            <button class="ui labeled icon primary button" id="tambahModal">
                <i class="add icon"></i>Tambah
            </button>
        </div>
    </div>
    <div class="ui divider"></div>
    <table class="ui long fixed blue scrolling table">
        <thead>
            <tr>
                <th class="one wide">No</th>
                <th>Jurusan</th>
                <th class="two wide">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $hasil = mysqli_query($koneksi, "SELECT * FROM jurusan");
            while ($jurusan = mysqli_fetch_array($hasil)) {
                $isUsed = isJurusanUsed($koneksi, $jurusan['id_jurusan']);
                echo "<tr>
                        <td class='one wide'>{$no}</td>
                        <td>{$jurusan['jurusan']}</td>
                        <td class='two wide'>
                            <button class='ui icon yellow button editBtn' 
                              data-id_jurusan='{$jurusan['id_jurusan']}' 
                              data-jurusan='{$jurusan['jurusan']}'>
                            <i class='edit icon'></i></button>
                            <button class='ui icon red button deleteBtn " . ($isUsed ? "disabled" : "") . "' 
                              data-id_jurusan='{$jurusan['id_jurusan']}'>
                            <i class='trash icon'></i></button>
                        </td>
                    </tr>";
                    $no++;
            }

            ?>
        </tbody>
    </table>
</div>
<?php
include "modals/jurusan/tambah_jurusan.php";
include "modals/jurusan/edit_jurusan.php";
?>
</div>
<script>
$(document).ready(function () {
    // Tampilkan modal tambah jurusan
    $('#tambahModal').click(function() {
        $('#modalTambah').modal('show');
    });

    // Tampilkan modal edit jurusan
    $('.editBtn').click(function() {
        $('#editJurusan').val($(this).data('jurusan'));
        $('#editIdJurusan').val($(this).data('id_jurusan'));
        $('#modalEdit').modal('show');
    });

    // Hapus jurusan
    $('.deleteBtn').click(function(event) {
        var id_jurusan = $(this).data('id_jurusan');
        if (confirm("Apakah Anda yakin ingin menghapus jurusan ini?")) {
            $.post("index.php?page=jurusan", { hapus_jurusan: id_jurusan }, function(response) {
                location.reload();
            });
        }
    });
});
</script>

