<!-- Modal Tambah -->
<div class="ui tiny modal" id="modalTambah">
    <div class="header">Tambah Operator</div>
    <div class="content">
    <form class="ui form" method="POST" action="">
        <input type="hidden" name="tambah_pengguna" value="1">
        <div class="field">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" required>
        </div>
        <div class="field">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan Username" required>
        </div>
        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan Password" required>
        </div>
        <button class="fluid ui primary button" type="submit">Tambah</button>
    </form>
    </div>
</div>