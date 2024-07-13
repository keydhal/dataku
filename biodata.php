<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menampilkan Data Diri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .hamburger-menu {
            position: relative;
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
    </style>
</head>
<body>
    <div class="hamburger-menu">
        <div class="hamburger-icon" onclick="toggleMenu()">☰ Menu</div>
        <div id="menuPopup" class="menu-popup">
            <a href="branda.php">Branda</a>
            <a href="biodata.php">Biodata</a>
            <a href="story.php">Story</a>
            <a href="login.php">Logout</a>
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

    <?php
    session_start();

    // Informasi koneksi ke database
    $dbHost = "localhost";
    $dbUsername = "ahmad";
    $dbPassword = "ahmad212";
    $dbName = "dataku";

    // Membuat koneksi ke database
    $conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // Memeriksa koneksi
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }

    // Mengambil username dari session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Query untuk mengambil data diri berdasarkan username
        $query = "SELECT * FROM registrasi WHERE user_id = '$username'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            // Menampilkan data diri
            $row = $result->fetch_assoc();
            echo "<h2>Data Diri</h2>";
            echo "<p>Nama Lengkap: " . $row['full_name'] . "</p>";
            echo "<p>Nama Pengguna: " . $row['user_id'] . "</p>";
            echo "<p>Email: " . $row['email'] . "</p>";
            echo "<p>Alamat: " . $row['address'] . "</p>";
            echo "<p>Jenis Kelamin: " . $row['gender'] . "</p>";
            echo "<p>Tanggal Lahir: " . $row['date_of_birth'] . "</p>";
            echo "<p>Nomor Telepon: " . $row['phone_number'] . "</p>";
            echo "<a href='ubah_data.php' class='button'>Ubah Data Diri</a>";
        } else {
            echo "<p>Data diri tidak ditemukan.</p>";
        }
    } else {
        echo "<p>Anda belum login. Silakan <a href='login.php'>login</a> terlebih dahulu.</p>";
    }

    // Menutup koneksi
    $conn->close();
    ?>
</body>
</html>
