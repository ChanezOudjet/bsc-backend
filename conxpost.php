<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hack";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Échec de la connexion : " . $conn->connect_error]));
}

header('Content-Type: application/json');

// Vérifier si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lire et décoder le JSON envoyé dans le corps de la requête
    $input = file_get_contents('php://input');
    error_log("Données reçues : " . $input); // Log pour déboguer
    $data = json_decode($input, true);

    // Vérifier si les données JSON sont valides
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Données JSON invalides."]);
        exit;
    }

    // Ajouter une personne à la liste noire
    if (isset($data['nom'], $data['prenom'], $data['numero_carte_identite'])) {
        $nom = $conn->real_escape_string($data['nom']);
        $prenom = $conn->real_escape_string($data['prenom']);
        $numero_carte_identite = $conn->real_escape_string($data['numero_carte_identite']);

        $sql = "INSERT INTO BlackList (nom, prenom, numero_carte_identite) 
                VALUES ('$nom', '$prenom', '$numero_carte_identite')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Personne ajoutée à la liste noire avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        }
    }
    // Ajouter une auberge
    elseif (isset($data['type'], $data['capacite'], $data['nom'], $data['emplacement'], $data['adresse'], $data['email'], $data['password'], $data['telephone'], $data['nbr_personne_reserve'], $data['disponibilite'], $data['image_list'], $data['offres'])) {
        $type = $conn->real_escape_string($data['type']);
        $capacite = (int)$data['capacite'];
        $nom = $conn->real_escape_string($data['nom']);
        $emplacement = $conn->real_escape_string($data['emplacement']);
        $adresse = $conn->real_escape_string($data['adresse']);
        $email = $conn->real_escape_string($data['email']);
        $password = password_hash($conn->real_escape_string($data['password']), PASSWORD_BCRYPT);
        $telephone = $conn->real_escape_string($data['telephone']);
        $nbr_personne_reserve = (int)$data['nbr_personne_reserve'];
        $disponibilite = (int)$data['disponibilite'];
        $image_list = $conn->real_escape_string(json_encode($data['image_list']));
        $offres = $conn->real_escape_string(json_encode($data['offres']));

        $sql = "INSERT INTO Hauberge (type, capacite, nom, emplacement, adresse, email, password, telephone, nbr_personne_reserve, disponibilite, image_list, offres)
                VALUES ('$type', $capacite, '$nom', '$emplacement', '$adresse', '$email', '$password', '$telephone', $nbr_personne_reserve, $disponibilite, '$image_list', '$offres')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Auberge ajoutée avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        }
    }
    // Ajouter un hôtel
    elseif (isset($data['nom'], $data['adresse'], $data['email'], $data['telephone'], $data['capacite'], $data['type'], $data['disponibilite'], $data['description'])) {
        $nom = $conn->real_escape_string($data['nom']);
        $adresse = $conn->real_escape_string($data['adresse']);
        $email = $conn->real_escape_string($data['email']);
        $telephone = $conn->real_escape_string($data['telephone']);
        $capacite = (int)$data['capacite'];
        $type = $conn->real_escape_string($data['type']);
        $disponibilite = (int)$data['disponibilite'];
        $description = $conn->real_escape_string($data['description']);

        $sql = "INSERT INTO Hotel (nom, adresse, email, telephone, capacite, type, disponibilite, description)
                VALUES ('$nom', '$adresse', '$email', '$telephone', $capacite, '$type', $disponibilite, '$description')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Hôtel ajouté avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        }
    }
    // Ajouter un site touristique
    elseif (isset($data['nom'], $data['description'], $data['localisation'], $data['image_url'])) {
        $nom = $conn->real_escape_string($data['nom']);
        $description = $conn->real_escape_string($data['description']);
        $localisation = $conn->real_escape_string($data['localisation']);
        $image_url = $conn->real_escape_string($data['image_url']);

        $sql = "INSERT INTO citeTouristique (nom, description, localisation, image_url) 
                VALUES ('$nom', '$description', '$localisation', '$image_url')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Site touristique ajouté avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        }
    }
    // Ajouter une réservation
    elseif (isset($data['hauberge_id'], $data['resident_id'], $data['numero_chambre'], $data['date_entree'], $data['date_sortie'], $data['nature_reservation'], $data['restauration_montant'])) {
        $hauberge_id = (int)$data['hauberge_id'];
        $resident_id = (int)$data['resident_id'];
        $numero_chambre = (int)$data['numero_chambre'];
        $date_entree = $conn->real_escape_string($data['date_entree']);
        $date_sortie = $conn->real_escape_string($data['date_sortie']);
        $nature_reservation = $conn->real_escape_string($data['nature_reservation']);
        $restauration_montant = (float)$data['restauration_montant'];

        // Validation des dates
        if (!strtotime($date_entree) || !strtotime($date_sortie) || strtotime($date_entree) > strtotime($date_sortie)) {
            echo json_encode(["success" => false, "message" => "Dates invalides."]);
            exit;
        }
        if (!in_array($nature_reservation, ['Gratuit', 'Non Gratuit'])) {
            echo json_encode(["success" => false, "message" => "Nature de réservation invalide."]);
            exit;
        }

        // Vérifier la disponibilité de la chambre
        $check_sql = "SELECT * FROM Reservation 
                      WHERE hauberge_id = $hauberge_id 
                      AND numero_chambre = $numero_chambre 
                      AND (date_entree <= '$date_sortie' AND date_sortie >= '$date_entree')";

        $result = $conn->query($check_sql);
        if ($result->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "La chambre est déjà réservée pour ces dates."]);
            exit;
        }

        // Insertion de la réservation
        $sql = "INSERT INTO Reservation (hauberge_id, resident_id, numero_chambre, date_entree, date_sortie, nature_reservation, restauration_montant) 
                VALUES ($hauberge_id, $resident_id, $numero_chambre, '$date_entree', '$date_sortie', '$nature_reservation', $restauration_montant)";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Réservation ajoutée avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        }
    }
    // Ajouter un transport
    elseif (isset($data['type'], $data['nom'], $data['numero_ligne'], $data['depart'], $data['destination'], $data['heure_depart'], $data['heure_arrivee'], $data['jours_operables'], $data['disponibilite'], $data['tarif'])) {
        $type = $conn->real_escape_string($data['type']);
        $nom = $conn->real_escape_string($data['nom']);
        $numero_ligne = $conn->real_escape_string($data['numero_ligne']);
        $depart = $conn->real_escape_string($data['depart']);
        $destination = $conn->real_escape_string($data['destination']);
        $heure_depart = $conn->real_escape_string($data['heure_depart']);
        $heure_arrivee = $conn->real_escape_string($data['heure_arrivee']);
        $jours_operables = $conn->real_escape_string($data['jours_operables']);
        $disponibilite = (bool)$data['disponibilite'];
        $tarif = (float)$data['tarif'];

        $sql = "INSERT INTO Transports (type, nom, numero_ligne, depart, destination, heure_depart, heure_arrivee, jours_operables, disponibilite, tarif) 
                VALUES ('$type', '$nom', '$numero_ligne', '$depart', '$destination', '$heure_depart', '$heure_arrivee', '$jours_operables', $disponibilite, $tarif)";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Transport ajouté avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        }
    }
}

// Fermeture de la connexion
$conn->close();
?>
