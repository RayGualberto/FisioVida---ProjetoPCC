<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FisioVida - Cadastro</title>
  <link rel="icon" href="img/Fisiovida logo.png" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <link rel="stylesheet" href="../css/style.css"/>

  <!-- Adicionando JQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src="server.js"></script>

  <!-- Adicionando Javascript -->
  <script>
    $(document).ready(function() {

        function limpa_formulário_cep() {
            $("#rua").val("");
            $("#bairro").val("");
            $("#cidade").val("");
            $("#uf").val("");
        }
        
        $("#cep").blur(function() {
            var cep = $(this).val().replace(/\D/g, '');
            if (cep != "") {
                var validacep = /^[0-9]{8}$/;
                if(validacep.test(cep)) {
                    $("#rua").val("...");
                    $("#bairro").val("...");
                    $("#cidade").val("...");
                    $("#uf").val("...");

                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                        if (!("erro" in dados)) {
                            $("#rua").val(dados.logradouro);
                            $("#bairro").val(dados.bairro);
                            $("#cidade").val(dados.localidade);
                            $("#uf").val(dados.uf);
                        }
                        else {
                            limpa_formulário_cep();
                            alert("CEP não encontrado.");
                        }
                    });
                }
                else {
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            }
            else {
                limpa_formulário_cep();
            }
        });
    });
  </script>

  <!-- Máscara CPF -->
  <script>
    $(document).ready(function(){
      $('#cpf').mask('000.000.000-00');
    });
  </script>

  <!-- Máscara para telefone -->

  <script>
$(document).ready(function(){
  $('#telefone').mask('(00) 00000-0000');
});
</script>

<!-- Máscara para CEP -->
<script>
$(document).ready(function(){
  $('#cep').mask('00000-000');
});
</script>

<!-- Validar CPF -->

<script>
$(document).ready(function() {

  $("form").on("submit", function(e) {
  if ($("#cpf").hasClass("is-invalid")) {
    e.preventDefault();
    alert("Corrija o CPF antes de enviar o formulário!");
  }
});


  $("#cpf").on("blur", function() {
    const cpf = $(this).val();

    if (cpf.trim() === "") return;

    $.ajax({
      url: "../site/validar_cpf.php", // ajuste o caminho conforme a estrutura
      method: "POST",
      dataType: "json",
      data: { cpf: cpf },
      success: function(response) {
        if (response.valido) {
          $("#cpf").removeClass("is-invalid").addClass("is-valid");
          if ($("#cpf-feedback").length === 0) {
            $("<div id='cpf-feedback' class='valid-feedback'>CPF válido ✅</div>")
              .insertAfter("#cpf");
          } else {
            $("#cpf-feedback")
              .removeClass("invalid-feedback")
              .addClass("valid-feedback")
              .text("CPF válido ✅");
          }
        } else {
          $("#cpf").removeClass("is-valid").addClass("is-invalid");
          if ($("#cpf-feedback").length === 0) {
            $("<div id='cpf-feedback' class='invalid-feedback'>CPF inválido ❌</div>")
              .insertAfter("#cpf");
          } else {
            $("#cpf-feedback")
              .removeClass("valid-feedback")
              .addClass("invalid-feedback")
              .text("CPF inválido ❌");
          }
        }
      },
      error: function() {
        console.error("Erro ao validar CPF.");
      },
    });
  });
});
</script>

</head>
<body> 

  <!-- Seção Cadastro -->
  <div class="container my-5">
    <h2 class="text-center mb-4">Cadastro</h2>
    <p class="text-center">Crie sua conta para agendar sessões e acompanhar sua evolução.</p>
  
    <form class="row g-3 mt-4" novalidate action="../php/cadastrar.php" method="POST">
      <div class="col-md-6">
        <label for="nome" class="form-label">Nome Completo:</label>
        <input type="text" class="form-control" id="nome" name="nome" placeholder="Seu nome completo" required />
      </div>
      
      <div class="col-md-6">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required />
      </div>

      <div class="col-md-6">
        <label for="senha" class="form-label">Senha:</label>
        <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required />
      </div>

      <div class="col-md-6">
        <label for="telefone" class="form-label">Telefone:</label>
        <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(xx) xxxxx-xxxx" required />
      </div>

      <div class="col-md-6"><label for="data de nascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="data_nasc" name="data_nasc" required></div>
    
      <div class="col-8">
        <label class="form-label" for="cep">Cep:</label>
        <input name="cep" type="text" id="cep" class="form-control" required/>

        <label class="form-label">Rua:</label>
        <input name="rua" type="text" id="rua" class="form-control"/>
      
        <label class="form-label">Bairro:</label>
        <input name="bairro" type="text" id="bairro" class="form-control" />

        <label class="form-label">Cidade:</label>
        <input name="cidade" type="text" id="cidade" class="form-control" />

        <label class="form-label">Estado:</label>
        <input name="uf" type="text" id="uf" class="form-control" />
      </div>
    
      <!-- CPF atualizado -->
      <div class="col-md-6">
        <label for="cpf" class="form-label">CPF:</label>
        <input class="form-control" type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" required>
      </div>
    
      <div class="col-md-6">
        <label for="sexo" class="form-label">Gênero:</label>
        <select class="form-select" id="sexo" name="sexo" required>
          <option value="">Selecione</option>
          <option value="M">Masculino</option>
          <option value="F">Feminino</option>
          <option value="Outro">Outro</option>
        </select>
      </div>
    
      <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary px-5">Cadastrar</button>
      </div>
    
      <div class="col-12 text-center">
        <p class="mt-3">Já tem uma conta? <a href="./login.php">Faça login</a></p>
      </div>
    </form>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  
  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="/FisioVida/js/main.js"></script>

</body>

<!-- Modal de erro -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">Erro no Cadastro</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <?php
        if (!empty($_SESSION['error_msg'])) {
            echo htmlspecialchars($_SESSION['error_msg']);
            unset($_SESSION['error_msg']);
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('errorModal');
    if (modalEl && modalEl.querySelector('.modal-body').textContent.trim() !== '') {
        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
});
</script>


</html>
