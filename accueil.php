<?php
include("db.php");
session_start();

// Ensure the user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: auth.php");
    exit();
}

// Retrieve the logged-in user's matricule
$mat = $_SESSION["user"]["matricule"];

// Fetch user info from the database
$stmt = $conn->prepare("SELECT a.*, e.nom_entreprise FROM assure a 
    JOIN entreprise e ON a.num_entreprise = e.num_entreprise
    WHERE a.matricule = ?");
$stmt->bind_param("s", $mat);
$stmt->execute();
$result = $stmt->get_result();
$assure = $result->fetch_assoc();

if (!$assure) {
    echo "<script>alert('Utilisateur non trouvé');</script>";
    exit();
}

// Handle form submission for new dossier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depot = $_POST['date_depot'];
    $trait = $_POST['date_traitement'];
    $rembours = $_POST['montant_remboursement'];
    $lien = $_POST['lien_maladie'];
    $num_malad = $_POST['num_maladie'];
    $total = $_POST['total_dossier'];

    // Validate inputs
    if (empty($depot) || empty($trait) || empty($rembours) || empty($lien) || empty($num_malad) || empty($total)) {
        echo "<script>alert('Tous les champs sont requis');</script>";
    } elseif (!is_numeric($rembours) || !is_numeric($total)) {
        echo "<script>alert('Montants invalides');</script>";
    } elseif ($rembours < 0 || $total < 0) {
        echo "<script>alert('Les montants ne peuvent pas être négatifs');</script>";
    } elseif ($rembours > 1000000 || $total > 1000000) {
        echo "<script>alert('Montants trop élevés');</script>";
    } elseif (!filter_var($lien, FILTER_VALIDATE_URL)) {
        echo "<script>alert('Lien maladie invalide');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO dossier 
        (datedepot, date_traitement, montant_remboursement, lien_maladie, num_maladie, total_dossier, matricule)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssissii", $depot, $trait, $rembours, $lien, $num_malad, $total, $mat);
        if ($stmt->execute()) {
            echo "<script>alert('Dossier ajouté avec succès');</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'ajout du dossier');</script>";
        }
    }
}

// Fetch available maladies for the dropdown
$maladies = $conn->query("SELECT * FROM maladie");

// Fetch the list of dossiers for the user
$dossiers = $conn->query("SELECT * FROM dossier WHERE matricule = '$mat'");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f0f2f5;
            padding: 30px;
        }

        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 10px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        form label {
            font-weight: bold;
        }

        form input, form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            grid-column: span 2;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #218838;
        }

        .logout {
            float: right;
            text-decoration: none;
            color: #007bff;
        }

        .logout:hover {
            text-decoration: underline;
        }

        .dossier-table {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue <?php echo htmlspecialchars($assure['nom_ass'] . ' ' . $assure['prenom_ass']); ?></h1>
        <a class="logout" href="deconnexion.php">Déconnexion</a>

        <h2>Vos informations personnelles</h2>
        <table>
            <tr>
                <th>Date naissance</th>
                <th>Enfants</th>
                <th>Situation familiale</th>
                <th>Entreprise</th>
                <th>Total remboursé</th>
                <th>Date décès</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($assure['date_naissance']); ?></td>
                <td><?php echo htmlspecialchars($assure['nb_enfant']); ?></td>
                <td><?php echo htmlspecialchars($assure['situation_familiale']); ?></td>
                <td><?php echo htmlspecialchars($assure['nom_entreprise']); ?></td>
                <td><?php echo htmlspecialchars($assure['total_remb']); ?> MAD</td>
                <td><?php echo $assure['date_deces'] ? htmlspecialchars($assure['date_deces']) : 'N/A'; ?></td>
            </tr>
        </table>

        <h2>Déposer un nouveau dossier</h2>
        <form method="post">
            <label>Date de dépôt:</label>
            <input type="date" name="date_depot" required>

            <label>Date de traitement:</label>
            <input type="date" name="date_traitement" required>

            <label>Montant remboursé:</label>
            <input type="number" name="montant_remboursement" min="0" max="1000000" step="10" required>

            <label>Total du dossier:</label>
            <input type="number" name="total_dossier" min="0" max="1000000" step="10" required>

            <label>Lien maladie:</label>
            <input type="url" name="lien_maladie" required>

            <label>Maladie:</label>
            <select name="num_maladie" required>
                <option value="" disabled selected>Choisir une maladie</option>
                <?php while ($mal = $maladies->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($mal['num_maladie']); ?>"><?= htmlspecialchars($mal['designation_maladie']); ?></option>
                <?php endwhile; ?>
            </select>

            <input type="submit" value="Ajouter dossier">
        </form>

        <h2>Vos dossiers</h2>
        <table class="dossier-table">
            <tr>
                <th>Numéro Dossier</th>
                <th>Date Dépôt</th>
                <th>Date Traitement</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $dossiers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['numdossier']); ?></td>
                    <td><?php echo htmlspecialchars($row['datedepot']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_traitement']); ?></td>
                    <td>
                        <a href="modifierDossier.php?id=<?php echo $row['numdossier']; ?>">Modifier</a> |
                        <a href="supprimerDossier.php?id=<?php echo $row['numdossier']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier ?');">Supprimer</a> |
                        <a href="selectionner.php?id=<?php echo $row['numdossier']; ?>">Sélectionner</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
