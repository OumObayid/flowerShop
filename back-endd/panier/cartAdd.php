<?php
// En-têtes CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Headers: Content-Type');

// Gérer la requête OPTIONS (pré-vol)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Répondre avec un statut OK
    http_response_code(200);
    exit();
}
// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

// Vérifier la connexion
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données"]);
    exit();
}

// Récupérer les données envoyées en POST
$data = json_decode(file_get_contents("php://input"), true);
$userId = $data['userId'];
$productId = $data['productId'];
$quantity = $data['quantity'];

// Vérifier que les paramètres nécessaires sont fournis
if (!isset($userId, $productId, $quantity)) {
    echo json_encode(["success" => false, "message" => "Données incomplètes"]);
    exit();
}

// Vérifier si un panier existe déjà pour cet utilisateur
$query = "SELECT id FROM carts WHERE user_id = $userId";
$result = mysqli_query($conn, $query);
$cart = mysqli_fetch_assoc($result);

// Si le panier n'existe pas, on en crée un nouveau
if (!$cart) {
    $insertCart = "INSERT INTO carts (user_id) VALUES ($userId)";
    if (mysqli_query($conn, $insertCart)) {
        $cartId = mysqli_insert_id($conn);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la création du panier"]);
        exit();
    }
} else {
    $cartId = $cart['id'];
}

// Vérifier si le produit est déjà dans le panier
$query = "SELECT id, quantity FROM cart_items WHERE cart_id = $cartId AND produit_id = $productId";
$result = mysqli_query($conn, $query);
$item = mysqli_fetch_assoc($result);

if ($item) {
    // Mettre à jour la quantité si le produit est déjà dans le panier
    $newQuantity = $item['quantity'] + $quantity;
    $updateQuery = "UPDATE cart_items SET quantity = $newQuantity WHERE id = {$item['id']}";
    if (!mysqli_query($conn, $updateQuery)) {
        echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour de la quantité"]);
        exit();
    }
} else {
    // Ajouter le produit au panier
    $insertQuery = "INSERT INTO cart_items (cart_id, produit_id, quantity) VALUES ($cartId, $productId, $quantity)";
    if (!mysqli_query($conn, $insertQuery)) {
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout du produit au panier"]);
        exit();
    }
}

// Récupérer les informations de l'article ajouté pour renvoyer une réponse détaillée
$query = "SELECT ci.id, p.nom, ci.quantity
          FROM cart_items ci
          JOIN produits p ON ci.produit_id = p.id
          WHERE ci.cart_id = $cartId AND ci.produit_id = $productId";
$result = mysqli_query($conn, $query);

if ($result && $addedItem = mysqli_fetch_assoc($result)) {
    echo json_encode([
        "success" => true,
        "item" => [
            "id" => $addedItem['id'],
            "nom" => $addedItem['nom'],
            "quantity" => $addedItem['quantity']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de la récupération des informations du produit"]);
}

// Fermer la connexion
mysqli_close($conn);