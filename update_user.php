<?php
// Import koneksi.php
require_once 'koneksi.php';
// Cek http method, pastikan menggunakan post
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<h1> Akses halaman ini hanya dengan method POST!</h1>";
    exit;
}

// Ambil id
$id = $_POST['id'];

// Ambil data POST, berikan data default null jika tidak dikirimkan
$nama = isset($_POST['nama']) ? $_POST['nama'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

// hanya update password jika ada isinya
if (!empty($password)) {
    // Hash password sebelum dikirim ke database (untuk keamanan)
    $password = password_hash($password, PASSWORD_DEFAULT);
}

// Pastikan nama dan email tidak kosong, jangan percaya data dari FRONTEND!!
if (empty($nama) || empty($email)) {
    echo "Nama dan Email tidak boleh kosong";
    exit;
}

// Masukkan data sisanya
$nama = htmlspecialchars($nama);
$email = htmlspecialchars($email);

// sql query
$query = "UPDATE users SET nama=?, email=?";

if (!empty($password)) {
    $query .= ", password=?";
}

// Tambahkan WHERE
$query .= " WHERE id=?";

$stmt = $koneksi->prepare($query);

// bind, untuk bind pastikan berurutan type, array input dan kolom di query, 
// dan pastikan type nya sesuai, s untuk string, i untuk integer;
if (!empty($password)) {
    $stmt->bind_param('sssi', $nama, $email, $password, $id);
} else {
    $stmt->bind_param('ssi', $nama, $email, $id);
}

if ($stmt->execute()) {
    $stmt->close();
    // Jika berhasil
    // ambil data photo user sebelumnya, supaya file tersebut dihapus, supaya file tidak bertumpuk
    $sql = "SELECT photo, nama FROM users WHERE id=$id";
    $user = $koneksi->query($sql)->fetch_object();

    // Cek apakah ada gambar
    if (isset($_FILES['gambar'])) {

        /** 
         * config disini akan override config yang ada di uploader 
         * contoh, di config uploader allowed_file adalah ['jpg', 'jpeg', 'png'] tapi disini kita masukkan ['jpg', 'jpeg', 'png', 'webp'] sehingga file webp bisa di upload
         * contoh lain adalah path, default nya adalah public, tapi kita override dengan public/uploads
        */

        $config = [
            'max_size' => 2000000,
            'allowed_file' => ['jpg', 'jpeg', 'png', 'webp'],
            'path' => 'public/uploads',
            'old' => $user->photo, //ini adalah photo lama, akan dihapus jika ada,
            'filename' => null // jika ingin nama file photo tidak random bisa diisi dengan nama atau id user, contoh: 'filename' => $id
        ];
        $file = $_FILES['gambar'];

        $filename = uploader($file, $config);
        $query = "UPDATE users SET photo=? WHERE id=?";

        $stmt = $koneksi->prepare($query);
        $stmt->bind_param('si', $filename, $id);

        $stmt->execute();
        $stmt->close();
    }
    echo "<h1>Update Data Berhasil!</h1>";
    echo "<p>" . $user->nama . "! telah di update.</p>";
    echo '<a href="index.html">Kembali ke Form</a> | <a href="tampil_data.php">Lihat Data</a>';
    exit;
} else {
    echo "Gagal";
    $stmt->close();
}



function uploader($file, $config = [])
{
    $filename = null;
    // default config
    $config = array_merge([
        // Ini adalah default config untuk uploader
        'max_size' => 2000, //byte
        'path' => 'public/',
        'filename' => null,
        'allowed_file' => ['jpg', 'jpeg', 'png'],
        'old_file' => null
    ], $config);

    // ambil metadata dari gambar/file
    $name = $file['name'];
    $fileSize = $file['size'];
    $tmp = $file['tmp_name'];
    $ext = pathinfo($name, PATHINFO_EXTENSION);

    // cek ext
    if (!in_array($ext, $config['allowed_file'])) {
        exit("Hanya boleh upload file " . join('/', $config['allowed_file']));
    }

    // cek ukuran file
    if ($fileSize <= 0 || (!empty($config['max_size']) && $fileSize > $config['max_size'])) {
        exit("Hanya boleh upload file dengan ukuran maksimal " . round($config['max_size'] / 1000) . 'MB');
    }

    // buatkan filename jika tidak di definisikan di config
    if (empty($config['filename'])) {
        $randomstring = substr(bin2hex(random_bytes(ceil(8 / 2))), 0, 8);
        $filename = $randomstring . '.' . $ext;
    } else {
        $filename = $config['filename'] . '.' . $ext;
    }

   // === Tentukan path tujuan ===
    $upload_dir = rtrim($config['path'], DIRECTORY_SEPARATOR);

    // Normalize path untuk konsistensi agar bisa di windows
    $upload_dir = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $upload_dir);

    // Buat directory jika belum ada (recursive)
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) { // true untuk recursive creation
            exit('Gagal membuat directory: ');
            return;
        }
    }

    // Pastikan directory writable
    if (!is_writable($upload_dir)) {
        exit('Directory tidak writable: ');
        return;
    }
    $path_dest = $upload_dir . DIRECTORY_SEPARATOR . $filename;

    // DO: upload
    // Pindahkan file upload
    if (!move_uploaded_file($tmp, $path_dest)) {
       exit('Gagal upload file');
    }

    // Hapus file lama jika ada
    if (!empty($config['old'])) {
        $old = $upload_dir . DIRECTORY_SEPARATOR . $config['old'];
        if (file_exists($old)) {
            @unlink($old);
        }
    }

    return $filename;
}
