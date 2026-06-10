<?php
session_start();
include '../koneksi.php';

/* ==========================
   TAMBAH DATA
========================== */
if(isset($_POST['simpan'])){

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $query = mysqli_query($conn,"
        INSERT INTO user(nama,username,password,role)
        VALUES('$nama','$username','$password','$role')
    ");

    if($query){
        $_SESSION['pesan'] = '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data anggota berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }else{
        $_SESSION['pesan'] = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data anggota gagal ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    header("Location: data_anggota.php");
    exit;
}

/* ==========================
   EDIT DATA
========================== */
if(isset($_POST['update'])){

    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $query = mysqli_query($conn,"
        UPDATE user
        SET nama='$nama',
            username='$username',
            role='$role'
        WHERE id_user='$id'
    ");

    if($query){
        $_SESSION['pesan'] = '
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Data anggota berhasil diupdate!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    header("Location: data_anggota.php");
    exit;
}

/* ==========================
   HAPUS DATA
========================== */
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    $query = mysqli_query($conn,"
        DELETE FROM user
        WHERE id_user='$id'
    ");

    if($query){
        $_SESSION['pesan'] = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data anggota berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }

    header("Location: data_anggota.php");
    exit;
}
?>