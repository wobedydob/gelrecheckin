<?php declare(strict_types=1); ?>

<footer>
    <div class="footer-content">
        <div class="footer-section about">
            <h2>GelreCheckin</h2>
            <p>Samen streven voor een gemakkelijk incheckproces.</p>
        </div>
        <div class="footer-section links">
            <h2>Snelle Links</h2>
            <ul>
                <li><a href="<?php echo site_url(); ?>">Home</a></li>

                <?php if(!auth()->isAuthenticated()): ?>
                    <li><a href="<?php echo site_url('inloggen/passagier'); ?>">Inloggen als Passagier</a></li>
                    <li><a href="<?php echo site_url('inloggen/medewerker'); ?>">Inloggen als Medewerker</a></li>
                <?php elseif(auth()->isAuthenticated()): ?>
                    <li><a href="<?php echo site_url('logout'); ?>">Uitloggen</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="footer-section contact">
            <h2>Neem contact op</h2>
            <p>E-mail: support@gelrecheckin.nl</p>
            <p>Telefoon: 026 811 2820</p>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; 2024 wuppo. All rights reserved.
    </div>
</footer>