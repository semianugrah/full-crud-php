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

$title = 'Daftar Mahasiswa';

include 'layout/header.php';

$data_mahasiswa = select("SELECT * FROM mahasiswa ORDER BY id_mahasiswa DESC");

// Ambil data untuk grafik garis (jumlah mahasiswa per prodi)
$data_prodi = select("SELECT prodi, COUNT(*) as jumlah FROM mahasiswa GROUP BY prodi");
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-users"></i> Data Mahasiswa</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Mahasiswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Garis -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Grafik Jumlah Mahasiswa per Prodi</h3>
        </div>
        <div class="card-body">
            <canvas id="chartLine" height="100"></canvas>
        </div>
    </div>

    <!-- Data Mahasiswa Tabel -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tabel Data Mahasiswa</h3>
        </div>
        <div class="card-body">
            <hr>
            <a href="tambah-mahasiswa.php" class="btn btn-primary mb-1"><i class="fas fa-plus-circle"></i> Tambah</a>
            <a href="download-excel-mahasiswa.php" class="btn btn-success mb-1"><i class="fas fa-file-excel"></i> Download Excel</a>
            <a href="download-pdf-mahasiswa.php" class="btn btn-danger mb-1"><i class="fas fa-file-pdf"></i> Download PDF</a>
            <table id="serverside" class="table table-bordered table-striped">
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
                    <?php $no = 1; foreach ($data_mahasiswa as $mahasiswa) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $mahasiswa['nama'] ?></td>
                            <td><?= $mahasiswa['prodi'] ?></td>
                            <td><?= $mahasiswa['jk'] ?></td>
                            <td><?= $mahasiswa['telepon'] ?></td>
                            <td>
                                <a href="detail-mahasiswa.php?id=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                <a href="edit-mahasiswa.php?id=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="hapus-mahasiswa.php?id=<?= $mahasiswa['id_mahasiswa']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?');"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>

<!-- Tambahkan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script Grafik -->
<script>
    const ctx = document.getElementById('chartLine').getContext('2d');
    const chartLine = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($data_prodi, 'prodi')) ?>,
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: <?= json_encode(array_column($data_prodi, 'jumlah')) ?>,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.3)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Jumlah Mahasiswa Berdasarkan Prodi'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
