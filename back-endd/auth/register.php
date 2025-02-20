<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

if (!$conn) {
    echo json_encode(['success'=>false, 'message' =>'Échec de la connexion à la base de données']);
    exit;
}

// Récupérer les données envoyées
$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['firstname']) || empty($data['lastname']) || empty($data['email']) || empty($data['password']) || empty($data['phone'])) {
    echo json_encode(['success'=>false, 'message' =>'Champs manquants']);
    exit;
}
// if (empty($data['email']) || empty($data['password'])) {
//     echo json_encode('Champs manquants');
//     exit;
// }

// Protection des données pour éviter les injections SQL
$firstname = mysqli_real_escape_string($conn, $data['firstname']);
$lastname = mysqli_real_escape_string($conn, $data['lastname']);
$email = mysqli_real_escape_string($conn, $data['email']);
$password = password_hash($data['password'], PASSWORD_BCRYPT);
$phone = mysqli_real_escape_string($conn, $data['phone']);

// Vérifier si l'email existe déjà
$checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $checkEmailQuery);

if (mysqli_num_rows($result) > 0) {
    echo json_encode(['success'=>false, 'message' =>'Cet email est déjà utilisé']);
    exit;
}

// Préparer et exécuter la requête d'insertion
$query = "INSERT INTO users (firstname, lastname, email, password, phone) VALUES ('$firstname', '$lastname', '$email', '$password','$phone')";

// Réponse au client
if (mysqli_query($conn, $query)) {
    echo json_encode(['success'=>true]);
} else {
    echo json_encode('Erreur lors de l\'inscription : ' . mysqli_error($conn));
    echo json_encode(['success'=>false, 'message' => mysqli_error($conn)]);
}

mysqli_close($conn);

