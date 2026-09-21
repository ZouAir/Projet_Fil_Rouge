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
<div class="modal-alert" hidden>
    <div class="modal-title">
        <p><?= $alert_message ?></p>
    </div>
    <div class="modal-icon">
        <i class='bx bxs-id-card'></i>
    </div>
    <div class="modal-cta">
        <button>Confirmer</button>
        <button id='cancel'>Annuler</button>
    </div>
</div>