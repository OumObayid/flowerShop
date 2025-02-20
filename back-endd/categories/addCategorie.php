<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowerShop");
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de connexion à la base de données.'
    ]);
    exit;
}

// Lecture des données envoyées
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nom']) || empty($data['nom'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Le champ nom est requis.'
    ]);
    exit;
}

$nom = mysqli_real_escape_string($conn, $data['nom']);

// Requête SQL d'insertion
$query = "INSERT INTO categories (nom) VALUES ('$nom')";

// Exécution de la requête
if (mysqli_query($conn, $query)) {
    // Si l'insertion réussit, renvoyer une réponse de succès avec l'ID inséré
    $response = [
        'success' => true,
        'message' => 'Catégorie ajoutée avec succès!',
        'id' => mysqli_insert_id($conn) // Retourne l'ID de la catégorie ajoutée
    ];
} else {
    // Si l'insertion échoue, renvoyer une réponse d'échec
    $response = [
        'success' => false,
        'message' => 'Erreur lors de l\'ajout de la catégorie : ' . mysqli_error($conn)
    ];
}

// Retourne la réponse sous forme de JSON
echo json_encode($response);

// Fermer la connexion à la base de données
mysqli_close($conn);
