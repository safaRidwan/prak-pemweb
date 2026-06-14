<?php
session_start();
require 'koneksi.php';

$errorMessage = '';

if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '' || $password === '') {
    $errorMessage = 'Username dan password wajib diisi.';
  } else {
    $stmt = $conn->prepare('SELECT id_user, nama, username, password, role FROM user WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && md5($password) === $user['password']) {
      $_SESSION['user_id'] = (int) $user['id_user'];
      $_SESSION['nama'] = $user['nama'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['role'] = $user['role'];

      header('Location: ' . (($user['role'] === 'admin') ? 'index.php' : 'index.php'));
      exit;
    }

    $errorMessage = 'Username atau password salah.';
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SeoDash Free Bootstrap Admin Template by Adminmart</title>
  <link rel="shortcut icon" type="image/png" href="SEODash/src/assets/images/logos/seodashlogo.png" />
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
                <a href="login.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="logo/logo_teks.png" alt="" width="180" height="120">
                </a>
                <p class="text-center">Absensi Karangtaruna</p>
                <?php if ($errorMessage !== ''): ?>
                  <div class="alert alert-danger py-2 mb-3" role="alert">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                  </div>
                <?php endif; ?>
                <form method="post" action="login.php">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" autocomplete="username" required>
                  </div>
                  <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" required>
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Sign In</button>
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Buat Akun?</p>
                    <a class="text-primary fw-bold ms-2" href="register.php">Buat Akun</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="SEODash/src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="SEODash/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>