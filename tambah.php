php
<?php
require_once "koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kode  = trim($_POST["kode_mobil"] ?? "");
    $nama  = trim($_POST["nama_mobil"] ?? "");
    $brand = trim($_POST["brand"] ?? "");
    $warna = trim($_POST["warna"] ?? "");
    $tipe  = trim($_POST["tipe"] ?? "");
    $harga = trim($_POST["harga"] ?? "");

    if ($kode === "" || $nama === "" || $brand === "" ||
        $warna === "" || $tipe === "" || $harga === "") {
        $error = "Semua kolom wajib diisi.";
    } elseif (!ctype_digit($harga) || (int)$harga <= 0) {
        $error = "Harga harus berupa angka lebih dari 0.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO mobil
            (kode_mobil, nama_mobil, brand, warna, tipe, harga)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $hargaInt = (int)$harga;
        $stmt->bind_param(
            "sssssi",
            $kode, $nama, $brand, $warna, $tipe, $hargaInt
        );

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = ($stmt->errno == 1062)
                ? "Kode mobil sudah digunakan."
                : "Gagal menambahkan data mobil.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Mobil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Tambah Mobil</h1>

    <?php if ($error !== ""): ?>
        <div class="alert"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Kode Mobil</label>
        <input type="text" name="kode_mobil"
               value="<?= htmlspecialchars($_POST['kode_mobil'] ?? ''); ?>"
               required>

        <label>Nama Mobil</label>
        <input type="text" name="nama_mobil"
               value="<?= htmlspecialchars($_POST['nama_mobil'] ?? ''); ?>"
               required>

        <label>Brand</label>
        <input type="text" name="brand"
               value="<?= htmlspecialchars($_POST['brand'] ?? ''); ?>"
               required>

        <label>Warna</label>
        <input type="text" name="warna"
               value="<?= htmlspecialchars($_POST['warna'] ?? ''); ?>"
               required>

        <label>Tipe</label>
        <input type="text" name="tipe"
               value="<?= htmlspecialchars($_POST['tipe'] ?? ''); ?>"
               required>

        <label>Harga</label>
        <input type="number" name="harga" min="1"
               value="<?= htmlspecialchars($_POST['harga'] ?? ''); ?>"
               required>

        <button type="submit" class="btn btn-tambah">Simpan</button>
        <a href="index.php" class="btn btn-kembali">Kembali</a>
    </form>
</div>
</body>
</html>