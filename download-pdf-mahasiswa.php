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

require __DIR__ . '/vendor/autoload.php';
require 'config/app.php';

use Spipu\Html2Pdf\Html2Pdf;

$data_barang = select("SELECT * FROM mahasiswa");

$content .= '<style type="text/css">
    .gambar {
        width: 50px;
}
        </style>';

$content .= '
<page>
    <table border="1" align="center">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Program Studi</th>
            <th>Jenis Kelamin</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Foto</th>
        </tr>';
        
        $no=1;
        foreach ($data_barang as $barang) {
            $content .= '
                <tr>
                    <td>.'.$no++.'</td>
                    <td>'.$barang['nama'].'</td>
                    <td>'.$barang['prodi'].'</td>
                    <td>'.$barang['jk'].'</td>
                    <td>'.$barang['telepon'].'</td>
                    <td>'.$barang['email'].'</td>
                    <td><img src="assets/img/'.$barang['foto'].'" class="gambar"></td>
                </tr>
            ';
        }
$content .= '
    </table>
</page>';

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($content);
ob_start();
$html2pdf->output('Laporan-mahasiswa.pdf');