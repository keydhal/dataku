<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Karya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .back-link {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php
// Memulai sesi jika belum dimulai
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "ahmad";
$password = "ahmad212";
$dbname = "dataku";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID karya dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil data karya berdasarkan ID
    $sql = "SELECT judul, deskripsi, cerita FROM karya WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data dari setiap baris
        while ($row = $result->fetch_assoc()) {
            $judul = $row['judul'];
            $deskripsi = $row['deskripsi'];
            $cerita = $row['cerita'];

            echo "<h1>$judul</h1>";
            echo "<p>$deskripsi</p>";
            echo "<p>$cerita</p>";
        }
    } else {
        echo "Karya tidak ditemukan.";
    }
} else {
    echo "Parameter ID tidak ditemukan.";
}

$conn->close();
?>

<br>
<a class="back-link" href="branda.php">&lt; Kembali ke Beranda</a>

</body>
</html>
