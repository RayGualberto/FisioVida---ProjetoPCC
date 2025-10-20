<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | FisioVida</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  />
</head>
<body>
  <div class="container my-5">
    <div class="row align-items-center">
      <!-- Coluna do Formulário e textos -->
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h2 class="mb-3 text-center" style="color:#198754; font-weight: 700;">
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
            <a href="#" class="text-decoration-none">Esqueceu a senha?</a>
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary px-5">Entrar</button>
          </div>
          <div class="col-12 text-center">
            <p class="mt-3">
              Não tem uma conta? <a href="../site/cadastro.php">Cadastre-se</a>
            </p>
          </div>
        </form>
      </div>

      <!-- Coluna da imagem -->
      <div class="col-lg-6 d-flex justify-content-center">
        <img
          src="../img/Fisiovida logo.png"
          alt="Imagem Fisioterapia"
          class="img-fluid"
          style="max-height: 450px; object-fit: contain; border: none; box-shadow: none;"
        />
      </div>
    </div>
  </div>
</body>
</html>
