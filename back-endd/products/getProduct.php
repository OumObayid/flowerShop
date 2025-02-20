<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST'); // Changez à POST si vous recevez des données JSON
header('Access-Control-Allow-Headers: Content-Type');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

// Vérifiez la connexion à la base de données
if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Échec de la connexion à la base de données : " . mysqli_connect_error(),
    ]);
    exit;
}

// Lecture des données envoyées
$data = json_decode(file_get_contents("php://input"), true);
$id = isset($data['id']) ? intval($data['id']) : 0;

// Validation de l'ID
if ($id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "L'ID fourni est invalide.",
    ]);
    mysqli_close($conn);
    exit;
}

// Requête pour récupérer le produit par ID
$query = "SELECT * FROM produits WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($result) {
    $product = mysqli_fetch_assoc($result);
    if ($product) {
        // Gestion de l'image (conversion en base64 si nécessaire)
        if (!empty($product['image']) && file_exists($product['image'])) {
            $imageData = file_get_contents($product['image']);
            $product['image'] = base64_encode($imageData);
        } else if (!empty($product['image'])) {
            $product['image'] = base64_encode($product['image']); // Pour les images en BLOB
        } else {
            $product['image'] = null; // Si aucune image n'est trouvée
        }

        // Réponse en cas de succès
        echo json_encode([
            "success" => true,
            "product" => $product,
        ]);
    } else {
        // Produit non trouvé
        echo json_encode([
            "success" => false,
            "message" => "Produit introuvable.",
        ]);
    }
} else {
    // Échec de la requête
    echo json_encode([
        "success" => false,
        "message" => "Échec de l'exécution de la requête : " . mysqli_error($conn),
    ]);
}

// Fermeture de la connexion
mysqli_close($conn);
?>
