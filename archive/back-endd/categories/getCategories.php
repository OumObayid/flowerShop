<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0); // Répondre à la requête OPTIONS pour les CORS
}

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowerShop");



// Vérification de la connexion
if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Échec de la connexion à la base de données : " . mysqli_connect_error()
    ]);
    exit;
}

// Requête pour obtenir les catégories
$query = "SELECT id, nom FROM categories"; // Modifiez selon la structure de votre table
$result = mysqli_query($conn, $query);

if ($result) {
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    // Réponse en cas de succès
    echo json_encode([
        "success" => true,
        "categories" => $categories
    ]);
} else {
    // Réponse en cas d'échec de la requête
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la récupération des catégories : " . mysqli_error($conn)
    ]);
}

// Fermeture de la connexion
mysqli_close($conn);

