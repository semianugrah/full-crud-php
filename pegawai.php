<?php
session_start();

if (!isset($_SESSION["login"])) {
    echo "<script>
           alert('login dulu');
           document.location.href = 'login.php';
           </script>";
    exit;
}

if ($_SESSION["level"] != 1 and $_SESSION["level"] != 3) {
    echo "<script>
           alert('Perhatian anda tidak punya hak akses');
           document.location.href = 'crud-modal.php';
           </script>";
    exit;
}

$title = 'Daftar Pegawai';

include 'layout/header.php';

$data_mahasiswa = select("SELECT * FROM pegawai ORDER BY id_pegawai DESC");
?>
<div class="content-wrapper">
<section class="content">
              <div class="container mt-5">
    <h1><i class="fas fa-users"></i> Data Pegawai</h1>
    <hr>
    <table class="table table-bordered table-striped" id="example">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Jenis Kelamin</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>
<?php include 'layout/footer.php';