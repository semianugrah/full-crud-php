<?php
session_start();

if (!isset($_SESSION["login"])) {
    echo "<script>
           alert('login dulu');
           document.location.href = 'login.php';
           </script>";
    exit;
}

if ($_SESSION["level"] != 1 and $_SESSION["level"] != 2) {
    echo "<script>
           alert('Perhatian anda tidak punya hak akses');
           document.location.href = 'crud-modal.php';
           </script>";
    exit;
}

$title = 'Daftar Barang';

include 'layout/header.php';

// Ambil data filter atau semua data
if (isset($_POST['filter'])) {
    $tgl_awal = strip_tags($_POST['tgl_awal'] . " 00:00:00");
    $tgl_akhir = strip_tags($_POST['tgl_akhir'] . " 23:59:59");
    $data_barang = select("SELECT * FROM barang WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' ORDER BY id_barang DESC");
} else {
    $jumlahDataPerhalaman = 5;
    $jumlahData = count(select("SELECT * FROM barang"));
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman);
    $halamanAktif = (isset($_GET['halaman']) ? $_GET['halaman'] : 1);
    $awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;
    $data_barang = select("SELECT * FROM barang ORDER BY id_barang DESC LIMIT $awalData, $jumlahDataPerhalaman");
}

// Data untuk grafik (ambil 10 data terakhir)
$barangChart = select("SELECT nama, jumlah FROM barang ORDER BY id_barang DESC LIMIT 10");
$labels = [];
$jumlah = [];

foreach ($barangChart as $row) {
    $labels[] = $row['nama'];
    $jumlah[] = $row['jumlah'];
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-list-ul"></i> Data Barang</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">


            <!-- Grafik -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Grafik Jumlah Barang</h3>
                </div>
                <div class="card-body">
                    <canvas id="barangChart"></canvas>
                </div>
            </div>

            <!-- Tabel barang -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tabel Data Barang</h3>
                </div>

                <div class="card-body">
                    <!-- Tombol aksi -->
                    <div class="mb-3">
                        <a href="tambah-barang.php" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i>
                            Tambah</a>
                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                            data-target="#modalFilter">
                            <i class="fas fa-search"></i> Filter Data
                        </button>
                    </div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Barcode</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = $awalData + 1; ?>
                            <?php foreach ($data_barang as $barang): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $barang['nama']; ?></td>
                                    <td><?= $barang['jumlah']; ?></td>
                                    <td>Rp. <?= number_format($barang['harga'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <img alt="barcode"
                                            src="barcode.php?codetype=Code128&size=15&text=<?= $barang['barcode']; ?>&print=true" />
                                    </td>
                                    <td><?= date("d/m/Y | H:i:s", strtotime($barang['tanggal'])); ?></td>
                                    <td class="text-center">
                                        <a href="ubah-barang.php?id_barang=<?= $barang['id_barang']; ?>"
                                            class="btn btn-success btn-sm"><i class="fas fa-edit"></i> Ubah</a>
                                        <a href="hapus-barang.php?id_barang=<?= $barang['id_barang']; ?>"
                                            class="btn btn-danger btn-sm" onclick="return confirm('Yakin?');"><i
                                                class="fas fa-trash-alt"></i> Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <nav class="mt-3">
                        <ul class="pagination justify-content-end">
                            <?php if ($halamanAktif > 1): ?>
                                <li class="page-item"><a class="page-link"
                                        href="?halaman=<?= $halamanAktif - 1 ?>">&laquo;</a></li>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                                <li class="page-item <?= ($i == $halamanAktif) ? 'active' : '' ?>">
                                    <a class="page-link" href="?halaman=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <?php if ($halamanAktif < $jumlahHalaman): ?>
                                <li class="page-item"><a class="page-link"
                                        href="?halaman=<?= $halamanAktif + 1 ?>">&raquo;</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title"><i class="fas fa-search"></i> Filter Data</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tgl_awal">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_akhir">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="filter" class="btn btn-success btn-sm">Tampilkan</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('barangChart').getContext('2d');
    const barangChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Jumlah Barang',
                data: <?= json_encode($jumlah); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Jumlah' }
                },
                x: {
                    title: { display: true, text: 'Nama Barang' }
                }
            }
        }
    });
</script>