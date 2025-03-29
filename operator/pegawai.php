<div class="main-content">
<div class="ui container" style="margin-top: 50px;">
<h1>Tabel Pengguna</h1>

<div class="ui segment">
  <div class="ui two column very relaxed grid">
    <div class="column">
    <div class="ui search">
  <div class="ui icon input">
    <input class="prompt" type="text" style="width: 68rem;" placeholder="Github Repositories...">
    <i class="search icon"></i>
  </div>
  <div class="results"></div>
</div>
    </div>
    <div class="column right aligned">
    <a class="ui labeled icon primary button" href="operator/fungsi_pengguna/tambah.php"><i class="add icon"></i>Tambah</a>

    </div>
  </div>
  <div class="ui hidden vertical divider">
  </div>
</div>




<table class="ui striped fixed compact long sortable stackable blue scrolling table">
    <thead>
     <tr>
       <th>No</th>
       <th>Username</th>
       <th>Password</th>
       <th>Aksi</th>
     </tr>
    </thead>
    <tbody>
     <?php
     $no = 1;
     include $_SERVER['DOCUMENT_ROOT'] . "/skkpd/koneksi/koneksi.php";
     $hasil = mysqli_query($koneksi, "SELECT id_pengguna, username, password FROM pengguna where nis is null");
     while($pengguna = mysqli_fetch_array($hasil)) { ?>
         <tr>
             <td><?=$no++?></td>
             <td><?=$pengguna['username'];?></td>
             <td><?=$pengguna['password'];?></td>
             <td>
              <div class="ui icon buttons">
                <a class="ui olive button" href="operator/fungsi_operator/ubah.php?id_pengguna=<?=$pengguna['id_pengguna']?>"><i class="edit icon"></i></a>
                <a class="ui red button" href="operator/fungsi_operator/hapus.php?id_pengguna=<?=$pengguna['id_pengguna']?>"><i class="trash icon"></i></a>
              </div>
             </td>
         </tr>
     <?php } ?>
    </tbody>
</table>
</div>
</div>
