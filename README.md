Note:
Jika belum membuat tabel user, bisa dibuat dengan

```SQL
    CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    photo VARCHAR(15) NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    tanggal_registrasi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Jika sudah ada tabel users, tapi belum ada kolom photo bisa dibuat dengan
```SQL
    ALTER TABLE users ADD COLUMN photo VARCHAR(15) NULL DEFAULT NULL AFTER email;
```

Nama database bebas, bisa disesuaikan di file koneksi.php
