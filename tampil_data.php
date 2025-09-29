<?php
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengguna Terdaftar</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <h1>Data Pengguna Terdaftar</h1>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Tanggal Registrasi</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, nama, email, tanggal_registrasi, photo FROM users ORDER BY id ASC";
            $result = $koneksi->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . $row["tanggal_registrasi"] . "</td>";
                    if(!empty($row['photo'])){
                        echo "<td><img src='public/uploads/" . $row["photo"] . "'</img></td>";
                    }else{
                        echo "<td>Tidak Ada</td>";
                    }

                    /**
                     * JIKA ingin menggunakan xhr (asinkronus ketika delete maka gunakan kode berikut untuk tombol delete)
                     * <button onclick="hapusdata(event)" data-id="'. $row['id'] .'" class="btn btn-danger">Delete</button>
                     * ganti <a href="delete_user.php?id='. $row['id'] .'" class="btn btn-danger">Delete</a> dengan kode diatas
                     */
                    echo '<td> <a class="btn btn-warning" href="update.php?id='. $row['id'] .'">Update</a><a href="delete_user.php?id='. $row['id'] .'" class="btn btn-danger">Delete</a></td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data yang terdaftar</td></tr>";
            }
            $koneksi->close();
            ?>
        </tbody>
    </table>
    <br>
    <a href="index.html">Kembali ke Form Registrasi</a>

    <script src="script.js"></script>
</body>
</html>