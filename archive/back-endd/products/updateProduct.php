<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$conn = mysqli_connect("localhost", "root", "", "flowershop");

// Gérer les pré-requêtes CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST');
    exit(0);
}

// Vérifier si une requête POST est envoyée
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données de la requête
    $id = isset($_POST['id']) ? mysqli_real_escape_string($conn, $_POST['id']) : null;
    $nom = isset($_POST['nom']) ? mysqli_real_escape_string($conn, $_POST['nom']) : null;
    $description = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : null;
    $prix = isset($_POST['prix']) ? mysqli_real_escape_string($conn, $_POST['prix']) : null;
    $categorie_id = isset($_POST['categorie_id']) ? mysqli_real_escape_string($conn, $_POST['categorie_id']) : null;

    // Vérifier si l'ID est manquant
    if (is_null($id)) {
        echo json_encode(["success" => false, "message" => "ID manquant"]);
        exit;
    }

    // Vérifier si une image est envoyée
    $image = isset($_FILES['image']) ? addslashes(file_get_contents($_FILES['image']['tmp_name'])) : null;

    // Construire la requête SQL
    $query = "UPDATE produits SET nom='$nom', description='$description', prix='$prix', categorie_id='$categorie_id'";

    // Ajouter l'image uniquement si elle est présente dans la requête
    if ($image) {
        $query .= ", image='$image'";
    }

    $query .= " WHERE id='$id'";

    // Exécuter la requête
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

mysqli_close($conn);
