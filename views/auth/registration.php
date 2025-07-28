<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="login flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <!-- Carte contenant le formulaire -->
    <div class="ecoride-card p-4">
        <h2 class="form-title text-center mb-4">Inscription à Ecoride</h2>
        <form id="registrationForm" method="post">
            <div class="row">
                <div class="col">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="name" placeholder="Bois" aria-label="First name">
                    <div class="valid-feedback">Saisie correcte</div>
                    <div class="invalid-feedback">Veuillez renseigner votre nom.</div>
                </div>
                <div class="col">
                    <label for="firstName" class="form-label">Prenom</label>
                    <input type="text" class="form-control" id="firstName" placeholder="Jose" aria-label="Last name">
                    <div class="valid-feedback">Saisie correcte</div>
                    <div class="invalid-feedback">Veuillez renseigner votre prénom.</div>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" placeholder="exemple@ecoride.com" required>
                <div class="valid-feedback">Adresse email valide</div>
                <div class="invalid-feedback">Veuillez saisir une adresse email valide.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" placeholder="••••••••" required>
                <small class="text-muted" style="text-decoration: underline;">Le mot de passe doit contenir :</small>
                <ul class="list-unstyled small mt-1 mb-3" id="passwordCriteria">
                    <li id="length" class="text-danger"> Au moins 9 caractères</li>
                    <li id="uppercase" class="text-danger"> Une majuscule</li>
                    <li id="lowercase" class="text-danger"> Une minuscule</li>
                    <li id="number" class="text-danger"> Un chiffre</li>
                    <li id="special" class="text-danger"> Un caractère spécial</li>
                </ul>
            </div>
            <div class="mb-3">
                <label for="validatePassword" class="form-label">Confirmer votre mot de passe</label>
                <input type="password" class="form-control" id="validatePassword" placeholder="••••••••" required>
                <div class="valid-feedback">Mot de passe confirmé</div>
                <div class="invalid-feedback">Les mots de passe ne correspondent pas.</div>
            </div>
            <button type="submit" id="register" class="btn ecoride-btn w-100">S'inscrire</button>
            <div class="text-center pt-3">
                <font style="text-decoration: underline;">
                    <a href="/?controller=auth&action=login">Vous avez déjà un compte ? Connectez-vous ici !</a>
                </font>
            </div>
        </form>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>