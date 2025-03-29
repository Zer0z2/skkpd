<?php
include "koneksi/koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_COOKIE['username'];
    $password_baru = mysqli_real_escape_string($koneksi, $_POST['password_baru']);
    $konfirmasi_password = mysqli_real_escape_string($koneksi, $_POST['konfirmasi_password']);

    // Validasi input
    if (empty($password_baru) || empty($konfirmasi_password)) {
        echo "<script>alert('Password tidak boleh kosong!');</script>";
    } elseif ($password_baru !== $konfirmasi_password) {
        echo "<script>alert('Konfirmasi password tidak cocok!');</script>";
    } else {
        // Hash password
        $password_hash = password_hash($password_baru, PASSWORD_BCRYPT);
        
        // Update password di database
        $query = "UPDATE pengguna SET password = '$password_hash' WHERE username = '$username'";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            echo "<script>alert('Password berhasil diperbarui!'); window.location.href='index.php?page=profile';</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan, coba lagi!');</script>";
        }
    }
}

$username = $_COOKIE['username'];
$query = "SELECT pegawai.* FROM pegawai
          JOIN pengguna ON pegawai.username = pengguna.username
          WHERE pegawai.username = '$username'";
$hasil = mysqli_query($koneksi, $query);
$profile = mysqli_fetch_array($hasil);

?>

<div class="main-content">
<div class="ui container">
        <div class="ui two column stackable grid">
            <!-- Detail Siswa -->
            <div class="column">
                <div class="ui segment">
                    <div class="ui top attached blue large label">Detail Pegawai</div>
                    <table class="ui celled very basic table">
                        <tbody>
                            <tr>
                                <td class="four wide"><strong><i class="id card icon"></i> Username</strong></td>
                                <td><?php echo $profile['username'] ?></td>
                            </tr>
                            <tr>
                                <td class="four wide"><strong><i class="user icon"></i> Nama</strong></td>
                                <td><?php echo $profile['nama_lengkap'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Ganti Password -->
            <div class="column">
                <div class="ui segment">
                    <div class="ui top attached orange large label">Ganti Password</div>
                    <form class="ui form" method="POST" action="">
                        <div class="field">
                            <label><i class="lock icon"></i> Ganti Password</label>
                            <input type="password" name="password_baru" placeholder="Masukkan password baru" required>
                        </div>
                        <div class="field">
                            <label><i class="lock icon"></i> Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" placeholder="Konfirmasi password baru" required>
                        </div>
                        <button class="ui yellow fluid button" type="submit">
                            <i class="sync icon"></i> Perbarui
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>