<?php
// Inclure le fichier de connexion
include('conxpost.php');
$conn = new mysqli($servername, $username, $password, $dbname);

header('Content-Type: application/json');

// Vérifier si la requête est de type GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Vérifier si la demande est pour obtenir les auberges
    if (isset($_GET['type']) && $_GET['type'] === 'auberges') {
        // Sélectionner toutes les auberges dans la base de données
        $sql = "SELECT * FROM Hauberge";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Créer un tableau pour stocker les auberges
            $auberges = [];
            while ($row = $result->fetch_assoc()) {
                $auberges[] = $row;
            }

            // Retourner les auberges au format JSON
            echo json_encode(["success" => true, "auberges" => $auberges]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucune auberge trouvée."]);
        }
    }
    // Vérifier si la demande est pour obtenir la liste noire
    elseif (isset($_GET['type']) && $_GET['type'] === 'blacklist') {
        // Sélectionner toutes les personnes de la liste noire
        $sql = "SELECT * FROM BlackList";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Créer un tableau pour stocker les personnes de la liste noire
            $blacklist = [];
            while ($row = $result->fetch_assoc()) {
                $blacklist[] = $row;
            }

            // Retourner la liste noire au format JSON
            echo json_encode(["success" => true, "blacklist" => $blacklist]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucune personne trouvée dans la liste noire."]);
        }
    }
    // Vérifier si la demande est pour obtenir les résidents
    elseif (isset($_GET['type']) && $_GET['type'] === 'residents') {
        // Sélectionner tous les résidents dans la base de données
        $sql = "SELECT * FROM Resident";  // Assurez-vous que la table existe
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Créer un tableau pour stocker les résidents
            $residents = [];
            while ($row = $result->fetch_assoc()) {
                $residents[] = $row;
            }

            // Retourner les résidents au format JSON
            echo json_encode(["success" => true, "residents" => $residents]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucun résident trouvé."]);
        }
    }
    // Vérifier si la demande est pour obtenir les hôtels
    elseif (isset($_GET['type']) && $_GET['type'] === 'hotels') {
        // Sélectionner tous les hôtels dans la base de données
        $sql = "SELECT * FROM Hotel";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Créer un tableau pour stocker les hôtels
            $hotels = [];
            while ($row = $result->fetch_assoc()) {
                $hotels[] = $row;
            }

            // Retourner les hôtels au format JSON
            echo json_encode(["success" => true, "hotels" => $hotels]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucun hôtel trouvé."]);
        }
    }
    // Vérifier si la demande est pour obtenir les sites touristiques
    elseif (isset($_GET['type']) && $_GET['type'] === 'citetouristiques') {
        // Sélectionner tous les sites touristiques dans la base de données
        $sql = "SELECT * FROM CiteTouristique";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Créer un tableau pour stocker les sites touristiques
            $citesTouristiques = [];
            while ($row = $result->fetch_assoc()) {
                $citesTouristiques[] = $row;
            }

            // Retourner les sites touristiques au format JSON
            echo json_encode(["success" => true, "citetouristiques" => $citesTouristiques]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucun site touristique trouvé."]);
        }
    }
    // Vérifier si la demande est pour obtenir les réservations
    elseif (isset($_GET['type']) && $_GET['type'] === 'reservations') {
        // Sélectionner toutes les réservations dans la base de données
        $sql = "SELECT * FROM Reservation";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Créer un tableau pour stocker les réservations
            $reservations = [];
            while ($row = $result->fetch_assoc()) {
                $reservations[] = $row;
            }

            // Retourner les réservations au format JSON
            echo json_encode(["success" => true, "reservations" => $reservations]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucune réservation trouvée."]);
        }
    }
    // Vérifier si la demande est pour obtenir les transports
    elseif (isset($_GET['type']) && $_GET['type'] === 'transports') {
        // Sélectionner tous les transports dans la base de données
        $sql = "SELECT * FROM Transports";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Créer un tableau pour stocker les transports
            $transports = [];
            while ($row = $result->fetch_assoc()) {
                $transports[] = $row;
            }

            // Retourner les transports au format JSON
            echo json_encode(["success" => true, "transports" => $transports]);
        } else {
            echo json_encode(["success" => false, "message" => "Aucun transport trouvé."]);
        }
    }
    // Si le type n'est pas précisé ou n'est pas valide
    else {
        echo json_encode(["success" => false, "message" => "Type invalide ou non précisé."]);
    }
}

// Fermer la connexion
$conn->close();
?>
