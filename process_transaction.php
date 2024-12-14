<?php
// Konfigurasi database
$host = "localhost";
$dbname = "handicraft_db";
$username = "root";
$password = "";

// Buat koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $address = $_POST['address'];
    $product = $_POST['product'];
    $upload_dir = 'uploads/';
    $file_name = "";

    // Buat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Proses unggah file
    if (isset($_FILES['transfer_photo']) && $_FILES['transfer_photo']['error'] == 0) {
        $file_name = time() . "_" . basename($_FILES['transfer_photo']['name']);
        $file_path = $upload_dir . $file_name;

        if (!move_uploaded_file($_FILES['transfer_photo']['tmp_name'], $file_path)) {
            echo "Gagal mengunggah file.";
            exit();
        }
    }

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO transactions (email, address, product, transfer_photo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $address, $product, $file_name);

    if ($stmt->execute()) {
        echo "Transaksi berhasil disimpan!";
        // Redirect ke halaman index.html
        header("Location: index.html");
        exit();
    } else {
        echo "Gagal menyimpan data: " . $stmt->error;
    }

    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
