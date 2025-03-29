<style>
@import url("https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap");
:root {
  --tablet: 768px;
  --smallMonitor: 992px;
  --largeMonitor: 1200px;
  --font-family: 'Open Sans', sans-serif;
  --sidebar-bg: #0B1332;
  --sidebar-hover: #132053;
  --sidebar-active: #3454D1;
  --sidebar-text: #ffffff;
}
nav.ui.top.fixed.menu .left.menu img {
  height: 33px; /* Adjust height as needed */
  width: auto; /* Maintain aspect ratio */
}
.logo{
  display: flex;
  justify-content: center;
  width: 100%;
  padding: 15px 0;
}
.logo img {
  max-width: 120px;
  height: auto;
}
body {
  font-family: var(--font-family) !important;
}

body ::-webkit-scrollbar {
  width: 6px;
}

.ui.vertical.menu.sidebar-menu {
  margin-top: 0 !important;
  max-height: 100% !important;
  height: 100% !important;
  width: 15rem !important;
  background-color: var(--sidebar-bg);
}

.ui.vertical.menu.sidebar-menu .item {
  font-size: 1.1rem;
  padding: 15px 20px !important;
  margin: 5px 8px !important;
  border-radius: 5px !important;
  color: var(--sidebar-text) !important;
  transition: all 0.2s ease-in-out;
}

.ui.vertical.menu.sidebar-menu .item i.icon {
  float: left;
  margin: 0em 0.8em 0em 0em;
  font-size: 1.2rem;
}

.ui.vertical.menu.sidebar-menu .item:hover {
  background-color: var(--sidebar-hover) !important;
  margin-left: 20px !important; /* Tambahkan efek margin saat hover */
}

.ui.vertical.menu.sidebar-menu .item.active {
  background-color: var(--sidebar-active) !important;
}
.ui.top.fixed.menu {
  background-color: var(--sidebar-bg) !important;
}

.ui.top.fixed.menu .right.menu .item {
  color: var(--sidebar-text) !important;
}
.ui.top.fixed.menu .left.menu .item {
  color: var(--sidebar-text) !important;
}

/* Dropdown Menu */
.right.menu .ui.dropdown .menu {
  background-color: var(--sidebar-bg) !important;
  border: none !important;
}

.right.menu .ui.dropdown .menu .item {
  color: var(--sidebar-text) !important;
}

.right.menu .ui.dropdown .menu .item:hover {
  background-color: var(--sidebar-hover) !important;
}

.main-content {
  margin-top: 4rem;
}

@media (min-width: 768px) {
  .ui.vertical.menu.sidebar-menu {
    visibility: visible;
    -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
  }
  .main-content {
    margin-left: 16rem;
    margin-right: 1rem;
  }
  .sidebar-menu-toggler {
    display: none !important;
  }
}
</style>
</head>
<body>
  <?php
      $user = $_COOKIE['username'];
  ?>
<div class="ui sidebar vertical menu sidebar-menu" id="sidebar">
      <div class="logo">
        <img src="assets/logo/logo_sidebar.png" alt="Logo">
      </div>
      <a class="item" href="index.php?page=home">
        <div>
          <i class="home icon"></i>
          Dashboard
        </div>
      </a>
      <a class="item" href="index.php?page=siswa">
        <div>
          <i class="users icon"></i>
          Siswa
        </div>
      </a>
      <a class="item" href="index.php?page=jurusan">
        <div>
          <i class="school icon"></i>
          Jurusan
        </div>
      </a>
      <a class="item" href="index.php?page=operator">
        <div>
          <i class="user tie icon"></i>
          Operator
        </div>
      </a>
      <a class="item" href="index.php?page=kategori">
        <div>
          <i class="tasks icon"></i>
          Kategori
        </div>
      </a>
      <a class="item" href="index.php?page=kegiatan">
        <div>
          <i class="list alternate icon"></i>
          Kegiatan
        </div>
      </a>
      <a class="item" href="index.php?page=sertifikat">
        <div>
          <i class="certificate icon"></i>
          Sertifikat
        </div>
      </a>
</div>

<!-- sidebar -->
<!-- top nav -->

<nav class="ui top fixed menu">
  <div class="left menu">
    <a href="#" class="sidebar-menu-toggler item" data-target="#sidebar">
      <i class="sidebar icon"></i>
    </a>
  </div>
  <div class="right menu">
    <div class="ui dropdown item">
      <i class="user circle icon"></i> <?php echo $user ?>
      <div class="menu">
        <a href="index.php?page=profile" class="item">
          <i class="info circle icon"></i> Profile</a>
        <a href="#" class="item" onclick="if(confirm('Are you sure you want to logout?')) { window.location.href='logout.php'; }">
          <i class="sign out icon"></i>
          Logout
        </a>
      </div>
    </div>
  </div>
</nav>

<script>
  $(document).ready(function() {
    $('.ui.dropdown').dropdown();
    $('.sidebar-menu-toggler').on('click', function() {
      var target = $(this).data('target');
      $(target)
        .sidebar({
          dinPage: true,
          transition: 'overlay',
          mobileTransition: 'overlay'
        })
        .sidebar('toggle');
    });
  });
  $(document).ready(function() {
  var currentUrl = window.location.href;
  var isActive = false;

  $('.ui.vertical.menu.sidebar-menu a').each(function() {
    if (this.href === currentUrl) {
      $(this).addClass('active');
      isActive = true;
    }
  });

});

</script>
</body>
</html>
