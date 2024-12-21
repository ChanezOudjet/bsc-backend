<?php
header('Content-Type: application/json');

// Connexion à la base de données
$dsn = "mysql:host=localhost;hack";
$username = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur de connexion : " . $e->getMessage()]);
    exit;
}

// Récupérer les données de la requête
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['hauberge_id'], $data['resident_id'], $data['numero_chambre'], $data['date_entree'], $data['date_sortie'], $data['nature_reservation'], $data['restauration_montant'])) {
    echo json_encode(["error" => "Paramètres manquants"]);
    exit;
}

// Appel de la fonction makeReservation
require 'functions.php'; // Assurez-vous d'inclure la fonction makeReservation
$message = makeReservation(
    $data['hauberge_id'],
    $data['resident_id'],
    $data['numero_chambre'],
    $data['date_entree'],
    $data['date_sortie'],
    $data['nature_reservation'],
    $data['restauration_montant'],
    $pdo
);

echo json_encode(["message" => $message]);
