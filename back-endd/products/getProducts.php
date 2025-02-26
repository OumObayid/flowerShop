<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Connexion à la base de données
$conn = mysqli_connect("localhost", "root", "", "flowershop");

if (!$conn) {
    // Réponse en cas d'échec de la connexion à la base de données
    echo json_encode([
        "success" => false,
        "message" => "Échec de la connexion à la base de données.",
    ]);
    exit;
}

// Requête pour récupérer les produits
// $query = "SELECT id, nom, description, prix, categorie_id, image FROM produits";
$query = "SELECT p.id, p.nom, p.description, p.prix, p.categorie_id, c.nom AS categorie_nom, p.image 
          FROM produits p 
          INNER JOIN categories c ON p.categorie_id = c.id";
$result = mysqli_query($conn, $query);

if (!$result) {
    // Réponse en cas d'échec de la requête SQL
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de l'exécution de la requête : " . mysqli_error($conn),
    ]);
    mysqli_close($conn);
    exit;
}

$products = [];
// while ($row = mysqli_fetch_assoc($result)) {
//     // Convertir l'image BLOB en chaîne base64
//     $row['image'] = base64_encode($row['image']);
//     $products[] = $row;
// }
while ($row = mysqli_fetch_assoc($result)) {
    // Vérifie si l'image est stockée sous forme de BLOB
    if (!empty($row['image'])) {
        $mimeType = "image/png"; // Change en "image/png" si nécessaire
        $row['image'] = "data:$mimeType;base64," . base64_encode($row['image']);
    }
    $products[] = $row;
}


// Réponse en cas de succès
echo json_encode([
    "success" => true,
    "products" => $products
]);

mysqli_close($conn);

