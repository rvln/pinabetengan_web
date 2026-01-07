// ========== THEME TOGGLE (DARK MODE) ==========
const themeToggle = document.getElementById('themeToggle');
const themeIcon = themeToggle ? themeToggle.querySelector('i') : null;
const body = document.body;

// Function to apply theme
function applyTheme(theme) {
  if (theme === 'dark') {
    body.classList.add('dark-mode');
    if (themeIcon) {
      themeIcon.classList.remove('fa-moon');
      themeIcon.classList.add('fa-sun');
    }
  } else {
    body.classList.remove('dark-mode');
    if (themeIcon) {
      themeIcon.classList.remove('fa-sun');
      themeIcon.classList.add('fa-moon');
    }
  }
  localStorage.setItem('theme', theme);
}

// Check saved theme or system preference
const savedTheme = localStorage.getItem('theme');
const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

if (savedTheme) {
  applyTheme(savedTheme);
} else if (systemPrefersDark) {
  applyTheme('dark');
} else {
  applyTheme('light');
}

// Toggle theme on button click
if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    const currentTheme = body.classList.contains('dark-mode') ? 'dark' : 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);

    // Add a little animation feedback
    themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
    setTimeout(() => {
      themeToggle.style.transform = '';
    }, 300);
  });
}

// Listen to system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
  if (!localStorage.getItem('theme')) {
    applyTheme(e.matches ? 'dark' : 'light');
  }
});

// ========== NAVBAR SCROLL EFFECT ==========
const navbar = document.querySelector('.navbar-custom');

if (navbar) {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
}

// ========== SCROLL REVEAL ANIMATION ==========
const observerOptions = {
  threshold: 0.15,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry, index) => {
    if (entry.isIntersecting) {
      setTimeout(() => {
        entry.target.classList.add('revealed');
      }, index * 100);
    }
  });
}, observerOptions);

document.querySelectorAll('.scroll-reveal').forEach(el => {
  observer.observe(el);
});

// ========== COUNTER ANIMATION FOR STATS ==========
const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const numberElement = entry.target.querySelector('.stat-number');
      if (numberElement) {
        const target = parseInt(numberElement.textContent.replace(/\D/g, ''));
        let current = 0;
        const increment = target / 60;
        const duration = 2000; // 2 seconds
        const stepTime = duration / 60;

        const updateCounter = () => {
          if (current < target) {
            current += increment;
            numberElement.textContent = Math.ceil(current).toLocaleString('id-ID');
            setTimeout(updateCounter, stepTime);
          } else {
            numberElement.textContent = target.toLocaleString('id-ID');
          }
        };

        updateCounter();
      }
      counterObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.5 });

document.querySelectorAll('.stat-card').forEach(card => {
  counterObserver.observe(card);
});

// ========== SMOOTH SCROLL FOR ANCHOR LINKS ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const targetId = this.getAttribute('href');
    if (targetId === '#') return;

    const target = document.querySelector(targetId);
    if (target) {
      const offsetTop = target.offsetTop - 80;
      window.scrollTo({
        top: offsetTop,
        behavior: 'smooth'
      });
    }
  });
});

// ========== HERO BUTTON SCROLL ==========
const heroPrimaryBtn = document.querySelector('.btn-hero-primary');
if (heroPrimaryBtn) {
  heroPrimaryBtn.addEventListener('click', () => {
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
      const offsetTop = statsSection.offsetTop - 80;
      window.scrollTo({
        top: offsetTop,
        behavior: 'smooth'
      });
    }
  });
}

// ========== MOBILE NAVBAR ENHANCEMENTS ==========
function enhanceMobileNavbar() {
  const navbarToggler = document.querySelector('.navbar-toggler');
  const navbarCollapse = document.querySelector('.navbar-collapse');

  if (navbarToggler && navbarCollapse) {
    // Close navbar when clicking outside on mobile
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 991) {
        const isClickInsideNavbar = e.target.closest('.navbar');
        const isNavbarOpen = navbarCollapse.classList.contains('show');

        if (!isClickInsideNavbar && isNavbarOpen) {
          try {
            const bsCollapse = new bootstrap.Collapse(navbarCollapse);
            bsCollapse.hide();
          } catch (err) {
            // Handle if bootstrap is not loaded yet or similar
          }
        }
      }
    });

    // Smooth close animation for mobile
    navbarCollapse.addEventListener('show.bs.collapse', () => {
      document.body.style.overflow = 'hidden';
    });

    navbarCollapse.addEventListener('hidden.bs.collapse', () => {
      document.body.style.overflow = '';
    });
  }
}

// Initialize mobile enhancements
enhanceMobileNavbar();

// Re-initialize on window resize
window.addEventListener('resize', enhanceMobileNavbar);

// ========== MOBILE MENU CLOSE ON LINK CLICK ==========
const navLinks = document.querySelectorAll('.nav-link-custom');
const navbarCollapse = document.querySelector('.navbar-collapse');

if (navbarCollapse) {
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      if (navbarCollapse.classList.contains('show')) {
        try {
          const bsCollapse = new bootstrap.Collapse(navbarCollapse);
          bsCollapse.hide();
        } catch (err) {}
      }
    });
  });
}

// ========== ACTIVE NAV LINK ON SCROLL ==========
const sections = document.querySelectorAll('section[id]');

function highlightNavOnScroll() {
  const scrollY = window.pageYOffset;

  sections.forEach(current => {
    const sectionHeight = current.offsetHeight;
    const sectionTop = current.offsetTop - 100;
    const sectionId = current.getAttribute('id');

    if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
      document.querySelectorAll('.nav-link-custom').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${sectionId}`) {
          link.classList.add('active');
        }
      });
    }
  });
}

window.addEventListener('scroll', highlightNavOnScroll);

// ========== DROPDOWN SMOOTH ANIMATIONS ==========
document.addEventListener('DOMContentLoaded', function() {
  // Add smooth animation to dropdown items
  const dropdownItems = document.querySelectorAll('.dropdown-item');
  dropdownItems.forEach(item => {
    item.addEventListener('mouseenter', function() {
      this.style.transform = 'translateX(8px)';
    });

    item.addEventListener('mouseleave', function() {
      this.style.transform = 'translateX(0)';
    });
  });

  // Enhanced dropdown toggle animation
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function() {
      const dropdownMenu = this.nextElementSibling;
      if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
        if (dropdownMenu.style.display === 'block') {
          dropdownMenu.style.opacity = '0';
          dropdownMenu.style.transform = 'translateY(-10px)';
          setTimeout(() => {
            dropdownMenu.style.display = 'none';
          }, 300);
        }
      }
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
      const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
      openDropdowns.forEach(dropdown => {
        dropdown.classList.remove('show');
      });
    }
  });
});

// ========== PRELOAD IMAGES FOR SMOOTH TRANSITIONS ==========
window.addEventListener('load', () => {
  document.body.style.visibility = 'visible';
  document.body.style.opacity = '1';
});

// ========== CONSOLE WELCOME MESSAGE ==========
console.log('%c🌿 Desa Pinabetengan Selatan 🌿', 'color: #7CB342; font-size: 24px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);');
console.log('%c✨ Website dengan Dark Mode berhasil dimuat!', 'color: #C62828; font-size: 14px; font-weight: 600;');
console.log('%c💡 Tip: Tekan tombol bulan/matahari untuk toggle dark mode', 'color: #FFD54F; font-size: 12px;');
