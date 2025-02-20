<?php
// En-têtes CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

// Récupérer l'ID de l'utilisateur passé en paramètre
$userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;

// Si aucun ID utilisateur n'est fourni, renvoyer un tableau vide
if ($userId <= 0) {
    echo json_encode(["items" => [],"message"=>"userId manquant"]);
    exit;
}


// Récupérer les articles du panier de l'utilisateur
$query = "
    SELECT ci.id AS id, ci.produit_id AS productId,p.image AS image, p.nom AS nom, ci.quantity AS quantity, p.prix AS prix 
    FROM cart_items ci
    JOIN carts c ON ci.cart_id = c.id
    JOIN produits p ON ci.produit_id = p.id
    WHERE c.user_id = $userId";
$result = mysqli_query($conn, $query);

// Organiser les résultats en tableau
$cartItems = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $row['image'] = base64_encode($row['image']);

        $cartItems[] = [
            "id" => $row['id'],
            "productId" => $row['productId'],
            "nom" => $row['nom'],
            "quantity" => intval($row['quantity']),
            "prix" => floatval($row['prix']),
            "image" => $row['image']
        ];
    }
}

// Retourner les articles du panier
echo json_encode(["items" => $cartItems]);

mysqli_close($conn);