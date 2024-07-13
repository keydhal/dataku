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

// Buat tabel jika belum ada
$sql = "CREATE TABLE IF NOT EXISTS karya (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    cerita TEXT,
    user_id INT NOT NULL
)";
if ($conn->query($sql) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

// Inisialisasi pesan
$message = "";

// Tangani form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $judul = $conn->real_escape_string($_POST['judul']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $cerita = $conn->real_escape_string($_POST['cerita']);
    
    // Ambil user_id dari sesi
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Query untuk menyimpan data ke database
        $sql = "INSERT INTO karya (judul, deskripsi, cerita, user_id) VALUES ('$judul', '$deskripsi', '$cerita', $user_id)";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Karya berhasil ditambahkan";
            header("Location: branda.php");
            exit();
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $message = "User ID tidak tersedia dalam sesi.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Karya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }
        .hamburger-menu {
            position: absolute; /* Position absolutely */
            top: 10px; /* Adjust top position */
            left: 10px; /* Adjust left position */
            display: inline-block;
        }
        .hamburger-icon {
            cursor: pointer;
            padding: 10px;
            background-color: #3498db;
            color: #ffffff;
            border-radius: 5px;
            font-size: 16px;
        }
        .menu-popup {
            display: none;
            position: absolute;
            top: 50px;
            right: 0;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            overflow: hidden;
        }
        .menu-popup a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .menu-popup a:hover {
            background-color: #f1f1f1;
        }
        .show {
            display: block;
        }
        .logout-button {
            width: 100%;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 12px 16px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 0;
            text-align: left;
        }
        .logout-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="hamburger-menu">
        <div class="hamburger-icon" onclick="toggleMenu()">☰ Menu</div>
        <div id="menuPopup" class="menu-popup">
            <a href="branda.php">Beranda</a>
            <a href="biodata.php">Biodata</a>
            <a href="karya.php">Karya</a>
            <form action="login.php" method="POST" style="margin: 0;">
                <button type="submit" class="logout-button">Logout</button>
            </form>
            <a href="https://chatgpt.com/c/f12479ac-8cd2-43af-ab66-6915fb914e22">Chat GPT</a>
            <a href="https://gemini.google.com/app">Gemini</a>
        </div>
    </div>

    <script>
        function toggleMenu() {
            var menuPopup = document.getElementById("menuPopup");
            menuPopup.classList.toggle("show");
        }

        // Menutup menu jika pengguna mengklik di luar menu
        window.onclick = function(event) {
            if (!event.target.matches('.hamburger-icon')) {
                var menuPopup = document.getElementById("menuPopup");
                if (menuPopup.classList.contains('show')) {
                    menuPopup.classList.remove('show');
                }
            }
        }
    </script>

    <h1>Upload Karya Baru</h1>

    <form method="post">
        <label for="judul">Judul:</label><br>
        <input type="text" id="judul" name="judul" required><br><br>
        
        <label for="deskripsi">Deskripsi:</label><br>
        <textarea id="deskripsi" name="deskripsi" required></textarea><br><br>
        
        <label for="cerita">Cerita:</label><br>
        <textarea id="cerita" name="cerita" required></textarea><br><br>
        
        <button type="submit">Upload</button>
    </form>

    <?php echo isset($message) ? $message : ''; ?>

</body>
</html>
