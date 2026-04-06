// Simple mobile menu toggle
const btn = document.getElementById('mobile-menu-btn');
const menu = document.getElementById('mobile-menu');
const openIcon = document.getElementById('menu-open');
const closeIcon = document.getElementById('menu-close');

btn.addEventListener('click', () => {
  menu.classList.toggle('hidden');
  openIcon.classList.toggle('hidden');
  closeIcon.classList.toggle('hidden');
});

document.addEventListener('DOMContentLoaded', () => {
  const currentUrl = window.location.pathname;          // e.g. "/home", "/membership", "/"
  const links = document.querySelectorAll('.nav-link'); // we'll add this class to every <a>

  links.forEach(link => {
    // Remove trailing slash for comparison (optional but cleaner)
    const linkPath = new URL(link.href).pathname.replace(/\/$/, '');
    const pagePath = currentUrl.replace(/\/$/, '');

    if (linkPath === pagePath) {
      link.classList.add('active-nav');
    }
  });
});