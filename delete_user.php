<?php
// Jika menggunakan GET
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    delete_user($_GET['id']);
    exit;
}elseif($_SERVER['REQUEST_METHOD'] == 'POST'){ //jika menggunakan POST (XHR)
    // Baca raw JSON data dari request body
    $json_data = file_get_contents('php://input');

    // Decode JSON menjadi array/object PHP
    $data = json_decode($json_data, true); // true untuk array, false untuk object

    // Cek jika JSON valid
    if ($data === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }

    delete_user(isset($data['id']) ? $data['id'] : $_POST['id'], true);
    exit;
}

function delete_user($id, $json = false)
{
    require_once 'koneksi.php';
    // cek user apakah ada atau tidak
    $sql = "SELECT nama, photo FROM users WHERE id=$id";
    $user = $koneksi->query($sql)->fetch_object();

    if (empty($user)) {
        if (!$json) {
            echo "<h1>User tidak ditemukan!</h1>";
            echo '<a href="index.html">Kembali ke Form</a> | <a href="tampil_data.php">Lihat Data</a>';
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'User tidak ditemukan']);
        }
        return;
    }

    // Hapus user
    $sql = "DELETE FROM users WHERE id=?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {

        // Hapus file photo ketika berhasil menghapus data
        if(!empty($user->photo)){
            // Normalize path untuk konsistensi agar bisa di windows
            $upload_dir = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, 'public/uploads'); //public/uploads harus sama dengan di update_user.php $config['path'] (ini adalah folder tempat kalian menyimpan file photo)

            $photo = $upload_dir . DIRECTORY_SEPARATOR . $user->photo;
            if (file_exists($photo)) {
                @unlink($photo);
            }
        }

        if (!$json) {
            echo "<h1>Hapus Data Berhasil!</h1>";
            echo "<p>" . $user->nama . "! telah dihapus.</p>";
            echo '<a href="index.html">Kembali ke Form</a> | <a href="tampil_data.php">Lihat Data</a>';
        } else {
            http_response_code(200);
            echo json_encode(['message' => 'User '. $user->nama .' telah dihapus']);
        }
    }else{
        if (!$json) {
            echo "<h1>Terjadi kesalahan saat menghapus data!</h1>";
            echo '<a href="index.html">Kembali ke Form</a> | <a href="tampil_data.php">Lihat Data</a>';
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'terjadi kesalahan saat menghapus data']);
        }
    }
}
