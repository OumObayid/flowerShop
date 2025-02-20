<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$conn = mysqli_connect("localhost", "root", "", "flowershop");

// Vérifiez la connexion à la base de données
if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur de connexion à la base de données : " . mysqli_connect_error()
    ]);
    exit();
}

// Récupération et sécurisation des données
$nom = mysqli_real_escape_string($conn, $_POST['nom'] ?? '');
$description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
$prix = mysqli_real_escape_string($conn, $_POST['prix'] ?? '');
$categorie_id = mysqli_real_escape_string($conn, $_POST['categorie_id'] ?? '');
$image = isset($_FILES['image']['tmp_name']) ? addslashes(file_get_contents($_FILES['image']['tmp_name'])) : null;

// Validation des données
if (empty($nom) || empty($description) || empty($prix) || empty($categorie_id) || empty($image)) {
    echo json_encode([
        "success" => false,
        "message" => "Tous les champs sont requis."
    ]);
    mysqli_close($conn);
    exit();
}

// Préparation de la requête d'insertion
$query = "INSERT INTO produits (nom, description, prix, categorie_id, image) VALUES ('$nom', '$description', '$prix', '$categorie_id', '$image')";
$result = mysqli_query($conn, $query);

// Vérification du résultat de l'exécution de la requête
if ($result) {
    echo json_encode([
        "success" => true,
        "message" => "Produit ajouté avec succès."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de l'ajout du produit : " . mysqli_error($conn)
    ]);
}

// Fermeture de la connexion
mysqli_close($conn);

