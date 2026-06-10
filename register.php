<?php
session_start();
require 'db.php';

$errorMessage = '';
$successMessage = '';

if (isset($_SESSION['user_id'])) {
  header('Location: admin/dashboard.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($nama === '' || $username === '' || $password === '') {
    $errorMessage = 'Semua field wajib diisi.';
  } else {
    $check = $conn->prepare('SELECT id_user FROM user WHERE username = ? LIMIT 1');
    $check->bind_param('s', $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $errorMessage = 'Username sudah digunakan, silakan pilih username lain.';
    } else {
      $hashedPassword = md5($password);
      $stmt = $conn->prepare('INSERT INTO user (nama, username, password, role) VALUES (?, ?, ?, "anggota")');
      $stmt->bind_param('sss', $nama, $username, $hashedPassword);

      if ($stmt->execute()) {
        $successMessage = 'Akun anggota berhasil dibuat. Silakan login.';
      } else {
        $errorMessage = 'Gagal membuat akun. Silakan coba lagi.';
      }
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SeoDash Free Bootstrap Admin Template by Adminmart</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/seodashlogo.png" />
  <link rel="stylesheet" href="SEODash/src/assets/css/styles.min.css" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="SEODash/src   /assets/images/logos/logo-light.svg" alt="">
                </a>
                <p class="text-center">Isi Data Diri Anda</p>
                <?php if ($errorMessage !== ''): ?>
                  <div class="alert alert-danger py-2 mb-3" role="alert">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                  </div>
                <?php endif; ?>
                <?php if ($successMessage !== ''): ?>
                  <div class="alert alert-success py-2 mb-3" role="alert">
                    <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
                  </div>
                <?php endif; ?>
                <form method="post" action="register.php">
                  <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                  </div>
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" autocomplete="username" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" required>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Sign Up</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Sudah Memiliki Akun?</p>
                    <a class="text-primary fw-bold ms-2" href="login.php">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>