Note:

Jika belum membuat database, bisa dibuat dengan
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tanggal_registrasi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

Nama database bebas, bisa disesuaikan di file koneksi.php
