<?php
include 'koneksi.php';
session_start();

// Set zona waktu agar akurat dengan jam lokal
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['user_id'];
$pesan = '';

// AUTO-UPDATE: Ubah status kegiatan menjadi 'selesai' jika waktu sekarang sudah melewati waktu_selesai
$waktu_sekarang_full = date('Y-m-d H:i:s');
mysqli_query($conn, "UPDATE kegiatan SET status = 'selesai' WHERE status = 'berlangsung' AND waktu_selesai < '$waktu_sekarang_full'");

// 1. Cari kegiatan yang SEDANG BERLANGSUNG
$query_kegiatan = mysqli_query($conn, "SELECT * FROM kegiatan WHERE status = 'berlangsung' ORDER BY id_kegiatan DESC LIMIT 1");
$kegiatan_aktif = mysqli_fetch_assoc($query_kegiatan);

$data_presensi = null;
$sudah_selesai = false;

// 2. Cek status presensi user untuk kegiatan ini
if ($kegiatan_aktif) {
    $id_kegiatan = $kegiatan_aktif['id_kegiatan'];
    $cek_presensi = mysqli_query($conn, "SELECT * FROM presensi WHERE id_user = '$id_user' AND id_kegiatan = '$id_kegiatan' LIMIT 1");
    $data_presensi = mysqli_fetch_assoc($cek_presensi);

    if ($data_presensi && $data_presensi['jam_keluar'] != null && $data_presensi['jam_keluar'] != '00:00:00') {
        $sudah_selesai = true;
    }
}

// 3. Proses Absen Masuk
if (isset($_POST['absen_masuk']) && $kegiatan_aktif && !$data_presensi) {
    $waktu_sekarang = date('H:i:s');
    $foto_data = $_POST['foto_data'];
    $nama_file_foto = '';

    if (!empty($foto_data)) {
        $folderPath = "uploads/";
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $image_parts = explode(";base64,", $foto_data);
        $image_base64 = base64_decode($image_parts[1]);

        $nama_file_foto = 'foto_' . $id_user . '_' . time() . '.png';
        $file_path = $folderPath . $nama_file_foto;

        file_put_contents($file_path, $image_base64);
    }

    $id_kegiatan_input = $kegiatan_aktif['id_kegiatan'];
    $query = mysqli_query($conn, "INSERT INTO presensi (id_user, id_kegiatan, jam_masuk, foto) VALUES ('$id_user', '$id_kegiatan_input', '$waktu_sekarang', '$nama_file_foto')");

    if ($query) {
        $pesan = '<div class="alert alert-success">Berhasil Absen Masuk pada ' . $waktu_sekarang . '</div>';
        header("Refresh:1");
    }
}

// 4. Proses Absen Keluar
if (isset($_POST['absen_keluar']) && $data_presensi && !$sudah_selesai) {
    $waktu_sekarang = date('H:i:s');
    $id_presensi = $data_presensi['id_presensi'];

    $query = mysqli_query($conn, "UPDATE presensi SET jam_keluar = '$waktu_sekarang' WHERE id_presensi = '$id_presensi'");
    if ($query) {
        $pesan = '<div class="alert alert-info">Berhasil Absen Keluar pada ' . $waktu_sekarang . '</div>';
        header("Refresh:1");
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mulai Presensi</title>
    <link rel="shortcut icon" type="image/png" href="SEODash/src/assets/images/logos/seodashlogo.png" />
    <link rel="stylesheet" href="SEODash/src/assets/css/styles.min.css" />
    <style>
        #kamera {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            display: none;
            background-color: #000;
            margin: 0 auto;
        }

        #hasil_foto {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            display: none;
            margin: 0 auto;
        }
    </style>
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

                    <?php if ($_SESSION['role'] == 'admin'): ?>
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
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <?= $pesan ?>
                        <div class="card shadow-sm">
                            <div class="card-body py-5 text-center">
                                <h4 class="card-title mb-2">Halo, <?= $_SESSION['nama']; ?>!</h4>

                                <?php if (!$kegiatan_aktif): ?>
                                    <div class="alert alert-warning mt-4 text-start">
                                        Saat ini tidak ada kegiatan Karang Taruna yang sedang berlangsung, atau waktu kegiatan telah habis.
                                    </div>

                                <?php elseif ($sudah_selesai): ?>
                                    <h6 class="text-primary mb-4"><?= htmlspecialchars($kegiatan_aktif['nama_kegiatan']) ?></h6>
                                    <div class="alert alert-success mt-3 text-start">
                                        <i class="ti ti-check fs-6 d-block mb-2"></i>
                                        Anda telah menyelesaikan absensi untuk kegiatan ini. Terima kasih!
                                    </div>

                                <?php else: ?>
                                    <p class="mb-2">Silakan lakukan presensi kegiatan hari ini:</p>
                                    <h5 class="text-primary mb-4"><?= htmlspecialchars($kegiatan_aktif['nama_kegiatan']) ?></h5>
                                    <p class="text-muted small">Batas Waktu: <?= date('d M Y, H:i', strtotime($kegiatan_aktif['waktu_selesai'])) ?></p>

                                    <form method="post" id="formAbsen">
                                        <?php if (!$data_presensi): ?>
                                            <div class="mb-3 d-flex flex-column align-items-center w-100">
                                                <video id="kamera" autoplay playsinline></video>
                                                <canvas id="canvas" style="display:none;"></canvas>
                                                <img id="hasil_foto" src="" alt="Hasil Foto">
                                            </div>
                                            <input type="hidden" name="foto_data" id="foto_data" required>
                                            <button type="button" id="btnAmbilFoto" class="btn btn-secondary mb-2 w-100">Ambil Foto Selfie</button>
                                            <button type="submit" name="absen_masuk" id="btnAbsenMasuk" class="btn btn-primary btn-lg w-100" style="display:none;">Kirim Absen Masuk</button>
                                        <?php else: ?>
                                            <div class="alert alert-secondary text-start">
                                                Waktu Masuk Anda: <strong><?= $data_presensi['jam_masuk'] ?></strong>
                                            </div>
                                            <button type="submit" name="absen_keluar" class="btn btn-danger btn-lg w-100">Absen Keluar</button>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="SEODash/src/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="SEODash/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="SEODash/src/assets/js/sidebarmenu.js"></script>
    <script src="SEODash/src/assets/js/app.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    <?php if ($kegiatan_aktif && !$data_presensi): ?>
        <script>
            const video = document.getElementById('kamera');
            if (video) {
                const canvas = document.getElementById('canvas');
                const btnAmbilFoto = document.getElementById('btnAmbilFoto');
                const btnAbsenMasuk = document.getElementById('btnAbsenMasuk');
                const fotoDataInput = document.getElementById('foto_data');
                const hasilFoto = document.getElementById('hasil_foto');
                let streamActive = true;

                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "user"
                        }
                    })
                    .then(function(stream) {
                        video.style.display = 'block';
                        video.srcObject = stream;
                    })
                    .catch(function(err) {
                        console.log("Error kamera: ", err);
                        alert("Tidak dapat mengakses kamera. Pastikan memberikan izin akses!");
                    });

                btnAmbilFoto.addEventListener('click', function() {
                    if (streamActive) {
                        const context = canvas.getContext('2d');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);

                        const dataURL = canvas.toDataURL('image/png');
                        fotoDataInput.value = dataURL;

                        video.style.display = 'none';
                        hasilFoto.src = dataURL;
                        hasilFoto.style.display = 'block';

                        btnAmbilFoto.innerText = "Ulangi Foto";
                        btnAmbilFoto.classList.replace('btn-secondary', 'btn-warning');

                        btnAbsenMasuk.style.display = 'block';
                        streamActive = false;
                    } else {
                        video.style.display = 'block';
                        hasilFoto.style.display = 'none';
                        btnAbsenMasuk.style.display = 'none';
                        fotoDataInput.value = '';

                        btnAmbilFoto.innerText = "Ambil Foto Selfie";
                        btnAmbilFoto.classList.replace('btn-warning', 'btn-secondary');
                        streamActive = true;
                    }
                });
            }
        </script>
    <?php endif; ?>
</body>

</html>