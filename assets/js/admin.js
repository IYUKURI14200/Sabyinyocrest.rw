/* assets/js/admin.js — small admin panel behaviours */
document.addEventListener('DOMContentLoaded', function () {
  // Confirm before any destructive action
  document.querySelectorAll('[data-confirm]').forEach(elx => {
    elx.addEventListener('click', function (e) {
      if (!confirm(elx.getAttribute('data-confirm') || 'Are you sure?')) {
        e.preventDefault();
      }
    });
  });

  // Live preview for file inputs
  document.querySelectorAll('input[type=file][data-preview]').forEach(input => {
    input.addEventListener('change', function () {
      const target = document.getElementById(input.getAttribute('data-preview'));
      if (target && input.files && input.files[0]) {
        target.src = URL.createObjectURL(input.files[0]);
      }
    });
  });

  // Auto-dismiss alerts
  document.querySelectorAll('.alert[data-autohide]').forEach(a => {
    setTimeout(() => { a.style.transition = 'opacity .4s'; a.style.opacity = '0'; setTimeout(()=>a.remove(), 400); }, 4000);
  });
});
