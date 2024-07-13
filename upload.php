<?php
session_start();

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

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $fileName = $_FILES['file']['name'];
    $fileType = $_FILES['file']['type'];
    $fileSize = $_FILES['file']['size'];
    $fileTmpName = $_FILES['file']['tmp_name'];

    // Membaca konten file
    $fileContent = file_get_contents($fileTmpName);

    // Menggunakan prepared statement untuk menyimpan file ke database
    $stmt = $conn->prepare("INSERT INTO uploaded_files (file_name, file_type, file_size, file_content) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $fileName, $fileType, $fileSize, $fileContent);
    
    if ($stmt->execute()) {
        $message = "File berhasil diunggah dan disimpan ke database.";
    } else {
        $message = "Terjadi kesalahan saat mengunggah file: " . $stmt->error;
    }

    $stmt->close();
} else {
    $message = "Tidak ada file yang diunggah.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
</head>
<body>
    <h1>Upload File</h1>
    <?php
    if (!empty($message)) {
        echo "<p>$message</p>";
    }
    ?>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Pilih file untuk diunggah:</label>
        <input type="file" name="file" id="file" 
