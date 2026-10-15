php
<?php
require_once "koneksi.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("ID mobil tidak valid.");
}

$stmt = $conn->prepare("SELECT * FROM mobil WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$mobil = $stmt->get_result()->fetch_assoc();

if (!$mobil) {
    die("Data mobil tidak ditemukan.");
}

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
        $hargaInt = (int)$harga;

        $stmt = $conn->prepare(
            "UPDATE mobil
             SET kode_mobil=?, nama_mobil=?, brand=?,
                 warna=?, tipe=?, harga=?
             WHERE id=?"
        );

        $stmt->bind_param(
            "sssssii",
            $kode, $nama, $brand, $warna, $tipe, $hargaInt, $id
        );

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            $error = ($stmt->errno == 1062)
                ? "Kode mobil sudah digunakan."
                : "Gagal mengubah data mobil.";
        }

        $stmt->close();
    }

    $mobil = [
        "kode_mobil" => $kode,
        "nama_mobil" => $nama,
        "brand" => $brand,
        "warna" => $warna,
        "tipe" => $tipe,
        "harga" => $harga
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Mobil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Mobil</h1>

    <?php if ($error !== ""): ?>
        <div class="alert"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Kode Mobil</label>
        <input type="text" name="kode_mobil"
               value="<?= htmlspecialchars((string)$mobil['kode_mobil']); ?>"
               required>

        <label>Nama Mobil</label>
        <input type="text" name="nama_mobil"
               value="<?= htmlspecialchars((string)$mobil['nama_mobil']); ?>"
               required>

        <label>Brand</label>
        <input type="text" name="brand"
               value="<?= htmlspecialchars((string)$mobil['brand']); ?>"
               required>

        <label>Warna</label>
        <input type="text" name="warna"
               value="<?= htmlspecialchars((string)$mobil['warna']); ?>"
               required>

        <label>Tipe</label>
        <input type="text" name="tipe"
               value="<?= htmlspecialchars((string)$mobil['tipe']); ?>"
               required>

        <label>Harga</label>
        <input type="number" name="harga" min="1"
               value="<?= htmlspecialchars((string)$mobil['harga']); ?>"
               required>

        <button type="submit" class="btn btn-edit">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-kembali">Kembali</a>
    </form>
</div>
</body>
</html>