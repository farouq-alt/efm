<?php
include 'auth.php';
include 'db.php';

$id = $_GET['id']; // Ensure the 'id' is sanitized

$sql = "SELECT * FROM rubrique WHERE numdossier=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

echo "<table border='1'>";
echo "<tr><th>Num Rubrique</th><th>Nom Rubrique</th><th>Montant</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['numrubrique']}</td>";
    echo "<td>{$row['nom_rubrique']}</td>";
    echo "<td>{$row['montant_rubrique']}</td>";
    echo "</tr>";
}
echo "</table>";
?>
