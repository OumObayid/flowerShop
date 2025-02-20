<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowerShop");

// Vérification de la connexion
if (!$conn) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de connexion à la base de données : ' . mysqli_connect_error()
    ]);
    exit;
}

// Exécution de la requête pour récupérer les utilisateurs
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

// Vérification du résultat de la requête
if ($result) {
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Liste des utilisateurs récupérée avec succès.',
        'dataUsers' => $users
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de l\'exécution de la requête : ' . mysqli_error($conn)
    ]);
}

// Fermeture de la connexion à la base de données
mysqli_close($conn);
