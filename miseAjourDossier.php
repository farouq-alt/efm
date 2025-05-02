<?php
    include('db.php')
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

table {
    width: 90vw; /* Adjust width as needed */
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: #fff;
    font-weight: bold;
    text-align: center;
}

td {
    text-align: center;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #e0f7fa;
}

a {
    text-decoration: none;
    color: #007bff;
    margin-right: 10px;
    transition: color 0.3s ease;
}

a:hover {
    color: #0056b3;
}

body > table {
    padding: 20px;
    border-radius: 8px;
}
</style>
</head>
<body>
    <table>
        <table>
            <tr>
                <td>numdossier</td>
                <td>datedepot</td>
                <td>montant_remboursement</td>
                <td>date_traitement</td>
                <td>lien_maladie</td>
                <td>matricule</td>
                <td>num_maladie</td>
                <td>total_dossier</td>
                <td>Action</td>
            </tr>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM Dossier");
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$row["numdossier"]."</td>";
                echo "<td>".$row["datedepot"]."</td>";
                echo "<td>".$row["montant_remboursement"]."</td>";
                echo "<td>".$row["date_traitement"]."</td>";
                echo "<td>".$row["lien_maladie"]."</td>";
                echo "<td>".$row["matricule"]."</td>";
                echo "<td>".$row["num_maladie"]."</td>";
                echo "<td>".$row["total_dossier"]."</td>";
                echo "<td> <a href='modifierDossier.php?id=".$row["numdossier"]."'>Modifier</a> | <a href='supprimerDossier.php?id=".$row["numdossier"]."'>Supprimer</a></td></tr>";            
            }
            ?>;
        </table>

    </table>
</body>
</html>