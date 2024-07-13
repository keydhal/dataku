<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Diri</title>
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
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
    } else {
        echo "<p>Anda belum login. Silakan <a href='login.php'>login</a> terlebih dahulu.</p>";
        exit;
    }

    // Cek apakah sudah disubmit data dari form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Menangkap data dari form
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $gender = $_POST['gender'];
        $date_of_birth = $_POST['date_of_birth'];
        $phone_number = $_POST['phone_number'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Query untuk mengupdate data diri
        $updateSQL = "UPDATE registrasi SET 
                        full_name = '$full_name', 
                        email = '$email', 
                        address = '$address', 
                        gender = '$gender', 
                        date_of_birth = '$date_of_birth', 
                        phone_number = '$phone_number',
                        password = '$password' 
                      WHERE username = '$username'";

        if ($conn->query($updateSQL) === TRUE) {
            echo "<p>Data diri berhasil diubah.</p>";
        } else {
            echo "Error: " . $updateSQL . "<br>" . $conn->error;
        }
    }

    // Query untuk mengambil data diri berdasarkan username
    $query = "SELECT * FROM registrasi WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        // Menampilkan form dengan data diri
        $row = $result->fetch_assoc();
        echo '
        <h2>Ubah Data Diri</h2>
        <form method="POST" action="' . $_SERVER['PHP_SELF'] . '">
            <label for="full_name">Nama Lengkap:</label><br>
            <input type="text" id="full_name" name="full_name" value="' . $row['full_name'] . '" required><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="' . $row['email'] . '" required><br><br>
            
            <label for="address">Alamat:</label><br>
            <textarea id="address" name="address">' . $row['address'] . '</textarea><br><br>
            
            <label for="gender">Jenis Kelamin:</label><br>
            <input type="radio" id="laki-laki" name="gender" value="Laki-laki" ' . ($row['gender'] == 'Laki-laki' ? 'checked' : '') . ' required>
            <label for="laki-laki">Laki-laki</label><br>
            <input type="radio" id="perempuan" name="gender" value="Perempuan" ' . ($row['gender'] == 'Perempuan' ? 'checked' : '') . '>
            <label for="perempuan">Perempuan</label><br><br>
            
            <label for="date_of_birth">Tanggal Lahir:</label><br>
            <input type="date" id="date_of_birth" name="date_of_birth" value="' . $row['date_of_birth'] . '" required><br><br>
            
            <label for="phone_number">Nomor Telepon:</label><br>
            <input type="text" id="phone_number" name="phone_number" value="' . $row['phone_number'] . '"><br><br>
            
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            
            <input type="submit" class="button" value="SIMPAN">
        </form>';
    } else {
        echo "<p>Data diri tidak ditemukan.</p>";
    }

    // Menutup koneksi
    $conn->close();
    ?>
    <a href='biodata.php' class='button'>Kembali</a>
</body>
</html>
