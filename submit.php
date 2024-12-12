<?php
// Konfigurasi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "pembelian";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses data form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $barang = $conn->real_escape_string($_POST['barang']);

    // Proses upload file
    $target_dir = "uploads/";
    $file_name = basename($_FILES["bukti"]["name"]);
    $target_file = $target_dir . $file_name;
    if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
        // Simpan data ke database
        $sql = "INSERT INTO transaksi (email, alamat, barang, bukti_transfer) VALUES ('$email', '$alamat', '$barang', '$target_file')";

        if ($conn->query($sql) === TRUE) {
            echo "Data berhasil disimpan!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Gagal mengupload bukti transfer.";
    }
}

$conn->close();
?>
