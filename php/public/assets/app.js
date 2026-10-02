// Minimal progressive enhancement: mobile menu + light/dark toggle.
(function () {
  var toggle = document.querySelector('.menu-toggle');
  var nav = document.querySelector('.mobile-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.hasAttribute('hidden');
      if (open) {
        nav.removeAttribute('hidden');
        toggle.setAttribute('aria-expanded', 'true');
      } else {
        nav.setAttribute('hidden', '');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  var themeKey = 'swiftship-theme';
  var root = document.documentElement;
  try {
    if (localStorage.getItem(themeKey) === 'dark') root.classList.add('dark');
  } catch (e) {}

  document.querySelectorAll('[data-theme-toggle]').forEach(function (button) {
    button.addEventListener('click', function () {
      var dark = root.classList.toggle('dark');
      try {
        localStorage.setItem(themeKey, dark ? 'dark' : 'light');
      } catch (e) {}
      button.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
    });
  });
})();
