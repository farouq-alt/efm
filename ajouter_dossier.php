<?php
include 'auth.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num = $_POST['numdossier'];
    $date = $_POST['datedepot'];
    $montant = $_POST['montant'];
    $traitement = $_POST['datetraitement'];
    $lien = $_POST['lien'];
    $matricule = $_POST['matricule'];
    $maladie = $_POST['maladie'];
    $total = $_POST['total'];

    if (!empty($num) && !empty($date) && !empty($montant)) {
        $sql = "INSERT INTO dossier VALUES ('$num', '$date', '$montant', '$traitement', '$lien', '$matricule', '$maladie', '$total')";
        mysqli_query($conn, $sql);
        echo "Dossier ajouté.";
    } else {
        echo "Tous les champs sont obligatoires.";
    }
}
?>

<form method="post">
    Numéro Dossier: <input type="number" name="numdossier" required><br>
    Date Dépôt: <input type="date" name="datedepot" required><br>
    Montant: <input type="number" name="montant"><br>
    Date Traitement: <input type="date" name="datetraitement"><br>
    Lien Maladie: <input type="text" name="lien"><br>
    Matricule: <input type="number" name="matricule"><br>
    Num Maladie: <input type="number" name="maladie"><br>
    Total Dossier: <input type="number" name="total"><br>
    <input type="submit" value="Ajouter">
</form>
