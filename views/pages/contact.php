<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="login flex-grow-1 d-flex align-items-center justify-content-center py-5">
    <!-- Carte contenant le formulaire -->
    <div class="ecoride-card p-4">
        <h2 class="form-title text-center mb-4">Contactez nous !</h2>
        <form id="contactForm" method="post">
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
            <div class="row">
                <div class="col">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" id="email" placeholder="exemple@ecoride.com" required>
                    <div class="valid-feedback">Adresse email valide</div>
                    <div class="invalid-feedback">Veuillez saisir une adresse email valide.</div>
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Télephone</label>
                    <input type="tel" class="form-control" id="phone" placeholder="06-01-02-04-05" required>
                    <div class="invalid-feedback">Veuillez saisir un numéro de téléphone valide.</div>
                </div>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Sujet</label>
                <input type="text" class="form-control" id="subject" placeholder="Covoiturage" required>
                <div class="invalid-feedback">Veuillez saisir un sujet.</div>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <input type="text" style="height: 100px;" class="form-control" id="message" placeholder="Saisez votre message" required>
                <div class="invalid-feedback">Veuillez saisir un message.</div>
            </div>

            <button type="submit" id="submit" class="btn ecoride-btn w-100">Envoyer</button>
        </form>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>