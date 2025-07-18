<?php

session_start();

if (!isset($_SESSION["login"])) {
    echo "<script>
           alert('login dulu');
           document.location.href = 'login.php';
           </script>";
    exit;
}

$title = 'Tambah Mahasiswa';

include 'layout/header.php';

if (isset($_POST['tambah'])) {
    if (create_mahasiswa($_POST) > 0) {
        echo "<script>
                    alert('Data Mahasiswa Berhasil Ditambahkan');
                    document.location.href = 'mahasiswa.php'; 
                  </script>";
    } else {
        echo "<script>
                    alert('Data Mahasiswa Gagal Ditambahkan');
                    document.location.href = 'mahasiswa.php';
                  </script>";
    }
}
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-users"></i> Tambah Mahasiswa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Tambah Mahasiswa</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
        <hr>
        <section class="content">
        <div class="container-fluid">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Mahasiswa</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Mahasiswa...." required>
            </div>
            <div class="row">
                <div class="nb-3 col-6">
                    <label for="prodi" class="form-label">Program Studi</label>
                    <select name="prodi" id="prodi" class="form-control" required>
                        <option value="">-- Pilih Prodi</option>
                        <option value="Teknik Informatika">Teknik Informatika</option>
                        <option value="Teknik Mesin">Teknik Mesin</option>
                        <option value="Teknik Listrik">Teknik Listrik</option>
                    </select>
                </div>
                <div class="nb-3 col-6">
                    <label for="jk" class="form-label">Jenis Kelamin</label>
                    <select name="jk" id="jk" class="form-control" required>
                        <option value="">-- Pilih Jenis Kelamin</option>
                        <option value="laki-laki">Laki-laki</option>
                        <option value="perempuan">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="telepon" class="form-label">Telepon</label>
                <input type="number" class="form-control" id="telepon" name="telepon" placeholder="Telepon...."
                    required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea name="alamat" id="alamat"></textarea>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email...." required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input type="file" class="form-control" id="foto" name="foto" placeholder="Foto...."
                    onchange="previewImg()">

                <img src="assets/img/<?= $mahasiswa['foto']; ?>" alt="" class="img-thumbnail img-preview mt-2"
                    width="100px">
            </div>

            <button type="submit" name="tambah" class="btn btn-primary" style="float: right;">Tambah</button>
        </form>
    </div>
</div>

<script>
    function previewImg() {
        const foto = document.querySelector('#foto');
        const imgPreview = document.querySelector('.img-preview');

        const fileFoto = new FileReader();
        fileFoto.readAsDataURL(foto.files[0]);

        fileFoto.onload = function (e) {
            imgPreview.src = e.target.result;
        }
    }
</script>

<?php include 'layout/footer.php'; ?>