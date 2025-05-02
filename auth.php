<?php
include("db.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mat = $_POST['mat'];
    $pwd = $_POST['pwd'];

    $sql = "SELECT * FROM assure WHERE matricule = '$mat' AND mot_depasse = '$pwd'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['user'] = mysqli_fetch_assoc($result);
        header("Location: accueil.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
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
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <h2>Connexion</h2>
        <label for="mat">Matricule</label>
        <input type="text" name="mat" id="mat" required>
        <label for="pwd">Mot de passe</label>
        <input type="password" name="pwd" id="pwd" required>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
