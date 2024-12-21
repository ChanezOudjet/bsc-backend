<?php
// Inclure le fichier de connexion
include('conxpost.php');
$conn = new mysqli($servername, $username, $password, $dbname);

header('Content-Type: application/json');

// Vérifier si la requête est de type DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Récupérer les données JSON envoyées dans la requête
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Vérifier si les données JSON sont valides
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Données JSON invalides.", "received" => $input]);
        exit;
    }

    // Vérifier si l'ID est fourni et valide
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        echo json_encode(["success" => false, "message" => "ID non valide ou manquant."]);
        exit;
    }

    $id = (int)$data['id'];

    // Construire la requête DELETE
    $sql = "DELETE FROM Hauberge WHERE id = $id";

    // Exécuter la requête
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Auberge supprimée avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
    }
}

// Fermer la connexion
$conn->close();
?>
