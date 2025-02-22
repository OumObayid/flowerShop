<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowerShop");

if (!$conn) {
    echo json_encode(["success" => false, "error" => "Erreur de connexion à la base de données."]);
    exit;
}

// Lecture de l'ID envoyé via DELETE
$data = json_decode(file_get_contents("php://input"), true);
$id = isset($data['id']) ? intval($data['id']) : 0;

if ($id) {
    $query = "DELETE FROM categories WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_affected_rows($conn) > 0) {
        echo json_encode(["success" => true,"message"=>"Suppression réussite"]);
    } else {
        echo json_encode(["success" => false, "message" => "Échec de la suppression ou categorie introuvable."]);
    }
} else {
        echo json_encode(["success" => false, "message" => "ID non fourni ou invalide."]);
}

mysqli_close($conn);