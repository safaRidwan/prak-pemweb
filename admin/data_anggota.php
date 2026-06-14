<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header('Location: ../login.php');
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Anggota</title>
  <link rel="shortcut icon" type="image/png" href="../SEODash/src/assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="../SEODash/src/assets/css/styles.min.css" />
</head>


<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="dashboard.php" class="text-nowrap logo-img">
            <img src="../logo/logo_teks.png" alt="" width="180" height="120" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Home</span></li>
            <li class="sidebar-item"><a class="sidebar-link" href="dashboard.php" aria-expanded="false"><span><iconify-icon icon="solar:home-smile-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Dashboard</span></a></li>
          </ul>

          <?php if ($_SESSION['role'] == 'admin'): ?>
            <ul id="sidebarnav">
              <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Kelola</span></li>
              <li class="sidebar-item"><a class="sidebar-link" href="data_anggota.php" aria-expanded="false"><span><iconify-icon icon="solar:user-plus-rounded-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Data Anggota</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="buat_kegiatan.php" aria-expanded="false"><span><iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Buat Kegiatan</span></a></li>
            </ul>
            <ul id="sidebarnav">
              <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Laporan</span></li>
              <li class="sidebar-item"><a class="sidebar-link" href="data_absensi.php" aria-expanded="false"><span><iconify-icon icon="solar:file-text-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Data Absensi</span></a></li>
            </ul>
          <?php endif; ?>

          <ul id="sidebarnav">
            <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-6"></i><span class="hide-menu">Presensi</span></li>
            <li class="sidebar-item"><a class="sidebar-link" href="../absensi.php" aria-expanded="false"><span><iconify-icon icon="solar:login-3-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Absensi</span></a></li>
            <li class="sidebar-item"><a class="sidebar-link" href="../riwayat_absensi.php" aria-expanded="false"><span><iconify-icon icon="solar:layers-minimalistic-bold-duotone" class="fs-6"></iconify-icon></span><span class="hide-menu">Riwayat Absensi</span></a></li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
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
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="../SEODash/src/assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="../profile.php" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="../logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->

      <div class="container-fluid">
        <?php
        if (isset($_SESSION['pesan'])) {
        ?>
          <div class="container-fluid" id="alertPesan" role="alert">
            <?= $_SESSION['pesan']; ?>
          </div>
        <?php
          unset($_SESSION['pesan']);
        }
        ?>
        <div class="card">
          <div class="card-body">

            <!-- Bagian yang diubah: Membungkus judul dan tombol menggunakan d-flex -->
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="card-title fw-semibold mb-0">Data Anggota</h5>

              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAnggota">
                + Tambah Anggota
              </button>

              <!-- Modal -->
              <div class="modal fade" id="tambahAnggota" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5">Tambah Anggota</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="proses_anggota.php" method="post">
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label">Nama</label>
                          <input type="text" class="form-control" name="nama" required>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Username</label>
                          <input type="text" class="form-control" name="username" required>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Password</label>
                          <input type="password" class="form-control" name="password" required>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Level</label>
                          <select class="form-select" name="role" required>
                            <option value="">Pilih Level</option>
                            <option value="admin">Admin</option>
                            <option value="anggota">Anggota</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>


            </div>
            <!-- Akhir bagian yang diubah -->

            <div class="card mb-0">
                <table class="table table-striped table-bordered align-middle">
                  <thead class="table-dark text-center">
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Role</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $data = mysqli_query($conn, "SELECT * FROM user");
                  while ($row = mysqli_fetch_assoc($data)) {
                  ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $row['nama']; ?></td>
                      <td><?= ucfirst($row['role']); ?></td>
                      <td>
                        <button
                          class="btn btn-sm btn-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#edit<?= $row['id_user']; ?>">
                          Edit
                        </button>

                        <a href="proses_anggota.php?hapus=<?= $row['id_user']; ?>"
                          class="btn btn-sm btn-danger"
                          onclick="return confirm('Yakin ingin menghapus data ini?')">
                          Delete
                        </a>
                      </td>
                    </tr>
                    <div class="modal fade" id="edit<?= $row['id_user']; ?>" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">

                          <div class="modal-header">
                            <h5 class="modal-title">Edit Anggota</h5>
                            <button type="button"
                              class="btn-close"
                              data-bs-dismiss="modal"></button>
                          </div>

                          <form action="proses_anggota.php" method="post">

                            <input type="hidden"
                              name="id"
                              value="<?= $row['id_user']; ?>">

                            <div class="modal-body">

                              <div class="mb-3">
                                <label>Nama</label>
                                <input type="text"
                                  name="nama"
                                  class="form-control"
                                  value="<?= $row['nama']; ?>"
                                  required>
                              </div>

                              <div class="mb-3">
                                <label>Username</label>
                                <input type="text"
                                  name="username"
                                  class="form-control"
                                  value="<?= $row['username']; ?>"
                                  required>
                              </div>

                              <div class="mb-3">
                                <label>Role</label>
                                <select name="role"
                                  class="form-select">

                                  <option value="admin"
                                    <?= ($row['role'] == 'admin') ? 'selected' : '' ?>>
                                    Admin
                                  </option>

                                  <option value="anggota"
                                    <?= ($row['role'] == 'anggota') ? 'selected' : '' ?>>
                                    Anggota
                                  </option>

                                </select>
                              </div>

                            </div>

                            <div class="modal-footer">
                              <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                                Batal
                              </button>

                              <button type="submit"
                                name="update"
                                class="btn btn-warning">
                                Update
                              </button>
                            </div>

                          </form>

                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="py-6 px-6 text-center">
          <p class="mb-0 fs-4">Instagram  <a href="https://instagram.com/hilall_lz" target="_blank" class="pe-1 text-primary text-decoration-underline"> Hilal </a> || <a href="https://instagram.com/musaa.nz" target="_blank" class="pe-1 text-primary text-decoration-underline"> Musafa </a></p>
        </div>
      </div>
    </div>
    <script src="../SEODash/src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../SEODash/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../SEODash/src/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../SEODash/src/assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../SEODash/src/assets/js/sidebarmenu.js"></script>
    <script src="../SEODash/src/assets/js/app.min.js"></script>
    <script src="../SEODash/src/assets/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script>
      setTimeout(function() {
        let alert = document.getElementById('alertPesan');

        if (alert) {
          let bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        }
      }, 3000);
    </script>
</body>

</html>