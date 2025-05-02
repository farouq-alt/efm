<?php
include("db.php");
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM dossier WHERE numdossier = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $depot = $_POST['date_depot'];
        $trait = $_POST['date_traitement'];
        $rembours = $_POST['montant_remboursement'];
        $lien = $_POST['lien_maladie'];
        $num_malad = $_POST['num_maladie'];
        $total = $_POST['total_dossier'];

        if ($depot == '' || $trait == '' || $rembours == '' || $lien == '' || $num_malad == '' || $total == '') {
            echo "<script>alert('Veuillez remplir tous les champs');</script>";
        } elseif (!is_numeric($rembours) || !is_numeric($total)) {
            echo "<script>alert('Le montant de remboursement et le total du dossier doivent être des nombres');</script>";
        } elseif ($rembours < 0 || $total < 0) {
            echo "<script>alert('Le montant de remboursement et le total du dossier ne peuvent pas être négatifs');</script>";
        } elseif ($rembours > 1000000 || $total > 1000000) {
            echo "<script>alert('Le montant de remboursement et le total du dossier ne peuvent pas dépasser 1 000 000');</script>";
        } elseif (!filter_var($lien, FILTER_VALIDATE_URL)) {
            echo "<script>alert('Le lien maladie doit être une URL valide');</script>";
        } else {
            $stmt = $conn->prepare("UPDATE dossier SET datedepot = ?, date_traitement = ?, montant_remboursement = ?, lien_maladie = ?, num_maladie = ?, total_dossier = ?   WHERE numdossier = ?;");
            $stmt->bind_param("ssissii", $depot, $trait, $rembours, $lien, $num_malad, $total, $id);
            if ($stmt->execute()) {
                echo "<script>alert('Dossier ajouté avec succès');</script>";
                header("Location: miseAjourDossier.php");
                exit();
            } else {
                echo "<script>alert('Erreur lors de l\'ajout du dossier');</script>";
            }
        }
    }
} else {
    echo "<script>alert('Aucun dossier trouvé');</script>";
    header("Location: miseAjourDossier.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
body {
    font-family: sans-serif;
    margin: 20px;
    background-color: #f4f4f4;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

form {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 400px; /* Adjust width as needed */
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 15px;
    align-items: center;
}

h2 {
    color: #007bff;
    grid-column: 1 / span 2;
    text-align: center;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    color: #555;
}

input[type="date"],
input[type="number"],
input[type="url"],
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

select option:first-child {
    color: #777;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
    grid-column: 1 / span 2;
    text-align: center;
    margin-top: 20px;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

.error-message {
    color: red;
    font-size: 0.9em;
    margin-top: 5px;
    grid-column: 1 / span 2;
}
</style>
</head>
<body>
<form action="" method="post">
            <label for="date_depot">Date de depot: </label>
            <input type="date" name="date_depot" value="<?php echo $row['datedepot']; ?>">
            <label for="date_traitement">Date de traitement: </label>
            <input type="date" name="date_traitement" value="<?php echo $row['date_traitement']; ?>">
            <label for="montant_remboursement">Total remboursement: </label>
            <input type="number" name="montant_remboursement" max="1000000" min="0" step="10" value="<?php echo intval($row['montant_remboursement']); ?>">
            <label for="lien_maladie">Lien maladie: </label>
            <input type="url" name="lien_maladie" value="<?php echo $row['lien_maladie']; ?>">
            <label for="num_maladie">Numero de maladie: </label>
            <select name="num_maladie" id="num_maladie">
            <?php
                $stmt_maladie = $conn->prepare("SELECT * FROM maladie");
                $stmt_maladie->execute();
                $result_maladie = $stmt_maladie->get_result();
                while ($maladie_row = $result_maladie->fetch_assoc()) {
                    $isSelected = ($maladie_row['num_maladie'] == $row['num_maladie']) ? 'selected' : '';
                    echo "<option value='" . $maladie_row['num_maladie'] . "' " . $isSelected . ">" . $maladie_row['designation_maladie'] . "</option>";
                }
            ?>

            </select>
            <label for="total_dossier">Total dossier: </label>
            <input type="number" name="total_dossier" max="1000000" min="0" step="10" value="<?php echo $row['total_dossier']; ?>">
            <input type="submit" value="Ajouter dossier" name="ajouter_dossier">
        </form>
</body>
</html>