  </div>
<div aria-live="polite" aria-atomic="true" class="position-relative">
  <div id="toastArea" class="toast-container position-fixed top-0 end-0 p-3"></div>
</div>
 <!-- Rodapé -->
 <footer class="bg-light text-center text-lg-start mt-auto">
    <div class="container p-4">
      <div class="row">
        <div class="col-lg-3 col-md-12 mb-4 mb-md-0" data-aos="fade-right">
          <h5 class="text-uppercase">Fisiovida</h5>
          <p>
            Endereço: Rua Exemplo, 123 - Cidade, Estado<br>
            Telefone: +55 12 3456-7890<br>
            Email: fisiovidarmnf@gmail.com
          </p>
        </div>

        <div class="col-lg-3 col-md-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
          <h5 class="text-uppercase mb-0">Redes sociais</h5>
          <ul class="list-unstyled d-flex justify-content-start gap-3 mt-3">
            <li><a href="#!" class="text-dark"><i class="bi bi-facebook fs-4"></i></a></li>
            <li><a href="#!" class="text-dark"><i class="bi bi-instagram fs-4"></i></a></li>
            <li><a href="#!" class="text-dark"><i class="bi bi-twitter fs-4"></i></a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-12 mb-4 mb-md-0" data-aos="fade-left" data-aos-delay="400">
          <h5 class="text-uppercase">Slogan</h5>
          <p>
            Conectamos você a fisioterapeutas qualificados, oferecendo praticidade no agendamento e cuidado humano em cada sessão
          </p>
        </div>
      </div>
    </div>
    <div class="text-center p-3 bg-secondary text-white">
        © 2025 Fisiovida. Todos os direitos reservados.
    </div>
  </footer>

      <!-- Bootstrap + AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <script>
        // Inicializa AOS
        AOS.init({
            duration: 700,
            once: true,
            easing: 'ease-out-cubic'
        });

        // Pequeno ajuste: pausa o carousel quando o usuário focar realizando interação (acessibilidade)
        document.querySelectorAll('.carousel').forEach(car => {
            // obtém (ou cria) a instância do Bootstrap Carousel associada ao elemento
            const bsCarousel = bootstrap.Carousel.getInstance(car) || new bootstrap.Carousel(car);

            // pausa ao entrar com o mouse
            car.addEventListener('mouseenter', () => {
                bsCarousel.pause();
            });

            // retoma o ciclo ao sair do mouse
            car.addEventListener('mouseleave', () => {
                bsCarousel.cycle();
            });
        });
    </script>
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
