<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dataku";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil semua data dari tabel uploaded_files
$sql = "SELECT file_name, file_type, file_size FROM uploaded_files";
$result = $conn->query($sql);

// Memeriksa apakah ada hasil yang ditemukan
if ($result->num_rows > 0) {
    // Output data dari setiap baris
    echo "<h2>Data Hasil Upload</h2>";
    echo "<table border='1'>";
    echo "<tr><th>File Name</th><th>File Type</th><th>File Size</th></tr>";
    while ($row = $result->fetch_assoc()) {
        // Tautan untuk membuka file
        $fileLink = "uploads/" . $row["file_name"]; // Sesuaikan dengan direktori penyimpanan file Anda
        echo "<tr><td><a href='$fileLink' target='_blank'>" . $row["file_name"] . "</a></td><td>" . $row["file_type"] . "</td><td>" . $row["file_size"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data yang ditemukan.";
}

$conn->close();
?>
