<?php
/**
 * CTC Bank — Main Layout Footer
 */
?>
        </main> <!-- /mainContent -->

        <!-- Banking Portal Footer -->
        <footer class="app-footer">
            <div class="footer-container">
                <div class="footer-col-info">
                    <span class="footer-bank-name"><?= e(APP_FULL_NAME) ?></span>
                    <p class="footer-subtext">Enterprise Treasury Management &amp; Core Infrastructure Portal.</p>
                </div>
                <div class="footer-col-disclaimer">
                    <p class="footer-warning">
                        <strong>Confidential Banking System:</strong> Authorized internal personnel only. All access, operations, and diagnostic queries are logged and monitored in compliance with banking regulatory standards.
                    </p>
                </div>
                <div class="footer-col-meta">
                    <span class="footer-version">System Version <?= e(APP_VERSION) ?></span>
                    <span class="footer-copyright">&copy; <?= date('Y') ?> Techtonic Bank. All rights reserved.</span>
                </div>
            </div>
        </footer>
    </div> <!-- /app-main-content -->
</div> <!-- /app-layout -->

<!-- Application Scripts -->
<script src="/assets/js/app.js"></script>
<?php if (isset($extraJs)): ?>
    <?php foreach ($extraJs as $jsFile): ?>
        <script src="<?= e($jsFile) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
