<?php require_once APP_ROOT . '/views/header.php'; ?>

<section>
    <p class="title container">
        Partagez vos trajets, préservez la planète :<br>
        la mobilité responsable à portée de clic.
    </p>
    <div class="search-container">
        <div class="search-image active">
            <img src="/images/route1.jpg" alt="...">
        </div>
        <form class="search-form">
            <input type="text" placeholder="Départ" name="depart">
            <input type="text" placeholder="Arrivée" name="arrivee">
            <input type="date" name="date">
            <button type="submit">Rechercher</button>
        </form>
    </div>
</section>
<section class="intro-section" style="padding: 40px; text-align: center; max-width: 900px; margin: auto;">
    <h2 style="font-size: 2em; font-weight: bold; margin-bottom: 20px;">
        Rejoignez la mobilité de demain
    </h2>
    <p>
        Notre plateforme révolutionne la mobilité partagée en connectant chauffeurs et passagers avec une priorité claire : <strong>réduire l’impact environnemental</strong>.
        <br><br>
        Contrairement aux services classiques, chaque utilisateur choisit librement son rôle selon ses besoins du moment : <strong>conducteur ou passager</strong>.
        <br><br>
        Grâce à un <strong>algorithme intelligent</strong>, nous privilégions :

    <p>les véhicules électriques pour les trajets urbains</p>
    <p>les véhicules sobres pour les longues distances</p>

    Cette approche personnalisée <strong>réduit l’empreinte carbone</strong> de chaque trajet.
    <br><br>
    En rejoignant notre communauté, vous contribuez à une <strong>mobilité plus responsable</strong>, tout en profitant d’un service <strong>flexible, économique et éthique</strong>.
    </p>

</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>