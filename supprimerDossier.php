<?php
include("db.php");
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $conn->prepare("DELETE FROM Dossier WHERE numdossier = ?;");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Dossier supprimé avec succès');</script>";
        header("Location: miseAjourDossier.php");
        exit();
    } else {
        echo "<script>alert('Erreur lors de la suppression du dossier');</script>";
    }
} else {
    echo "<script>alert('Aucun dossier trouvé');</script>";
    header("Location: miseAjourDossier.php");
    exit();
}
?>