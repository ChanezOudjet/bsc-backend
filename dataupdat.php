<?php
// Inclure le fichier de connexion
include('conxpost.php');
$conn = new mysqli($servername, $username, $password, $dbname);

header('Content-Type: application/json');

// Vérifier si la requête est de type PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Récupérer les données JSON envoyées dans la requête
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Vérifier si les données sont valides
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Données JSON invalides."]);
        exit;
    }

    // Vérifier le type de mise à jour
    if (isset($data['type'])) {
        $type = $data['type'];
        $id = isset($data['id']) ? (int)$data['id'] : null;

        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID manquant."]);
            exit;
        }

        // Logique pour chaque type de mise à jour
        switch ($type) {
            case 'auberge':
                // Vérifier si les champs nécessaires sont présents
                if (isset($data['type'], $data['capacite'], $data['nom'], $data['emplacement'], $data['adresse'], $data['email'], $data['password'], $data['telephone'], $data['nbr_personne_reserve'], $data['disponibilite'], $data['image_list'], $data['offres'])) {
                    $type = $conn->real_escape_string($data['type']);
                    $capacite = (int)$data['capacite'];
                    $nom = $conn->real_escape_string($data['nom']);
                    $emplacement = $conn->real_escape_string($data['emplacement']);
                    $adresse = $conn->real_escape_string($data['adresse']);
                    $email = $conn->real_escape_string($data['email']);
                    $password = password_hash($conn->real_escape_string($data['password']), PASSWORD_BCRYPT);
                    $telephone = $conn->real_escape_string($data['telephone']);
                    $nbr_personne_reserve = (int)$data['nbr_personne_reserve'];
                    $disponibilite = (bool)$data['disponibilite'];
                    $image_list = $conn->real_escape_string(json_encode($data['image_list']));
                    $offres = $conn->real_escape_string(json_encode($data['offres']));

                    // Vérifier si l'ID existe dans la table Hauberge
                    $result = $conn->query("SELECT id FROM Hauberge WHERE id = $id");
                    if ($result->num_rows === 0) {
                        echo json_encode(["success" => false, "message" => "Aucune auberge trouvée avec cet ID."]);
                        exit;
                    }

                    // Construire la requête de mise à jour
                    $sql = "UPDATE Hauberge SET
                            type = '$type',
                            capacite = $capacite,
                            nom = '$nom',
                            emplacement = '$emplacement',
                            adresse = '$adresse',
                            email = '$email',
                            password = '$password',
                            telephone = '$telephone',
                            nbr_personne_reserve = $nbr_personne_reserve,
                            disponibilite = $disponibilite,
                            image_list = '$image_list',
                            offres = '$offres'
                            WHERE id = $id";
                }
                break;

            case 'blacklist':
                // Vérifier si les champs nécessaires sont présents pour BlackList
                if (isset($data['nom'], $data['prenom'], $data['numero_carte_identite'])) {
                    $nom = $conn->real_escape_string($data['nom']);
                    $prenom = $conn->real_escape_string($data['prenom']);
                    $numero_carte_identite = $conn->real_escape_string($data['numero_carte_identite']);
                    
                    // Vérifier si l'ID existe dans la table BlackList
                    $result = $conn->query("SELECT id FROM BlackList WHERE id = $id");
                    if ($result->num_rows === 0) {
                        echo json_encode(["success" => false, "message" => "Aucun enregistrement trouvé dans la liste noire avec cet ID."]);
                        exit;
                    }

                    // Construire la requête de mise à jour pour BlackList
                    $sql = "UPDATE BlackList SET nom = '$nom', prenom = '$prenom', numero_carte_identite = '$numero_carte_identite' WHERE id = $id";
                } else {
                    echo json_encode(["success" => false, "message" => "Données manquantes pour la mise à jour de la liste noire."]);
                    exit;
                }
                break;

            case 'resident':
                // Vérifier si les champs nécessaires sont présents pour Resident
                if (isset($data['name'], $data['email'], $data['phone'])) {
                    $name = $conn->real_escape_string($data['name']);
                    $email = $conn->real_escape_string($data['email']);
                    $phone = $conn->real_escape_string($data['phone']);
                    
                    // Vérifier si l'ID existe dans la table Resident
                    $result = $conn->query("SELECT id FROM Resident WHERE id = $id");
                    if ($result->num_rows === 0) {
                        echo json_encode(["success" => false, "message" => "Aucun résident trouvé avec cet ID."]);
                        exit;
                    }

                    // Construire la requête de mise à jour pour Resident
                    $sql = "UPDATE Resident SET name = '$name', email = '$email', phone = '$phone' WHERE id = $id";
                }
                break;

            case 'hotel':
                // Vérifier si les champs nécessaires sont présents pour Hotel
                if (isset($data['name'], $data['location'], $data['rating'])) {
                    $name = $conn->real_escape_string($data['name']);
                    $location = $conn->real_escape_string($data['location']);
                    $rating = (float)$data['rating'];
                    
                    // Vérifier si l'ID existe dans la table Hotel
                    $result = $conn->query("SELECT id FROM Hotel WHERE id = $id");
                    if ($result->num_rows === 0) {
                        echo json_encode(["success" => false, "message" => "Aucun hôtel trouvé avec cet ID."]);
                        exit;
                    }

                    // Construire la requête de mise à jour pour Hotel
                    $sql = "UPDATE Hotel SET name = '$name', location = '$location', rating = $rating WHERE id = $id";
                }
                break;

            case 'citetouristique':
                // Vérifier si les champs nécessaires sont présents pour CiteTouristique
                if (isset($data['name'], $data['description'], $data['location'])) {
                    $name = $conn->real_escape_string($data['name']);
                    $description = $conn->real_escape_string($data['description']);
                    $location = $conn->real_escape_string($data['location']);
                    
                    // Vérifier si l'ID existe dans la table CiteTouristique
                    $result = $conn->query("SELECT id FROM CiteTouristique WHERE id = $id");
                    if ($result->num_rows === 0) {
                        echo json_encode(["success" => false, "message" => "Aucun site touristique trouvé avec cet ID."]);
                        exit;
                    }

                    // Construire la requête de mise à jour pour CiteTouristique
                    $sql = "UPDATE CiteTouristique SET name = '$name', description = '$description', location = '$location' WHERE id = $id";
                }
                break;

            case 'reservation':
                // Vérifier si les champs nécessaires sont présents pour Reservation
                if (isset($data['status'], $data['date'])) {
                    $status = $conn->real_escape_string($data['status']);
                    $date = $conn->real_escape_string($data['date']);
                    
                    // Vérifier si l'ID existe dans la table Reservation
                    $result = $conn->query("SELECT id FROM Reservation WHERE id = $id");
                    if ($result->num_rows === 0) {
                        echo json_encode(["success" => false, "message" => "Aucune réservation trouvée avec cet ID."]);
                        exit;
                    }

                    // Construire la requête de mise à jour pour Reservation
                    $sql = "UPDATE Reservation SET status = '$status', date = '$date' WHERE id = $id";
                }
                break;

            case 'transport':
                // Vérifier si les champs nécessaires sont présents pour Transport
                if (isset($data['type'], $data['capacity'], $data['availability'])) {
                    $type = $conn->real_escape_string($data['type']);
                    $capacity = (int)$data['capacity'];
                    $availability = (bool)$data['availability'];
                    
                    // Vérifier si l'ID existe dans la table Transport
                    $result = $conn->query("SELECT id FROM Transport WHERE id = $id");
                    if ($result->num_rows === 0) {
                        echo json_encode(["success" => false, "message" => "Aucun transport trouvé avec cet ID."]);
                        exit;
                    }

                    // Construire la requête de mise à jour pour Transport
                    $sql = "UPDATE Transport SET type = '$type', capacity = $capacity, availability = $availability WHERE id = $id";
                }
                break;

            default:
                echo json_encode(["success" => false, "message" => "Type de mise à jour invalide."]);
                exit;
        }

        // Exécuter la requête
        if (isset($sql) && $conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => ucfirst($type) . " mis à jour avec succès."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur SQL : " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Type ou ID manquant."]);
    }
}

// Fermer la connexion
$conn->close();
?>
