<?php
header('Content-Type: application/json');

// Inclure le fichier de connexion
include('conxpost.php');
$conn = new mysqli($servername, $username, $password, $dbname);


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
