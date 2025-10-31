  </div>
<div aria-live="polite" aria-atomic="true" class="position-relative">
  <div id="toastArea" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Função para aplicar o tema
function applyTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme); // Compatível com Bootstrap
}

// Carregar tema salvo no localStorage
const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark' || savedTheme === 'light') {
    applyTheme(savedTheme);
}

// Esperar o DOM carregar
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('themeToggle');
    if (btn) {
        btn.addEventListener('click', () => {
            // Verifica o tema atual
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
            // Alterna para o outro tema
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
            localStorage.setItem('theme', newTheme); // Salva preferência
        });
    }
});

// Essa função esta relacionada no helpers.php
function showToast(message, type) {
  var toastArea = document.getElementById('toastArea');
  if (!toastArea) return;
  var bg = 'text-bg-primary';
  if (type === 'success') bg = 'text-bg-success';
  else if (type === 'warning') bg = 'text-bg-warning';
  else if (type === 'danger' || type === 'error') bg = 'text-bg-danger';
  var el = document.createElement('div');
  el.className = 'toast align-items-center ' + bg;
  el.role = 'status';
  el.ariaLive = 'polite';
  el.ariaAtomic = 'true';
  el.innerHTML = '<div class="d-flex"><div class="toast-body">'+message+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>';
  toastArea.appendChild(el);
  var t = new bootstrap.Toast(el, { delay: 3500 });
  t.show();
}

// Se o script renderizar ai aparece um toast
//Link do que é toast no bootstrap https://getbootstrap.com/docs/5.3/components/toasts/
document.addEventListener('DOMContentLoaded', function() {
  var script = document.getElementById('flashToastsScript');
  if (script && script.textContent) {
    try { (new Function(script.textContent))(); } catch (e) {}
  }
});
</script>
</body>
</html>
