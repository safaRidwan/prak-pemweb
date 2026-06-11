<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// 1. Logika Simpan Data
if (isset($_POST['simpan'])) {
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $status = 'berlangsung';

    $query = mysqli_query($conn, "INSERT INTO kegiatan (nama_kegiatan, deskripsi, waktu_mulai, waktu_selesai, status) 
                                  VALUES ('$nama_kegiatan', '$deskripsi', '$waktu_mulai', '$waktu_selesai', '$status')");

    if ($query) {
        echo "<script>alert('Kegiatan berhasil dibuat!'); window.location='buat_kegiatan.php';</script>";
    } else {
        echo "<script>alert('Gagal membuat kegiatan!');</script>";
    }
}

// 2. Logika Update Data
if (isset($_POST['update'])) {
    $id_kegiatan = $_POST['id_kegiatan'];
    $nama_kegiatan = $_POST['nama_kegiatan'];
    $deskripsi = $_POST['deskripsi'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $status = $_POST['status'];

    $query = mysqli_query($conn, "UPDATE kegiatan SET nama_kegiatan='$nama_kegiatan', deskripsi='$deskripsi', waktu_mulai='$waktu_mulai', waktu_selesai='$waktu_selesai', status='$status' WHERE id_kegiatan='$id_kegiatan'");

    if ($query) {
        echo "<script>alert('Kegiatan berhasil diupdate!'); window.location='buat_kegiatan.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate kegiatan!');</script>";
    }
}

// 3. Logika Hapus Data
if (isset($_POST['hapus'])) {
    $id_kegiatan = $_POST['id_kegiatan'];

    $query = mysqli_query($conn, "DELETE FROM kegiatan WHERE id_kegiatan='$id_kegiatan'");

    if ($query) {
        echo "<script>alert('Kegiatan berhasil dihapus!'); window.location='buat_kegiatan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus kegiatan!');</script>";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Kegiatan</title>
    <link rel="shortcut icon" type="image/png" href="../SEODash/src/assets/images/logos/seodashlogo.png" />
    <link rel="stylesheet" href="../SEODash/src/assets/css/styles.min.css" />
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="dashboard.php" class="text-nowrap logo-img">
                        <img src="../SEODash/src/assets/images/logos/logo-light.svg" alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
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
                                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <img src="../SEODash/src/assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="../profil.php" class="d-flex align-items-center gap-2 dropdown-item">
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

            <div class="container-fluid">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-semibold mb-0">Buat Kegiatan Baru</h5>
                        </div>
                        <form accept="" method="post">
                            <div class="mb-3">
                                <label class="form-label">Nama Kegiatan</label>
                                <input type="text" class="form-control" name="nama_kegiatan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Mulai</label>
                                    <input type="datetime-local" class="form-control" name="waktu_mulai" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Waktu Selesai</label>
                                    <input type="datetime-local" class="form-control" name="waktu_selesai" required>
                                </div>
                            </div>
                            <div class="footer mt-2">
                                <button type="submit" name="simpan" class="btn btn-primary">Simpan Kegiatan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Daftar Kegiatan</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Waktu Mulai</th>
                                        <th>Waktu Selesai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query_kegiatan = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY id_kegiatan DESC");
                                    while ($row = mysqli_fetch_assoc($query_kegiatan)) {
                                        // Format waktu untuk input type datetime-local (Y-m-d\TH:i)
                                        $waktu_mulai_format = date('Y-m-d\TH:i', strtotime($row['waktu_mulai']));
                                        $waktu_selesai_format = date('Y-m-d\TH:i', strtotime($row['waktu_selesai']));
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_kegiatan']); ?></td>
                                        <td><?= date('d M Y H:i', strtotime($row['waktu_mulai'])); ?></td>
                                        <td><?= date('d M Y H:i', strtotime($row['waktu_selesai'])); ?></td>
                                        <td>
                                            <?php if ($row['status'] == 'berlangsung'): ?>
                                                <span class="badge bg-success">Berlangsung</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Selesai</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_kegiatan']; ?>">Edit</button>
                                            <button class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#hapusModal<?= $row['id_kegiatan']; ?>">Hapus</button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="editModal<?= $row['id_kegiatan']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Kegiatan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_kegiatan" value="<?= $row['id_kegiatan']; ?>">
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Kegiatan</label>
                                                            <input type="text" class="form-control" name="nama_kegiatan" value="<?= htmlspecialchars($row['nama_kegiatan']); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea class="form-control" name="deskripsi" rows="3"><?= htmlspecialchars($row['deskripsi']); ?></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Waktu Mulai</label>
                                                            <input type="datetime-local" class="form-control" name="waktu_mulai" value="<?= $waktu_mulai_format; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Waktu Selesai</label>
                                                            <input type="datetime-local" class="form-control" name="waktu_selesai" value="<?= $waktu_selesai_format; ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-select" name="status">
                                                                <option value="berlangsung" <?= ($row['status'] == 'berlangsung') ? 'selected' : ''; ?>>Berlangsung</option>
                                                                <option value="selesai" <?= ($row['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="update" class="btn btn-warning">Update Data</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="hapusModal<?= $row['id_kegiatan']; ?>" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_kegiatan" value="<?= $row['id_kegiatan']; ?>">
                                                        <p>Apakah Anda yakin ingin menghapus kegiatan <strong><?= htmlspecialchars($row['nama_kegiatan']); ?></strong>?</p>
                                                        <p class="text-danger small">Peringatan: Menghapus kegiatan ini mungkin juga akan menghapus data presensi yang terkait (tergantung aturan database/cascade).</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" name="hapus" class="btn btn-danger">Ya, Hapus!</button>
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
</body>

</html>