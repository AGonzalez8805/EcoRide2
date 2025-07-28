</main>
<!-- === Pied de page (footer) du site === -->
<footer>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <!-- Colonne Contact -->
            <div class="footer-col mb-3">
                <h5>Contact</h5>
                <p class="mb-1">Email : contact@ecoride.com</p>
                <p>Tél : +33 1 23 45 67 89</p>
            </div>

            <div class="col">
                <!-- Menu de navigation secondaire centré dans le footer -->
                <ul class="nav justify-content-center border-bottom border-black pb-3 mb-1">
                    <li class="nav-item">
                        <a href="/?controller=pages&action=mentions" class="nav-link px-2 text-body-secondary <?= $currentPage === 'mentions' ? 'active-link' : '' ?>">Mentions Légales</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link px-2 text-body-secondary">A propos</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link px-2 text-body-secondary">FAQs</a>
                    </li>
                </ul>
                <!-- Texte centré sous le menu -->
                <p class="text-center mb-0">&copy; EcoRide. Tous droits réservés.</p>
            </div>
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>