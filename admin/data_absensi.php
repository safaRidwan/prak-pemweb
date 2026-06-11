<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Ambil semua daftar kegiatan untuk dropdown filter
$query_kegiatan = mysqli_query($conn, "SELECT * FROM kegiatan ORDER BY id_kegiatan DESC");
$list_kegiatan = [];
while ($row = mysqli_fetch_assoc($query_kegiatan)) {
    $list_kegiatan[] = $row;
}

// Tentukan ID Kegiatan yang sedang dipilih (Default: Kegiatan paling terbaru)
$id_kegiatan_aktif = isset($_GET['kegiatan']) ? $_GET['kegiatan'] : (count($list_kegiatan) > 0 ? $list_kegiatan[0]['id_kegiatan'] : 0);

// Cari nama kegiatan yang sedang dipilih untuk ditampilkan di tabel
$nama_kegiatan_aktif = "-";
foreach ($list_kegiatan as $k) {
    if ($k['id_kegiatan'] == $id_kegiatan_aktif) {
        $nama_kegiatan_aktif = $k['nama_kegiatan'];
        break;
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Absensi</title>
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
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title fw-semibold mb-0">Data Absensi Anggota</h5>
                        </div>

                        <form method="GET" class="mb-4">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Pilih Kegiatan untuk melihat Absensi:</label>
                                    <select name="kegiatan" class="form-select" onchange="this.form.submit()">
                                        <?php foreach ($list_kegiatan as $kegiatan): ?>
                                            <option value="<?= $kegiatan['id_kegiatan'] ?>" <?= ($kegiatan['id_kegiatan'] == $id_kegiatan_aktif) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($kegiatan['nama_kegiatan']) ?> (<?= date('d M Y', strtotime($kegiatan['waktu_mulai'])) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Anggota</th>
                                        <th scope="col">Kegiatan</th>
                                        <th scope="col">Jam Masuk</th>
                                        <th scope="col">Jam Keluar</th>
                                        <th scope="col">Foto</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($id_kegiatan_aktif != 0) {
                                        // Menambahkan p.foto pada pemanggilan query
                                        $query_absensi = "
                                            SELECT u.id_user, u.nama, p.jam_masuk, p.jam_keluar, p.foto 
                                            FROM user u 
                                            LEFT JOIN presensi p ON u.id_user = p.id_user AND p.id_kegiatan = '$id_kegiatan_aktif'
                                            WHERE u.role = 'anggota'
                                            ORDER BY u.nama ASC
                                        ";
                                        $result = mysqli_query($conn, $query_absensi);
                                        $no = 1;

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $status = '';
                                            $jam_masuk = $row['jam_masuk'] ? $row['jam_masuk'] : '-';
                                            $jam_keluar = $row['jam_keluar'] ? $row['jam_keluar'] : '-';
                                            
                                            // Menampilkan foto
                                            $foto_html = '-';
                                            if (!empty($row['foto'])) {
                                                // Karena file ini ada di folder admin, maka kita mundur satu folder dengan ../
                                                $foto_path = '../uploads/' . htmlspecialchars($row['foto']);
                                                $foto_html = '
                                                    <a href="' . $foto_path . '" target="_blank">
                                                        <img src="' . $foto_path . '" alt="Foto Presensi" width="50" height="50" class="rounded shadow-sm" style="object-fit: cover; border: 1px solid #ddd;">
                                                    </a>';
                                            }

                                            // Penentuan Status
                                            if (empty($row['jam_masuk'])) {
                                                $status = '<span class="badge bg-danger">Belum Absen</span>';
                                            } else if (!empty($row['jam_masuk']) && (empty($row['jam_keluar']) || $row['jam_keluar'] == '00:00:00')) {
                                                $status = '<span class="badge bg-warning text-dark">Absen Masuk</span>';
                                            } else {
                                                $status = '<span class="badge bg-success">Absen Pulang</span>';
                                            }
                                    ?>
                                            <tr>
                                                <th scope="row" class="text-center"><?= $no++; ?></th>
                                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                                <td><?= htmlspecialchars($nama_kegiatan_aktif); ?></td>
                                                <td class="text-center"><?= $jam_masuk; ?></td>
                                                <td class="text-center"><?= $jam_keluar; ?></td>
                                                <td class="text-center"><?= $foto_html; ?></td>
                                                <td class="text-center"><?= $status; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>Belum ada kegiatan yang terdaftar.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="py-6 px-6 text-center">
                    <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">AdminMart.com</a> Distributed by <a href="https://themewagon.com/" target="_blank" class="pe-1 text-primary text-decoration-underline">ThemeWagon</a></p>
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