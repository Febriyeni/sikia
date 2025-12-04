<?php
include "koneksi.php";
$nim = isset($_GET['nim']) ? $_GET['nim'] : '';
if($nim === ''){
	header("Location: index.php");
	exit;
}

// fetch gambar name to remove file
$stmt = mysqli_prepare($koneksi, "SELECT gambar FROM mahasiswa WHERE nim = ?");
mysqli_stmt_bind_param($stmt, 's', $nim);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if($row = mysqli_fetch_assoc($res)){
	if(!empty($row['gambar']) && file_exists('upload/' . $row['gambar'])){
		@unlink('upload/' . $row['gambar']);
	}
}

$del = mysqli_prepare($koneksi, "DELETE FROM mahasiswa WHERE nim = ?");
mysqli_stmt_bind_param($del, 's', $nim);
mysqli_stmt_execute($del);

header("Location: index.php");
exit;
?>
