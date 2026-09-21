<div class="modal-confirm">
    <div class="modal-box">
        <div class="modal-title">
            <p><?= $modal_message ?></p>
        </div>
        <div class="modal-icon">
            <?= $modal_icon ?>
        </div>
        <div class="modal-cta">
            <button>Fermer</button>
        </div>
    </div>
</div>
<div class="modal-alert">
    <div class="modal-box">
        <div class="modal-title">
            <p><?= $alert_message ?></p>
        </div>
        <div class="modal-icon">
            <i class='bx bx-info-circle'></i>
        </div>
        <div class="modal-cta">
            <button>Confirmer</button>
            <button id='cancel'>Annuler</button>
        </div>
    </div>
</div>