<?php
// En-têtes CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

// Récupération des données de la requête
$data = json_decode(file_get_contents("php://input"), true);

// Vérifier si itemId est présent dans la requête
if (empty($data['itemId'])) {
    echo json_encode(["success" => "false", "message" => "Données manquantes : itemId requis."]);
    exit;
}

$itemId = $data['itemId'];

// Vérification de l'existence du panier pour cet item
$cartIdQuery = "SELECT cart_id FROM cart_items WHERE id = $itemId";
$cartIdResult = mysqli_query($conn, $cartIdQuery);
if ($cartIdResult && mysqli_num_rows($cartIdResult) > 0) {
    $cart = mysqli_fetch_assoc($cartIdResult);
    $cartId = $cart['cart_id'];

    // Supprimer l'article
    $deleteQuery = "DELETE FROM cart_items WHERE id = $itemId";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if (!$deleteResult) {
        echo json_encode(["success" => "false", "message" => "Erreur lors de la suppression de l'article du panier."]);
        exit;
    }

    // Vérifier si le panier est vide après suppression
    $checkCartQuery = "SELECT COUNT(*) AS itemCount FROM cart_items WHERE cart_id = $cartId";
    $checkCartResult = mysqli_query($conn, $checkCartQuery);
    $itemCount = mysqli_fetch_assoc($checkCartResult)['itemCount'];

    // Si le panier est vide, supprimer également le panier
    if ($itemCount == 0) {
        $deleteCartQuery = "DELETE FROM carts WHERE id = $cartId";
        mysqli_query($conn, $deleteCartQuery);
    }

    echo json_encode(["success" => "true", "message" => "Produit supprimé du panier"]);
} else {
    echo json_encode(["success" => "false", "message" => "Article non trouvé dans le panier."]);
}