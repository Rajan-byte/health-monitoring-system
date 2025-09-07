</main>
<footer class="site-footer">
<div class="wrap">
<small>&copy; <?= date('Y') ?> Health Monitoring System 
<?php if(isset($_SESSION['user_id'])): ?>
 | Logged in as <strong><?= htmlspecialchars($_SESSION['user_email'] ?? 'User') ?></strong>
<?php endif; ?>
</small>
</div>
</footer>
</body>
</html>
