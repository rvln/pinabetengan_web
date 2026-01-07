<!-- FOOTER -->
<footer class="footer-section">
  <div class="container footer-content">
    <div class="text-center">
      <h3 class="footer-brand">Desa Pinabetengan Selatan</h3>
      <p class="footer-info">
        Jl. Desa Pinabetengan Selatan, Kec. Tompaso Baru<br>
        Kab. Minahasa Selatan, Sulawesi Utara<br>
        <i class="fas fa-phone"></i> (0431) 123-456 |
        <i class="fas fa-envelope"></i> info@pinabetenganselatan.desa.id
      </p>
      <div class="social-links">
        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
        <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
        <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
      </div>
      <div class="footer-bottom">
        <p>&copy; <?= date('Y') ?> Desa Pinabetengan Selatan. All rights reserved.</p>
      </div>
    </div>
  </div>
</footer>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $theme_script ?? '' ?>
<script>
// Navbar scroll effect
const navbar = document.querySelector('.navbar-custom');
if(navbar) {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
}

// Fade in animations
const fadeElements = document.querySelectorAll('.fade-in, .scroll-reveal');
fadeElements.forEach(el => {
  setTimeout(() => el.classList.add('revealed', 'show'), 100);
});

// Dropdown smooth animations
const dropdownItems = document.querySelectorAll('.dropdown-item');
dropdownItems.forEach(item => {
  item.addEventListener('mouseenter', function() {
    this.style.transform = 'translateX(8px)';
  });

  item.addEventListener('mouseleave', function() {
    this.style.transform = 'translateX(0)';
  });
});
</script>
</body>
</html>
