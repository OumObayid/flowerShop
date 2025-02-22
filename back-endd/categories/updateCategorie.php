<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$conn = mysqli_connect("localhost", "root", "", "flowerShop");

// Gérer les pré-requêtes CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Lecture des données envoyées
$data = json_decode(file_get_contents("php://input"), true);

$id = isset($data['id']) ? mysqli_real_escape_string($conn, $data['id']) : null;
$nom = isset($data['nom']) ? mysqli_real_escape_string($conn, $data['nom']) : null;

// Vérification des données
if (is_null($id) || empty($nom)) {
    echo json_encode(["success" => false, "message" => "ID et nom requis"]);
    exit;
}

// Requête SQL pour mettre à jour la catégorie
$query = "UPDATE categories SET nom='$nom' WHERE id='$id'";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(["success" => true, "message" => "Catégorie mise à jour"]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
}

mysqli_close($conn);
