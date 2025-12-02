<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | FisioVida</title>
    <link rel="icon" href="../img/Icone fisiovida.png">
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"/>
      <link rel="stylesheet" href="../css/logincadastro.css"/>
</head>
<body>
  <div class="container my-5">
    <div class="row align-items-center">
      <!-- Coluna do Formulário e textos -->
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h2 class="mb-3 text-center" style="color:#0b8ecb; font-weight: 700;">
          Login de Usuário
        </h2>
        <p class="text-center">
          Acesse sua conta para agendar sessões e acompanhar sua evolução.
        </p>

        <form class="row g-3 mt-4" method="POST" action="../php/logar.php">
          <div class="col-12">
            <label for="email" class="form-label">Email:</label>
            <input
              type="email"
              class="form-control"
              id="email"
              name="email"
              placeholder="Digite seu email"
              required
            />
          </div>
          <div class="col-12">
            <label for="senha" class="form-label">Senha:</label>
            <input
              type="password"
              class="form-control"
              id="senha"
              name="senha"
              placeholder="Digite sua senha"
              required
            />
          </div>
          
          <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="lembrar" name="lembrar" />
              <label class="form-check-label" for="lembrar">Lembrar de mim</label>
            </div>
            <a href="../php/esqueci_senha.php" class="text-decoration-none">Esqueceu a senha?</a>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary px-5">Entrar</button>
          </div>
          <div class="col-12 text-center">
            <p class="mt-3">
              Não tem uma conta? <a href="../site/cadastro.php">Cadastre-se</a>
            </p>
            <p>
              <a href="index.html">Voltar para a pagina inicial</a>
            </p>
          </div>
        </form>
      </div>

      <!-- Coluna da imagem -->
      <div class="col-lg-6 d-flex justify-content-center">
      <a href="index.html">  
      <img
          src="../img/Fisiovida logo.png"
          alt="Imagem Fisioterapia"
          class="img-fluid"
          style="max-height: 450px; object-fit: contain; border: none; box-shadow: none;"
        />
      </a>
      </div>
    </div>
  </div>
   <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="../js/notificacoes.js"></script>

  <?php if (!empty($_SESSION['msg'])): ?>
  <script>
  mostrarMensagem("<?= $_SESSION['msg'] ?>", "<?= $_SESSION['msg_tipo'] ?>" === "sucesso");
  </script>
  <?php 
  unset($_SESSION['msg']);
  unset($_SESSION['msg_tipo']);
  endif;
  ?>

<script>
// Quando a página carregar
document.addEventListener("DOMContentLoaded", function() {

    const inputSenha = document.getElementById("senha");

    if (!inputSenha) return;

    // Criar um container (input-group) em volta do input existente
    const wrapper = document.createElement("div");
    wrapper.classList.add("input-group", "mb-3");

    // Inserir wrapper antes do campo original
    inputSenha.parentNode.insertBefore(wrapper, inputSenha);

    // Mover o input para dentro
    wrapper.appendChild(inputSenha);

    // Criar o botão de mostrar senha
    const btn = document.createElement("button");
    btn.type = "button";
    btn.classList.add("btn", "btn-outline-secondary");
    btn.innerHTML = '<i class="bi bi-eye"></i>';

    wrapper.appendChild(btn);

    // Alternar tipo da senha
    btn.addEventListener("click", function () {
        const icon = btn.querySelector("i");

        if (inputSenha.type === "password") {
            inputSenha.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            inputSenha.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    });

});
</script>

<!-- Bootstrap Icons (se ainda não estiver carregado) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
document.addEventListener("DOMContentLoaded", function () {
    const email = document.getElementById("email");
    const lembrar = document.getElementById("lembrar");

    // Se houver email salvo, preencher e marcar o checkbox
    if (localStorage.getItem("lembrar_email") === "true") {
        email.value = localStorage.getItem("email_salvo") ?? "";
        lembrar.checked = true;
    }

    // Quando o usuário marcar ou desmarcar o checkbox
    lembrar.addEventListener("change", function () {
        if (this.checked) {
            localStorage.setItem("email_salvo", email.value);
            localStorage.setItem("lembrar_email", "true");
        } else {
            localStorage.removeItem("email_salvo");
            localStorage.removeItem("lembrar_email");
        }
    });

    // Atualiza o email salvo caso o usuário edite o campo
    email.addEventListener("input", function () {
        if (lembrar.checked) {
            localStorage.setItem("email_salvo", email.value);
        }
    });
});
</script>


</body>
</html>
