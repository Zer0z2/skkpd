<?php
include "koneksi/koneksi.php";
if (isset($_POST['tombol_login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $cek_operator = mysqli_query($koneksi, "SELECT username, password FROM pengguna WHERE username='$user'");
    $data_operator = mysqli_fetch_assoc($cek_operator);

    $cek_siswa = mysqli_query($koneksi, "SELECT nis, password FROM pengguna WHERE nis='$user'");
    $data_siswa = mysqli_fetch_assoc($cek_siswa);

    if (mysqli_num_rows($cek_operator) > 0) {
        if (password_verify($pass, $data_operator['password'])) {
            $user_operator = $data_operator['username'];
            $nama_operator = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_Lengkap FROM pegawai WHERE username = '$user_operator'"));
            
            setcookie('username', $data_operator['username'], time() + (60 * 60 * 24 * 7), '/');
            setcookie('nama_lengkap', $nama_operator['nama_Lengkap'], time() + (60 * 60 * 24 * 7), '/');
            setcookie('level_user', 'operator', time() + (60 * 60 * 24 * 7), '/');
            
            echo "<script>alert('Berhasil Login');window.location.href='index.php?page=home'</script>";
        } else {
            echo "<script>alert('Gagal Login, password Salah');window.location.href='login.php'</script>";
        }
    } elseif (mysqli_num_rows($cek_siswa) > 0) {
        if (password_verify($pass, $data_siswa['password'])) {
            $user_siswa = $data_siswa['nis'];
            $nama_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_siswa FROM siswa WHERE nis = '$user_siswa'"));
            
            setcookie('nis', $data_siswa['nis'], time() + (60 * 60 * 24 * 7), '/');
            setcookie('nama_siswa', $nama_siswa['nama_siswa'], time() + (60 * 60 * 24 * 7), '/');
            setcookie('level_user', 'siswa', time() + (60 * 60 * 24 * 7), '/');
            
            echo "<script>alert('Berhasil Login');window.location.href='index.php?page=home'</script>";
        } else {
            echo "<script>alert('Gagal Login, password Salah');window.location.href='login.php'</script>";
        }
    } else {
        echo "<script>alert('Gagal Login, username atau password salah');window.location.href='login.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Globaliti SKKPd</title>
    <link rel="icon" href="assets/logo/logo.ico">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/fomantic/dist/semantic.min.css">
    <script src="assets/fomantic/dist/semantic.min.js"></script>
    <style>
        html, body {
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .ui.grid.height {
            flex: 1;
            display: flex;
        }

        .ui.grid.height > .column {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        #img {
            background-image: url(assets/image/img_login.jpg);
            background-size: cover;
            filter: brightness(50%);
        }

        .ui.form {
            width: 50%;
        }

        .logomark {
            width: 150px !important;
        }
    </style>
</head>
<body>
    <div class="ui grid height">
        <div class="ten wide column" id="img"></div>
        <div class="six wide column">
            <form class="ui form" action="" method="post">
                <h1 class="ui center aligned header">
                    <img class="logomark" src="assets/logo/logo_login.png" alt="logomark">
                    <div class="ui hidden divider"></div>
                    Selamat Datang
                    <div class="sub header" style="margin-top: 10px;">Silahkan login untuk melanjutkan.</div>
                </h1>
                <div class="ui divider"></div>
                <div class="field">
                    <label>Username/NIS</label>
                    <div class="ui left icon input">
                        <input type="text" placeholder="Masukkan Username atau NIS" name="username" autocomplete="off">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field">
                    <label>Password</label>
                    <div class="ui left icon input">
                        <i class="key icon"></i>
                        <input type="password" name="password" placeholder="Masukkan password" autocomplete="off">
                    </div>
                </div>
                <div class="ui hidden divider"></div>
                <button name="tombol_login" class="fluid ui blue button" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>