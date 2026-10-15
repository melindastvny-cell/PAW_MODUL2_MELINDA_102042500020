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

$result = $stmt->get_result();
$mobil = $result->fetch_assoc();

if (!$mobil) {
    die("Data mobil tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Mobil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Detail Mobil</h1>

    <table class="detail">
        <tr>
            <td>Kode Mobil</td>
            <td><?= htmlspecialchars($mobil['kode_mobil']); ?></td>
        </tr>
        <tr>
            <td>Nama Mobil</td>
            <td><?= htmlspecialchars($mobil['nama_mobil']); ?></td>
        </tr>
        <tr>
            <td>Brand</td>
            <td><?= htmlspecialchars($mobil['brand']); ?></td>
        </tr>
        <tr>
            <td>Warna</td>
            <td><?= htmlspecialchars($mobil['warna']); ?></td>
        </tr>
        <tr>
            <td>Tipe</td>
            <td><?= htmlspecialchars($mobil['tipe']); ?></td>
        </tr>
        <tr>
            <td>Harga</td>
            <td>Rp <?= number_format($mobil['harga'], 0, ',', '.'); ?></td>
        </tr>
    </table>

    <br>
    <a href="index.php" class="btn btn-kembali">Kembali</a>
    <a href="edit.php?id=<?= $mobil['id']; ?>" class="btn btn-edit">Edit</a>
</div>
</body>
</html>