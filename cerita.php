<?php
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

// Ambil user_id dari parameter URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query untuk mengambil karya dari user_id yang dipilih
    $sql = "SELECT id, judul, deskripsi FROM karya WHERE user_id = $user_id ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        echo "Error: " . $conn->error;
    }

    if ($result->num_rows > 0) {
        // Ambil nama pengguna dari database
        $sql_user = "SELECT full_name FROM registrasi WHERE id = $user_id";
        $result_user = $conn->query($sql_user);
        $user_name = ($result_user && $result_user->num_rows > 0) ? $result_user->fetch_assoc()['full_name'] : '';

        echo "<a href='branda.php' style='position: absolute; top: 10px; left: 10px;'>&larr; Kembali ke Beranda</a>";
        echo "<h1>Profil : $user_name</h1>";
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $judul = $row['judul'];
            $deskripsi = $row['deskripsi'];
            $karya_id = $row['id'];

            // Tampilkan judul sebagai tautan ke halaman baca.php dengan parameter id
            echo "<li>";
            echo "<a href='baca.php?id={$karya_id}'>$judul</a>";
            echo "<br>";
            echo $deskripsi;
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<a href='branda.php' style='position: absolute; top: 10px; left: 10px;'>&larr; Kembali ke Beranda</a>";
        echo "<h1>Belum ada cerita dari pengguna ini.</h1>";
    }

    $conn->close();
} else {
    echo "User ID tidak tersedia.";
}
?>
