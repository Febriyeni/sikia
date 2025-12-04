<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<?php include "koneksi.php";
$nim = isset($_GET['nim']) ? $_GET['nim'] : '';
// Fetch record safely
$d = null;
if($nim !== ''){
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM mahasiswa WHERE nim = ?");
    mysqli_stmt_bind_param($stmt, 's', $nim);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $d = mysqli_fetch_assoc($res);
    if(!$d){
        echo "<script>alert('Data tidak ditemukan');location='index.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Parameter nim tidak diberikan');location='index.php';</script>";
    exit;
}
?>

<h2>EDIT DATA</h2>

<form method="post" enctype="multipart/form-data">
    <label for="nama">Nama:</label>
    <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($d['nama'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" required>
    
    <label for="prodi">Prodi:</label>
    <input type="text" id="prodi" name="prodi" value="<?= htmlspecialchars($d['prodi'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" required>
    
    <label for="alamat">Alamat:</label>
    <textarea id="alamat" name="alamat" rows="4" required><?= htmlspecialchars($d['alamat'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></textarea>
    
    <label for="gambar">Gambar Baru (Kosongkan jika tidak ingin mengubah):</label>
    <input type="file" id="gambar" name="gambar" accept="image/*">
    
    <?php if(!empty($d['gambar']) && file_exists('upload/' . $d['gambar'])): ?>
    <p style="color: #666;">Gambar saat ini:</p>
    <img src="upload/<?= htmlspecialchars($d['gambar'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" width="100" style="border-radius: 5px;">
    <?php endif; ?>
    
    <button name="update">Update</button>
    <a href="index.php" style="align-self: flex-start; color: #667eea; text-decoration: none; margin-top: 10px;">← Kembali</a>
</form>

<?php
if(isset($_POST['update'])){
    $nama = trim($_POST['nama']);
    $prodi = trim($_POST['prodi']);
    $alamat = trim($_POST['alamat']);

    // handle new upload
    $newImageName = null;
    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0){
        $check = getimagesize($_FILES['gambar']['tmp_name']);
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if($check !== false && in_array($ext, $allowed)){
            $newImageName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            if(move_uploaded_file($_FILES['gambar']['tmp_name'], 'upload/' . $newImageName)){
                // remove old image if exists
                if(!empty($d['gambar']) && file_exists('upload/' . $d['gambar'])){
                    @unlink('upload/' . $d['gambar']);
                }
            } else {
                echo "<script>alert('Gagal memindahkan file upload.');</script>";
                $newImageName = null;
            }
        } else {
            echo "<script>alert('File bukan gambar yang valid.');</script>";
            $newImageName = null;
        }
    }

    if($newImageName !== null){
        $stmt = mysqli_prepare($koneksi, "UPDATE mahasiswa SET nama = ?, prodi = ?, alamat = ?, gambar = ? WHERE nim = ?");
        mysqli_stmt_bind_param($stmt, 'sssss', $nama, $prodi, $alamat, $newImageName, $nim);
    } else {
        $stmt = mysqli_prepare($koneksi, "UPDATE mahasiswa SET nama = ?, prodi = ?, alamat = ? WHERE nim = ?");
        mysqli_stmt_bind_param($stmt, 'ssss', $nama, $prodi, $alamat, $nim);
    }
    if(mysqli_stmt_execute($stmt)){
        echo "<script>alert('Data terupdate');location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data.');</script>";
    }
}
?>
