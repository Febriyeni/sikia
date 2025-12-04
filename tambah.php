<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<?php include "koneksi.php"; ?>

<h2>TAMBAH DATA</h2>
<form method="post" enctype="multipart/form-data">
    <label for="nim">NIM:</label>
    <input type="text" id="nim" name="nim" required>
    
    <label for="nama">Nama:</label>
    <input type="text" id="nama" name="nama" required>
    
    <label for="prodi">Prodi:</label>
    <input type="text" id="prodi" name="prodi" required>
    
    <label for="alamat">Alamat:</label>
    <textarea id="alamat" name="alamat" rows="4" required></textarea>
    
    <label for="gambar">Gambar:</label>
    <input type="file" id="gambar" name="gambar" accept="image/*" required>
    
    <button name="simpan">Simpan</button>
    <a href="index.php" style="align-self: flex-start; color: #667eea; text-decoration: none; margin-top: 10px;">← Kembali</a>
</form>

<?php
if(isset($_POST['simpan'])){
    $nim = trim($_POST['nim']);
    $nama = trim($_POST['nama']);
    $prodi = trim($_POST['prodi']);
    $alamat = trim($_POST['alamat']);

    $uploadedName = null;

    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0){
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if($check !== false && in_array($ext, $allowed)){
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            if(move_uploaded_file($_FILES['gambar']['tmp_name'], 'upload/' . $newName)){
                $uploadedName = $newName;
            } else {
                echo "<script>alert('Gagal memindahkan file upload.');</script>";
            }
        } else {
            echo "<script>alert('File bukan gambar atau ekstensi tidak diperbolehkan.');</script>";
        }
    }

    $stmt = mysqli_prepare($koneksi, "INSERT INTO mahasiswa (nim,nama,prodi,alamat,gambar) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'sssss', $nim, $nama, $prodi, $alamat, $uploadedName);
    if(mysqli_stmt_execute($stmt)){
        echo "<script>alert('Data tersimpan');location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data.');</script>";
    }
}
?>
</div>
</body>
</html>
