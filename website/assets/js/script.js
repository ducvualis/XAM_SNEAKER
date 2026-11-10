document.addEventListener('DOMContentLoaded', () => {
  const button = document.querySelector('.menu-toggle');
  const nav = document.querySelector('#site-nav');
  if (button && nav) {
    button.addEventListener('click', () => {
      const visible = nav.classList.toggle('show');
      button.setAttribute('aria-expanded', visible ? 'true' : 'false');
    });
  }
  document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
      if (!confirm(form.getAttribute('data-confirm'))) event.preventDefault();
    });
  });
});
