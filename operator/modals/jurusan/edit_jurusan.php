<!-- Modal Edit -->
<div class="ui mini modal" id="modalEdit">
    <div class="header">Edit Jurusan</div>
    <div class="content">
        <form class="ui form" method="POST">
            <input type="hidden" name="ubah_jurusan">
            <input type="hidden" name="id_jurusan" id="editIdJurusan">
        <div class="field">
            <label>Nama Jurusan</label>
            <input type="text" id="editJurusan" name="jurusan" required>
        </div>
            <button class="fluid ui primary button" type="submit">Simpan</button>
        </form>
    </div>
</div>