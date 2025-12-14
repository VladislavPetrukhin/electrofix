</main>

<footer class="site-footer mt-5">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
    <div>© <?= date('Y') ?> ElectroFix • HTML5 + CSS3 + Bootstrap + JS + PHP + MySQL</div>
    <div class="text-secondary small">
      <?= is_logged_in() ? 'Вы вошли как: <b>' . h(current_user()['username']) . '</b>' : 'Гость' ?>
    </div>
  </div>
</footer>

<!-- jQuery (lab 4) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Main JS -->
<script src="<?= BASE_URL ?>/js/main.js"></script>

</body>
</html>
