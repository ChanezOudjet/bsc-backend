<?php
function makeReservation($hauberge_id, $resident_id, $numero_chambre, $date_entree, $date_sortie, $nature_reservation, $restauration_montant, $pdo) {
    try {
        // Vérifiez si la chambre est disponible
        $stmt = $pdo->prepare("SELECT * FROM Reservation WHERE hauberge_id = ? AND numero_chambre = ? AND 
            (date_entree <= ? AND date_sortie >= ?)");
        $stmt->execute([$hauberge_id, $numero_chambre, $date_sortie, $date_entree]);
        $reservation_exists = $stmt->fetch();

        if ($reservation_exists) {
            return "La chambre n'est pas disponible ou l'auberge est pleine.";
        }

        // Insérez la réservation
        $stmt = $pdo->prepare("INSERT INTO Reservation (hauberge_id, resident_id, numero_chambre, date_entree, date_sortie, nature_reservation, restauration_montant) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$hauberge_id, $resident_id, $numero_chambre, $date_entree, $date_sortie, $nature_reservation, $restauration_montant]);

        return "Réservation effectuée avec succès.";
    } catch (PDOException $e) {
        return "Erreur : " . $e->getMessage();
    }
}
?>
