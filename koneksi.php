<?php
$db_host = 'mysql';
$db_user = 'dev';
$db_pass = 'devpassword';
$db_name = 'db_pertemuan4';

$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}
?>