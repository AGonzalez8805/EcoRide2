<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="login flex-grow-1 d-flex align-items-center justify-content-center">
    <!-- Carte contenant le formulaire -->
    <div class="ecoride-card-login p-4">
        <h2 class="form-title text-center mb-4">Se connecter à Ecoride</h2>
        <div id="loginError" class="alert alert-danger d-none" role="alert"></div>

        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" placeholder="exemple@ecoride.com" required>
                <div class="valid-feedback">Adresse email valide</div>
                <div class="invalid-feedback">Veuillez saisir une adresse email valide.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" placeholder="••••••••" required>
                <div class="invalid-feedback">Mot de passe requis</div>
            </div>

            <button type="submit" id="login" class="btn ecoride-btn w-100">Se connecter</button>

            <div class="text-center pt-3">
                <font style="text-decoration: underline;">
                    <a href="/?controller=auth&action=registration">Vous n'avez pas de compte ? Inscrivez-vous ici !</a>
                </font>
            </div>
        </form>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>