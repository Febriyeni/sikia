<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<?php include "koneksi.php"; ?>
<h2>DATA MAHASISWA</h2>
<a href="tambah.php" class="btn-tambah">+ Tambah Data</a>
<table border="1" cellpadding="10">
    <tr>
        <th>NIM</th><th>Nama</th><th>Prodi</th><th>Alamat</th><th>Gambar</th><th>Aksi</th>
    </tr>

<?php
// Fetch all students
$result = mysqli_query($koneksi, "SELECT * FROM mahasiswa");
while($d = mysqli_fetch_assoc($result)){
    $nim = htmlspecialchars($d['nim'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $nama = htmlspecialchars($d['nama'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $prodi = htmlspecialchars($d['prodi'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $alamat = htmlspecialchars($d['alamat'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $gambar = htmlspecialchars($d['gambar'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $imgPath = 'upload/' . $gambar;
    $imgTag = '';
    if(!empty($gambar) && file_exists($imgPath)){
        $imgTag = "<img src=\"". $imgPath ."\" width=\"60\">";
    }
?>
    <tr>
        <td><?= $nim ?></td>
        <td><?= $nama ?></td>
        <td><?= $prodi ?></td>
        <td><?= $alamat ?></td>
        <td><?= $imgTag ?></td>
        <td>
            <a href="edit.php?nim=<?= urlencode($nim) ?>" class="action-link edit">Edit</a> |
            <a href="hapus.php?nim=<?= urlencode($nim) ?>" class="action-link delete" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
        </td>
    </tr>
<?php } ?>
</table>
</div>
</body>
</html>
