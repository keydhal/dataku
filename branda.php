<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Karya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
            background-color: #f9f9f9; /* Background color */
        }
        .container {
            max-width: 800px; /* Max width of content */
            margin: 50px auto; /* Center the container */
            padding: 0 20px; /* Padding inside container */
        }
        .hamburger-menu {
            position: absolute; /* Position absolutely */
            top: 10px; /* Adjust top position */
            left: 10px; /* Adjust left position */
            display: inline-block;
            z-index: 2; /* Ensure menu is on top of content */
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
        ul {
            list-style-type: none; /* Remove list bullets */
            padding: 0; /* Remove default padding */
        }
        li {
            margin-bottom: 20px; /* Space between list items */
            border-bottom: 1px solid #ddd; /* Add border bottom */
            padding-bottom: 20px; /* Add padding below each item */
        }
        li:last-child {
            border-bottom: none; /* Remove border bottom for last item */
        }
        h1 {
            text-align: center; /* Center align the heading */
        }
        h2 {
            font-size: 20px; /* Title font size */
            margin-bottom: 5px; /* Space below title */
        }
        .author {
            font-size: 16px; /* Author info font size */
            color: #888; /* Author info color */
            margin-bottom: 10px; /* Space below author info */
        }
        p {
            margin: 0; /* Remove default margin */
        }
    </style>
</head>
<body>
    <div class="hamburger-menu">
        <div class="hamburger-icon" onclick="toggleMenu()">☰ Menu</div>
        <div id="menuPopup" class="menu-popup">
            <a href="branda.php">Beranda</a>
            <a href="profil.php">PROFIL</a>
            <a href="karya.php">POSTING</a>
            <form action="login.php" method="POST" style="margin: 0;">
                <button type="submit" class="logout-button">Logout</button>
            </form>
            <a href="https://chatgpt.com/c/f12479ac-8cd2-43af-ab66-6915fb914e22">Chat GPT</a>
                        <a href="https://gemini.google.com/app">Gemini</a>
            <a href="hapus_akun.php" onclick="return confirm('Apakah Anda yakin ingin menghapus akun Anda?')">Hapus Akun</a>
        </div>
    </div>

    <div class="container">
        <?php
        session_start();

        // Koneksi ke database
        $servername = "localhost";
        $username = "ahmad";
        $password = "ahmad212";
        $dbname = "dataku";

        // Membuat koneksi
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Memeriksa koneksi
        if ($conn->connect_error) {
            die("Koneksi gagal: " . $conn->connect_error);
        }

        // Query untuk mengambil data karya beserta informasi penulis
        $sql = "SELECT k.id, k.judul, k.deskripsi, k.user_id, r.full_name 
                FROM karya k 
                LEFT JOIN registrasi r ON k.user_id = r.id 
                ORDER BY k.id DESC";
        $result = $conn->query($sql);

        // Memeriksa hasil query
        if ($result === false) {
            echo "Error: " . $conn->error;
        } else {
            // Memeriksa apakah ada data yang ditemukan
            if ($result->num_rows > 0) {
                echo "<h1>Daftar Karya</h1>";
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    $judul = htmlspecialchars($row['judul']); // Menghindari XSS dengan htmlspecialchars
                    $deskripsi = htmlspecialchars($row['deskripsi']);
                    $user_id = $row['user_id']; // Ambil user_id dari hasil query
                    $full_name = htmlspecialchars($row['full_name']); // Jika Anda juga ingin menampilkan nama lengkap

                    // Tampilkan judul sebagai tautan ke halaman baca.php dengan parameter id
                    echo "<li>";
                    echo "<a href='baca.php?id={$row['id']}'><h2>$judul</h2></a>";

                    // Tampilkan user_id sebagai tautan ke halaman profil.php atau cerita.php
                    if (!empty($user_id)) {
                        echo "<p class='author'>by: ";
                        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
                            echo "<a href='profil.php?user_id={$row['user_id']}'>$full_name</a></p>";
                        } else {
                            echo "<a href='cerita.php?user_id={$row['user_id']}'>$full_name</a></p>";
                        }
                    }
                    echo "<p>$deskripsi</p>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<h1>Belum ada karya yang ditambahkan.</h1>";
            }
        }

        // Menutup koneksi database
        $conn->close();
        ?>
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
</body>
</html>
