<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="login flex-grow-1 d-flex align-items-center justify-content-center py-4">
    <div class="ecoride-card p-5">
        <h2 class="form-title text-center mb-4">
            Laisser un avis
        </h2>
        <!-- Message de succès (masqué par défaut) -->
        <div id="successMessage" class="alert alert-success text-center" style="display: none;">
            <i class="fas fa-check-circle me-2"></i>
            Merci pour votre avis ! Il sera visible après modération.
        </div>
        <form id="avisForm" method="post">
            <!-- Pseudo -->
            <div class="mb-3">
                <label for="pseudo" class="form-label">
                    <i class="fas fa-user me-2"></i>Pseudo
                </label>
                <input type="text" class="form-control" id="pseudo" name="pseudo"
                    placeholder="Entrez votre pseudo">
                <div class="valid-feedback">
                    <i class="fas fa-check me-1"></i>Saisie correcte
                </div>
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-triangle me-1"></i>Veuillez renseigner votre pseudo.
                </div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i>Adresse email
                </label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="exemple@ecoride.com">
                <div class="valid-feedback">
                    <i class="fas fa-check me-1"></i>Adresse email valide
                </div>
                <div class="invalid-feedback">
                    <i class="fas fa-exclamation-triangle me-1"></i>Veuillez saisir une adresse email valide.
                </div>
            </div>

            <!-- Note -->
            <div class="mb-4">
                <label class="form-label">
                    Votre note globale
                </label>
                <div class="rating-section">
                    <div class="star-rating" id="starRating">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                    <div class="rating-text" id="ratingText">
                        Cliquez pour noter votre expérience
                    </div>
                    <input type="hidden" id="rating" name="rating">
                </div>
            </div>

            <!-- Commentaire -->
            <div class="mb-4">
                <label for="commentaire" class="form-label">
                    Votre commentaire
                </label>
                <textarea id="commentaire" name="commentaire" class="form-control"
                    placeholder="Décrivez votre expérience avec EcoRide..."
                    rows="5" maxlength="500"></textarea>
                <div class="char-counter" id="charCounter">0/500 caractères</div>
                <div class="invalid-feedback">
                    Veuillez rédiger un commentaire (minimum 10 caractères).
                </div>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" id="publish" class="btn ecoride-btn w-100">
                Publier mon avis
            </button>
        </form>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>