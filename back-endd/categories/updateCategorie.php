<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$conn = mysqli_connect("localhost", "root", "", "flowerShop");

// Gérer les pré-requêtes CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST');
    exit(0);
}


// Lecture des données envoyées
$data = json_decode(file_get_contents("php://input"), true);

// Récupérer les données de la requête
$id = isset($data['id']) ? mysqli_real_escape_string($conn, $data['id']) : null;
$nom = isset($data['nom']) ? mysqli_real_escape_string($conn, $data['nom']) : null;

// Vérifier si l'ID est manquant
if (is_null($id)) {
    echo json_encode(["success" => false, "message" => "ID manquant"]);
    exit;
}

// Construire la requête SQL
$query = "UPDATE categories SET nom='$nom'";


$query .= " WHERE id='$id'";

// Exécuter la requête
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(["success" => true,"message"=>"votre catégorie a été bien modifié"]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
}


mysqli_close($conn);
