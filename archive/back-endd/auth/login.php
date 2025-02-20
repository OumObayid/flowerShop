<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Échec de la connexion à la base de données']);
    exit;
}

// Récupérer les données envoyées
$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Champs manquants']);
    exit;
}

$email = mysqli_real_escape_string($conn, $data['email']);
$password = $data['password'];

// Vérification de l'email
$query = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($password, $user['password'])) {
        // Réponse en cas de succès
        echo json_encode(['success' => true, 'user' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email non trouvé']);
}

mysqli_close($conn);
