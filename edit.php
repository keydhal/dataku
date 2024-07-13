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

    // Jika form dikirimkan untuk update atau delete
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['update'])) {
            $karya_id = $_POST['karya_id'];
            $judul = $_POST['judul'];
            $deskripsi = $_POST['deskripsi'];
            $cerita = $_POST['cerita'];

            // Query untuk update karya
            $sql_update = "UPDATE karya SET judul='$judul', deskripsi='$deskripsi', cerita='$cerita' WHERE id=$karya_id AND user_id=$user_id";
            
            if ($conn->query($sql_update) === TRUE) {
                echo "Data karya berhasil diupdate.";
                header("Location: branda.php"); // Redirect ke halaman beranda setelah update
                exit(); // Penting: pastikan untuk keluar dari script setelah redirect
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } elseif (isset($_POST['delete'])) {
            $karya_id = $_POST['karya_id'];

            // Query untuk delete karya
            $sql_delete = "DELETE FROM karya WHERE id=$karya_id AND user_id=$user_id";
            
            if ($conn->query($sql_delete) === TRUE) {
                echo "Data karya berhasil dihapus.";
                header("Location: branda.php"); // Redirect ke halaman beranda setelah delete
                exit(); // Penting: pastikan untuk keluar dari script setelah redirect
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }
    }

    // Query untuk mengambil karya pengguna setelah proses update/delete
    $sql_karya = "SELECT id, judul, deskripsi, cerita FROM karya WHERE user_id = $user_id";
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
    <title>Edit Karya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .works-list {
            list-style-type: none;
            padding-left: 0;
        }
        .works-list li {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .works-list a {
            text-decoration: none;
            color: #3498db;
        }
        .works-list a:hover {
            text-decoration: underline;
        }
        .edit-form {
            margin-top: 10px;
        }
        .edit-form input[type="text"], .edit-form textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        .edit-form button {
            padding: 10px 15px;
            background-color: #3498db;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .edit-form button:hover {
            background-color: #2980b9;
        }
        .delete-button {
            background-color: #e74c3c;
            margin-left: 10px;
        }
        .delete-button:hover {
            background-color: #c0392b;
        }
        .back-arrow {
            display: inline-block;
            font-size: 24px;
            color: #3498db;
            cursor: pointer;
            margin-right: 20px;
        }
        .back-arrow:hover {
            text-decoration: underline;
        }
        .add-section {
            display: inline-block;
            font-size: 24px;
            color: #3498db;
            cursor: pointer;
            text-decoration: none;
            margin-left: 10px;
        }
        .add-section:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div>
        <div class="back-arrow" onclick="window.location.href='profil.php'">&larr; Kembali ke Profil</div>
    </div>
    
    <h2>Edit Karya Anda</h2>
    <ul class="works-list">
        <?php
        if (isset($result_karya) && $result_karya->num_rows > 0) {
            while ($row = $result_karya->fetch_assoc()) {
                ?>
                <li>
                    <h3><?php echo $row['judul']; ?></h3>
                    <p><?php echo $row['deskripsi']; ?></p>
                    <form action="edit.php" method="post" class="edit-form">
                        <input type="hidden" name="karya_id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="judul" value="<?php echo $row['judul']; ?>" required>
                        <textarea name="deskripsi" rows="4" required><?php echo $row['deskripsi']; ?></textarea>
                        <textarea name="cerita" rows="8" required><?php echo $row['cerita']; ?></textarea>
                        <button type="submit" name="update">Update</button>
                        <button type="submit" name="delete" class="delete-button">Delete</button>
                    </form>
                </li>
                <?php
            }
        } else {
            echo "<li>Belum ada karya yang ditambahkan.</li>";
        }
        ?>
    </ul>
    <a class="add-section" href="karya.php">+</a>
</body>
</html>
