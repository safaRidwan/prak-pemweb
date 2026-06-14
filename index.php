<?php
include 'koneksi.php';
session_start();
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// AUTO-UPDATE: Memastikan data kegiatan update secara realtime sebelum dihitung
$waktu_sekarang = date('Y-m-d H:i:s');
mysqli_query($conn, "UPDATE kegiatan SET status = 'selesai' WHERE status = 'berlangsung' AND waktu_selesai < '$waktu_sekarang'");

// 1. Hitung Jumlah Anggota
$query_anggota = mysqli_query($conn, "SELECT COUNT(*) as total FROM user WHERE role = 'anggota'");
$total_anggota = mysqli_fetch_assoc($query_anggota)['total'];

// 2. Hitung Kegiatan Berlangsung
$query_berlangsung = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan WHERE status = 'berlangsung'");
$total_berlangsung = mysqli_fetch_assoc($query_berlangsung)['total'];

// 3. Hitung Kegiatan Selesai
$query_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan WHERE status = 'selesai'");
$total_selesai = mysqli_fetch_assoc($query_selesai)['total'];

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="shortcut icon" type="image/png" href="SEODash/src/assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="SEODash/src/assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="dashboard.php" class="text-nowrap logo-img">
            <img src="logo/logo_teks.png" alt="" width="180" height="120" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Home</span></li>
            <li class="sidebar-item"><a class="sidebar-link" href="index.php" aria-expanded="false"><span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Dashboard</span></a></li>
          </ul>

          <?php if ($_SESSION['role'] == 'admin'): ?>
            <ul id="sidebarnav">
              <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Kelola</span></li>
              <li class="sidebar-item"><a class="sidebar-link" href="admin/data_anggota.php" aria-expanded="false"><span><iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Data Anggota</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="admin/buat_kegiatan.php" aria-expanded="false"><span><iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Buat Kegiatan</span></a></li>
            </ul>
            <ul id="sidebarnav">
              <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Laporan</span></li>
              <li class="sidebar-item"><a class="sidebar-link" href="admin/data_absensi.php" aria-expanded="false"><span><iconify-icon icon="solar:file-text-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Data Absensi</span></a></li>
            </ul>
          <?php endif; ?>

          <ul id="sidebarnav">
            <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Presensi</span></li>
            <li class="sidebar-item"><a class="sidebar-link" href="absensi.php" aria-expanded="false"><span><iconify-icon icon="solar:login-3-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Absensi</span></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="riwayat_absensi.php" aria-expanded="false"><span><iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Riwayat Absensi</span></a></li>
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
                    <a href="profile.php" class="d-flex align-items-center gap-2 dropdown-item">
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
        <div class="row">
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card bg-primary text-white shadow-sm h-100">
              <div class="card-body">
                <h5 class="card-title text-white mb-3">Jumlah Anggota</h5>
                <div class="d-flex justify-content-between align-items-center">
                  <h2 class="text-white fw-bold mb-0"><?= $total_anggota; ?></h2>
                  <iconify-icon icon="solar:users-group-rounded-bold-duotone" class="fs-8"></iconify-icon>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card bg-warning text-white shadow-sm h-100">
              <div class="card-body">
                <h5 class="card-title text-white mb-3">Kegiatan Berlangsung</h5>
                <div class="d-flex justify-content-between align-items-center">
                  <h2 class="text-white fw-bold mb-0"><?= $total_berlangsung; ?></h2>
                  <iconify-icon icon="solar:calendar-bold-duotone" class="fs-8"></iconify-icon>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-12 mb-4">
            <div class="card bg-success text-white shadow-sm h-100">
              <div class="card-body">
                <h5 class="card-title text-white mb-3">Kegiatan Selesai</h5>
                <div class="d-flex justify-content-between align-items-center">
                  <h2 class="text-white fw-bold mb-0"><?= $total_selesai; ?></h2>
                  <iconify-icon icon="solar:check-circle-bold-duotone" class="fs-8"></iconify-icon>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center py-5">
                <img src="SEODash/src/assets/images/backgrounds/product-tip.png" alt="image" class="img-fluid mb-3" width="205">
                <h4>Halo, <?= $_SESSION['nama'] ?>!</h4>
                <p class="card-subtitle mt-2 mb-4">Selamat datang di dashboard Sistem Presensi Karang Taruna.</p>
                <a href="absensi.php" class="btn btn-primary px-4">Pergi untuk Absen</a>
              </div>
            </div>
          </div>
        </div>

        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Instagram  <a href="https://instagram.com/hilall_lz" target="_blank" class="pe-1 text-primary text-decoration-underline"> Hilal </a> || <a href="https://instagram.com/musaa.nz" target="_blank" class="pe-1 text-primary text-decoration-underline"> Musafa </a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="SEODash/src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="SEODash/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="SEODash/src/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="SEODash/src/assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="SEODash/src/assets/js/sidebarmenu.js"></script>
  <script src="SEODash/src/assets/js/app.min.js"></script>
  <script src="SEODash/src/assets/js/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>