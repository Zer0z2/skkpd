<!-- Modal Edit -->
<div class="ui tiny modal" id="modalEdit">
    <div class="header">Edit Operator</div>
    <div class="content">
        <form class="ui form" method="POST" action="">
            <input type="hidden" name="ubah_pengguna">
            <input type="hidden" name="id_pengguna" id="editId">
        <div class="field">
            <label>Nama Lengkap</label>
            <input type="text" id="editNama" name="nama_lengkap" value="<?=$pengguna['nama_lengkap'];?>" required>
        </div>
        <div class="field">
            <label>Username</label>
            <input type="text" id="editUsername" name="username" value="<?=$pengguna['username'];?>" required>
        </div>
        <div class="field">
            <label>Password Baru (Opsional)</label>
            <input type="password" id="editPassword" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
        </div>
            <button class="fluid ui primary button" type="submit">Simpan</button>
        </form>
    </div>
</div>