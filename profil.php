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

// Ambil user_id dari sesi
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Query untuk mengambil informasi pengguna
    $sql_user = "SELECT user_id, full_name FROM registrasi WHERE id = $user_id";
    $result_user = $conn->query($sql_user);

    if ($result_user === false) {
        echo "Error: " . $conn->error;
    } elseif ($result_user->num_rows > 0) {
        $user_data = $result_user->fetch_assoc();
    } else {
        echo "User tidak ditemukan.";
    }

    // Query untuk mengambil karya pengguna
    $sql_karya = "SELECT id, judul, deskripsi FROM karya WHERE user_id = $user_id";
    $result_karya = $conn->query($sql_karya);

    if ($result_karya === false) {
        echo "Error: " . $conn->error;
    }
} else {
    echo "User ID tidak tersedia dalam sesi.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .back-arrow {
            display: inline-block;
            font-size: 24px;
            color: #3498db;
            cursor: pointer;
        }
        .back-arrow:hover {
            text-decoration: underline;
        }
        .edit-button {
            display: inline-block;
            font-size: 24px;
            color: #3498db;
            cursor: pointer;
            text-decoration: none;
        }
        .edit-button:hover {
            text-decoration: underline;
        }
        .profile-info {
            margin-bottom: 20px;
        }
        .profile-info h2 {
            margin: 0;
        }
        .profile-info p {
            margin: 5px 0;
        }
        .works-list {
            list-style-type: none;
            padding-left: 0;
        }
        .works-list li {
            margin-bottom: 10px;
        }
        .works-list a {
            text-decoration: none;
            color: #3498db;
        }
        .works-list a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="back-arrow" onclick="window.location.href='branda.php'">&larr; Kembali ke Beranda</div>
        <a class="edit-button" href="edit.php">&#9998;</a>
    </div>
    
    <div class="profile-info">
        <h2>Profil Pengguna</h2>
        <?php if (isset($user_data)) { ?>
            <p><strong>User ID:</strong> <?php echo $user_data['user_id']; ?></p>
            <p><strong>Nama Lengkap:</strong> <?php echo $user_data['full_name']; ?></p>
        <?php } ?>
    </div>

    <div class="user-works">
        <h2>Karya Anda</h2>
        <ul class="works-list">
            <?php
            if ($result_karya->num_rows > 0) {
                while ($row = $result_karya->fetch_assoc()) {
                    echo "<li>";
                    echo "<a href='baca.php?id={$row['id']}'>{$row['judul']}</a>";
                    echo "<br>";
                    echo "<span style='font-size: 14px; color: #888;'>{$row['deskripsi']}</span>";
                    echo "</li>";
                }
            } else {
                echo "<li>Belum ada karya yang ditambahkan.</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>
<?php
$conn->close();
?>
