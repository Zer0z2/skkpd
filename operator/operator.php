<?php
include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";

// Handle tambah pengguna
if (isset($_POST['tambah_pengguna'])) {
    $username = $_POST['username'];
    $nama = $_POST['nama_lengkap'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Gunakan prepared statements untuk menghindari SQL Injection
    $stmt1 = $koneksi->prepare("INSERT INTO pegawai (nama_lengkap, username) VALUES (?, ?)");
    $stmt2 = $koneksi->prepare("INSERT INTO pengguna (username, password) VALUES (?, ?)");

    if ($stmt1 && $stmt2) {
        // Bind parameter dan eksekusi query pertama
        $stmt1->bind_param("ss", $nama, $username);
        $stmt1->execute();
        $stmt1->close();

        // Bind parameter dan eksekusi query kedua
        $stmt2->bind_param("ss", $username, $pass);
        $stmt2->execute();
        $stmt2->close();

        echo "<script>
                alert('Pengguna berhasil ditambahkan!');
                window.location.href = 'index.php?page=operator';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat menambahkan pengguna.');
                window.location.href = 'index.php?page=operator';
              </script>";
    }
}

// Handle ubah pengguna
if (isset($_POST['ubah_pengguna'])) {
    $id = $_POST['id_pengguna'];
    $username_baru = $_POST['username'];
    $nama = $_POST['nama_lengkap'];
    $pass = $_POST['password'];

    $koneksi->begin_transaction();

    try {
        // Ambil username lama berdasarkan id_pengguna
        $stmt_check = $koneksi->prepare("SELECT username FROM pengguna WHERE id_pengguna=?");
        $stmt_check->bind_param("s", $id);
        $stmt_check->execute();
        $result = $stmt_check->get_result();
        $row = $result->fetch_assoc();
        $username_lama = $row['username'];
        $stmt_check->close();

        // Update tabel pegawai dengan username baru
        $stmt1 = $koneksi->prepare("UPDATE pegawai SET nama_lengkap=?, username=? WHERE username=?");
        $stmt1->bind_param("sss", $nama, $username_baru, $username_lama);
        $stmt1->execute();
        $stmt1->close();

        // Cek apakah password diubah atau tidak
        if (!empty($pass)) {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $stmt2 = $koneksi->prepare("UPDATE pengguna SET username=?, password=? WHERE id_pengguna=?");
            $stmt2->bind_param("sss", $username_baru, $hashed_pass, $id);
        } else {
            $stmt2 = $koneksi->prepare("UPDATE pengguna SET username=? WHERE id_pengguna=?");
            $stmt2->bind_param("ss", $username_baru, $id);
        }
        $stmt2->execute();
        $stmt2->close();

        $koneksi->commit();

        echo "<script>
                alert('Data pengguna berhasil diperbarui!');
                window.location.href = 'index.php?page=operator';
              </script>";
        exit();
    } catch (Exception $e) {
        $koneksi->rollback();
        echo "<script>
                alert('Terjadi kesalahan saat memperbarui data pengguna.');
                window.location.href = 'index.php?page=operator';
              </script>";
    }
}


if (isset($_POST['hapus_username'])) {
    $username = $_POST['hapus_username'];

    $koneksi->begin_transaction();

    try {
        // Hapus dari tabel pengguna
        $stmt1 = $koneksi->prepare("DELETE FROM pengguna WHERE username=?");
        $stmt1->bind_param("s", $username);
        $stmt1->execute();
        $stmt1->close();

        // Hapus dari tabel pegawai
        $stmt2 = $koneksi->prepare("DELETE FROM pegawai WHERE username=?");
        $stmt2->bind_param("s", $username);
        $stmt2->execute();
        $stmt2->close();

        $koneksi->commit();

        echo "<script>
                alert('Pengguna berhasil dihapus.');
                window.location.href = 'index.php?page=operator';
              </script>";
        exit();
    } catch (Exception $e) {
        $koneksi->rollback();
        echo "<script>
                alert('Terjadi kesalahan saat menghapus pengguna.');
                window.location.href = 'index.php?page=operator';
              </script>";
    }
}

?>


<div class="main-content">
<div class="ui container">
    <div class="ui two column very relaxed grid">
		<div class="column">	
			<h1>Daftar Operator</h1>		
		</div>
        <div class="column right aligned">
            <button class="ui labeled icon primary button" id="tambahModal">
                <i class="add icon"></i>Tambah
            </button>
        </div>
	</div>
    <div class="ui divider"></div>
    <table class="ui fixed blue scrolling table">
        <thead>
            <tr>
                <th class="one wide">No</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th class="two wide">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no=1;
            $hasil = mysqli_query($koneksi, "SELECT pengguna.*,pegawai.nama_lengkap FROM pengguna inner join pegawai where pengguna.username = pegawai.username");
            while ($pengguna = mysqli_fetch_array($hasil)) {
                echo "<tr>
                        <td class='one wide'>{$no}</td>
                        <td>{$pengguna['username']}</td>
                        <td>{$pengguna['nama_lengkap']}</td>
                        <td class='two wide'>
                            <button class='ui icon yellow button editBtn'
                              data-id_pengguna='{$pengguna['id_pengguna']}'
                              data-username='{$pengguna['username']}'
                              data-nama_lengkap='{$pengguna['nama_lengkap']}'
                            ><i class='edit icon'></i></button>
                            <button class='ui icon red button deleteBtn' data-username='{$pengguna['username']}'><i class='trash icon'></i></button>

                        </td>
                    </tr>";
                    $no++;
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include "modals/operator/tambah_operator.php";
include "modals/operator/edit_operator.php";
?>

</div>
<script>
$(document).ready(function() {
    $('#tambahModal').click(function() {
        $('#modalTambah').modal('show');
    });

    $('.editBtn').click(function() {
        $('#editId').val($(this).data('id_pengguna'));
        $('#editNama').val($(this).data('nama_lengkap'));
        $('#editUsername').val($(this).data('username'));
        $('#editPassword').val($(this).data('password'));
		$('#modalEdit').modal('show');
    });

    $('.deleteBtn').click(function () {
        var username = $(this).data('username');
        var currentUsername = getCookie("username"); // Ambil username dari cookie

        if (username === currentUsername) {
            alert("Tidak bisa menghapus diri sendiri.");
            return;
        }

        if (confirm("Hapus pengguna ini?")) {
            $.post("index.php?page=operator", { hapus_username: username }, function (response) {
                alert("Operator berhasil dihapus"); 
                location.reload();
            });
        }
    });

    // Fungsi untuk mengambil nilai cookie berdasarkan nama
    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? match[2] : "";
    }
});
</script>