<!DOCTYPE html>
<html lang="fr">

<body>
    <div class="modal-alert" hidden>
        <div class="modal-title">
            <p>Vous êtes sur le point de <?= $modal_alert ?></p>
        </div>
        <div class="modal-icon">
            <i class='bx bxs-id-card'></i>
        </div>
        <div>
            <p>Confirmez vous cette action ?</p>
        </div>
        <div class="modal-cta">
            <button>Confirmer</button>
            <button>Annuler</button>
        </div>
    </div>
    <div class="modal-confirm" hidden>
        <div class="modal-icon">
            <?= $modal_icon ?>
        </div>
        <div>
            <p><?= $modal_message ?></p>
        </div>
        <div class="modal-cta">
            <button>Fermer</button>
        </div>
    </div>
</body>

<!-- Réservation  -->
<i class='bx bxs-coupon'></i>
<i class='bx bxs-purchase-tag-alt'></i>

<!-- Compte créé -->
Clé => <i class='bx bxs-key'></i> || <i class='bx bx-key'></i>
Fête => <i class='bx bxs-party'></i> || <i class='bx bxs-check-circle'></i>
Cadeau => <i class='bx bxs-gift'></i>

<!-- Profil modifié -->
Utilisateur => <i class='bx bxs-user-check'></i> || <i class='bx bx-user-check'></i>
Profil / Badge => <i class='bx bxs-id-card'></i> || <i class='bx bxs-user-badge'></i>