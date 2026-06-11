<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

$id_user = $_SESSION['user_id'];
$pesan = '';

// Proses Update Profil
if (isset($_POST['update_profil'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_baru = $_POST['password'];

    // Cek apakah user ingin mengubah password atau tidak
    if (!empty($password_baru)) {
        // Jika password diisi, update beserta passwordnya
        $password_md5 = md5($password_baru);
        $query = mysqli_query($conn, "UPDATE user SET nama='$nama', username='$username', password='$password_md5' WHERE id_user='$id_user'");
    } else {
        // Jika password kosong, update hanya nama dan username
        $query = mysqli_query($conn, "UPDATE user SET nama='$nama', username='$username' WHERE id_user='$id_user'");
    }

    if ($query) {
        // Update session agar perubahan langsung terlihat di pojok kanan atas / sapaan
        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;
        
        $pesan = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profil Anda berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        $pesan = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Gagal memperbarui profil. Username mungkin sudah digunakan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

// Ambil data user yang sedang login untuk ditampilkan di form
$query_user = mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id_user'");
$data_user = mysqli_fetch_assoc($query_user);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Saya</title>
  <link rel="shortcut icon" type="image/png" href="SEODash/src/assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="SEODash/src/assets/css/styles.min.css" />
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="admin/dashboard.php" class="text-nowrap logo-img">
            <img src="SEODash/src/assets/images/logos/logo-light.svg" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="admin/dashboard.php" aria-expanded="false">
                <span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
          </ul>
          
          <?php if($_SESSION['role'] == 'admin'): ?>
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Kelola</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="admin/data_anggota.php" aria-expanded="false">
                <span><iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Data Anggota</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="admin/buat_kegiatan.php" aria-expanded="false">
                <span><iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Buat Kegiatan</span>
              </a>
            </li>
          </ul>
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Laporan</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="admin/data_absensi.php" aria-expanded="false">
                <span><iconify-icon icon="solar:file-text-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Data Absensi</span>
              </a>
            </li>
          </ul>
          <?php endif; ?>

          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-6"></i>
              <span class="hide-menu">Presensi</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="absensi.php" aria-expanded="false">
                <span><iconify-icon icon="solar:login-3-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Absensi</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="riwayat_absensi.php" aria-expanded="false">
                <span><iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon></span>
                <span class="hide-menu">Riwayat Absensi</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
    <div class="body-wrapper">
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                  <img src="SEODash/src/assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="profil.php" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>

      <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?= $pesan ?>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h5 class="card-title fw-semibold mb-0">Profil Pribadi</h5>
                            <span class="badge bg-primary rounded-3 fw-semibold"><?= strtoupper($data_user['role']); ?></span>
                        </div>

                        <div class="text-center mb-4">
                            <img src="SEODash/src/assets/images/profile/user-1.jpg" alt="Profile" width="100" height="100" class="rounded-circle mb-3 shadow-sm">
                        </div>

                        <form action="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($data_user['nama']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($data_user['username']); ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" placeholder="Ketik password baru...">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah password.</div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <button type="submit" name="update_profil" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">AdminMart.com</a> Distributed by <a href="https://themewagon.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">ThemeWagon</a></p>
        </div>
      </div>
    </div>
  </div>

  <script src="SEODash/src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="SEODash/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="SEODash/src/assets/js/sidebarmenu.js"></script>
  <script src="SEODash/src/assets/js/app.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>
</html>