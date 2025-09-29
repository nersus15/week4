<?php
require_once 'koneksi.php';

// Get data user berdasarkan id
$id = $_GET['id'];
if(empty($id)) exit("Ilegal!!");

$user = null;
$sql = "SELECT nama, email FROM users WHERE id=$id";
$result = $koneksi->query($sql);

if($result->num_rows != 1){
    exit("Id salah");
}else{
    while($row = $result->fetch_assoc()){
        $user = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Registrasi</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>Update data user</h1>
    <form action="update_user.php" method="post" enctype="multipart/form-data" onsubmit="return validasiFormUpdate()">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-group">
            <label for="">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="<?= $user['nama'] ?>">
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" id="email" value="<?= $user['email'] ?>">
        </div>
        <div class="form-group">
            <label for="">Password (Isi jika ingin merubah pasword)</label>
            <input type="password" name="password" id="password">
        </div>
        <div class="form-group">
            <label for="">Photo Profile</label>
            <input type="file" name="gambar" id="gambar">
        </div>
        <button class="btn btn-success" type="submit">Simpan</button>
        <a class="btn btn-danger" href="tampil_data.php">Batal</a>
    </form>

    <script src="script.js"></script>
</body>
</html>