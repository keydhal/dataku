<?php
session_start();

// Koneksi ke database
$dbHost = "localhost";
$dbUsername = "ahmad";
$dbPassword = "ahmad212";
$dbName = "dataku";

$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Koneksi database gagal: " . $db->connect_error);
}

// Memproses data form login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Memeriksa apakah 'username_or_email' dan 'password' sudah diset dan tidak kosong
    $usernameOrEmail = isset($_POST['username_or_email']) ? $_POST['username_or_email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($usernameOrEmail) && !empty($password)) {
        // Mengamankan input email atau user_id dari SQL Injection
        $usernameOrEmail = $db->real_escape_string($usernameOrEmail);

        // Query mencari data pengguna berdasarkan user_id atau email
        $sql = "SELECT id, user_id, password FROM registrasi WHERE user_id = '$usernameOrEmail' OR email = '$usernameOrEmail'";
        $result = $db->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $hashedPassword = $row['password'];
                if (password_verify($password, $hashedPassword)) {
                    // Login berhasil
                    $_SESSION['username'] = $row['user_id'];
                    $_SESSION['user_id'] = $row['id'];

                    $db->close(); // Menutup koneksi database

                    // Alihkan ke halaman beranda.php setelah login berhasil
                    header("Location: branda.php");
                    exit();
                } else {
                    $error_message = "User ID atau password salah.";
                }
            } else {
                $error_message = "Pengguna dengan user ID atau email tersebut tidak ditemukan.";
            }
        } else {
            $error_message = "Terjadi kesalahan dalam eksekusi query.";
        }
    } else {
        $error_message = "Mohon lengkapi formulir login.";
    }
}

// Menutup koneksi database jika tidak ada proses login
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .login-box {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }
        .login-box input[type="submit"] {
            width: 100%;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 3px;
        }
        .login-box input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="username_or_email" placeholder="User ID atau Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>
        <?php
        // Menampilkan pesan error jika ada
        if (isset($error_message)) {
            echo '<div class="error-message">' . $error_message . '</div>';
        }
        ?>
    </div>
    <p>belum punya akun?</p>
    
    <a href="register.php">DAFTAR SEKARANG</a>
</body>
</html>
