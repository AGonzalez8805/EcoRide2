<?php require_once APP_ROOT . '/views/header.php'; ?>

<!-- Section Hero -->
<section>
    <p class="title container">
        Partagez vos trajets, préservez la planète :<br>
        la mobilité responsable à portée de clic.
    </p>
    <div class="search-container">
        <div class="search-image active">
            <img src="/images/route1.jpg" alt="...">
        </div>
        <form class="search-form" method="get" action="/?controller=results">
            <input type="text" placeholder="Départ" name="depart">
            <input type="text" placeholder="Arrivée" name="arrivee">
            <input type="date" name="date">
            <button type="submit">Rechercher</button>
        </form>
    </div>
</section>
<!-- Section Introduction -->
<section>
    <div class="intro-section">
        <div class="intro-title">
            <h2>Rejoignez la mobilité de demain</h2>
        </div>
        <div class="intro-content">
            <div class="text-intro">
                <p>
                    Notre plateforme révolutionne la mobilité partagée en connectant chauffeurs et passagers avec une priorité claire :
                    <strong>réduire l'impact environnemental. </strong>
                    <br><br>
                    Contrairement aux services classiques, chaque utilisateur choisit librement son rôle selon ses besoins du moment :
                    <strong>conducteur ou passager. </strong>
                    <br><br>
                    Grâce à un algorithme intelligent, nous privilégions :<br>
                    <strong>les véhicules électriques pour les trajets urbains <br>
                        les véhicules sobres pour les longues distances</strong><br>
                    Cette approche personnalisée réduit l'empreinte carbone de chaque trajet.<br><br>
                    En rejoignant notre communauté, vous contribuez à une mobilité plus responsable, tout en profitant d'un service flexible, économique et éthique.
                </p>
            </div>
            <div class="photo-intro">
                <img src="/images/ecorideAccueil.png" alt="">
            </div>
        </div>
    </div>
</section>
<!-- Section Comment ça marche  -->
<section class="works-section">
    <div class="container">
        <h2>Comment ça marche ?</h2>
        <p class="text-center mb-5" style="font-size: 1.2rem; color: #666;">En 3 étapes simples</p>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h4>Inscrivez-vous</h4>
                    <p>Créez votre compte gratuitement en quelques minutes. Vérifiez votre profil pour plus de confiance.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h4>Recherchez ou proposez</h4>
                    <p>Cherchez un trajet qui vous convient ou proposez le vôtre. Notre algorithme trouve les meilleures correspondances.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h4>Voyagez et évaluez</h4>
                    <p>Rencontrez votre covoitureur, profitez du trajet et laissez un avis pour aider la communauté.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Témoignages -->
<section class="avis-section">
    <div class="container">
        <h2>Ils nous font confiance</h2>

        <div class="testimonial-grid">
            <div class="testimonial-card">
                <p class="testimonial-text">
                    J'ai économisé plus de 200€ par mois sur mes trajets domicile-travail tout en contribuant à la protection de l'environnement.
                </p>
                <div class="testimonial-author">Marie L.</div>
                <div class="testimonial-role">Utilisatrice depuis 8 mois</div>
            </div>

            <div class="testimonial-card">
                <p class="testimonial-text">
                    Super communauté ! J'ai rencontré des personnes formidables et mes trajets Paris-Lyon sont devenus un plaisir.
                </p>
                <div class="testimonial-author">Julien M.</div>
                <div class="testimonial-role">Conducteur régulier</div>
            </div>

            <div class="testimonial-card">
                <p class="testimonial-text">
                    Application très intuitive et service client réactif. Je recommande EcoRide à tous mes collègues !
                </p>
                <div class="testimonial-author">Sarah K.</div>
                <div class="testimonial-role">Passagère occasionnelle</div>
            </div>
        </div>

        <div class="text-center">
            <a href="/?controller=avis&action=avis" class="btn-review">Déposer un avis</a>
        </div>
    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>