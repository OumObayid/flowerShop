<?php
// update.php : Mettre à jour la quantité d'un produit dans le panier

// En-têtes CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

// Vérifier la connexion
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données."]);
    exit;
}

// Récupérer les données JSON de la requête
$data = json_decode(file_get_contents("php://input"), true);
if (empty($data['itemId']) || !isset($data['quantity'])) {
    // Si une des données nécessaires est manquante, retourner une erreur
    echo json_encode(["success" => false, "message" => "Données manquantes. Assurez-vous de transmettre itemId et quantity."]);
    exit;
}
// convertir en int
$itemId = intval($data['itemId']);
$newQuantity = intval($data['quantity']);

// Mettre à jour la quantité dans la table cart_items
$updateQuery = "UPDATE cart_items SET quantity = $newQuantity WHERE id = $itemId";
$updateResult = mysqli_query($conn, $updateQuery);

// Vérifier si la mise à jour a réussi
if ($updateResult && mysqli_affected_rows($conn) > 0) {
    echo json_encode(["success" => true, "message" => "Quantité mise à jour avec succès."]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour de la quantité ou aucun changement effectué."]);
}

// Fermer la connexion à la base de données
mysqli_close($conn);
