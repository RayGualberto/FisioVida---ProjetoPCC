<?php
require_once '../php/db.php'; // conexão $pdo

$errors = [];
$nome = $email = $cpf = $telefone = $cep = $sexo = $data_nasc = '';
$registro_crefito = $especialidade = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome             = trim($_POST['first_name'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $cpf              = trim($_POST['cpf'] ?? '');
    $telefone         = trim($_POST['telefone'] ?? '');
    $cep              = trim($_POST['cep'] ?? '');
    $sexo             = $_POST['sexo'] ?? '';
    $data_nasc        = $_POST['data_nasc'] ?? '';
    $registro_crefito = trim($_POST['registro_crefito'] ?? '');
    $especialidade    = trim($_POST['especialidade'] ?? '');

    // Validações básicas
    if ($nome === '') $errors[] = 'Nome é obrigatório.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
    if (strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
    if ($cpf === '') $errors[] = 'CPF é obrigatório.';
    if ($telefone === '') $errors[] = 'Telefone é obrigatório.';
    if ($cep === '') $errors[] = 'CEP é obrigatório.';
    if (!in_array($sexo, ['M', 'F', 'Outro'], true)) $errors[] = 'Sexo inválido.';
    if ($data_nasc === '') $errors[] = 'Data de nascimento é obrigatória.';
    if ($registro_crefito === '') $errors[] = 'Registro CREFITO é obrigatório.';
    if ($especialidade === '') $errors[] = 'Especialidade é obrigatória.';

    if (!$errors) {
        try {
            $pdo->beginTransaction();

            // Inserção na tabela USUARIO com tipo_usuario fixo 'fisioterapeuta'
            $stmt = $pdo->prepare('
                INSERT INTO usuario (nome, email, senha, cpf, telefone, cep, sexo, data_nasc, tipo_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([
                $nome,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                $cpf,
                $telefone,
                $cep,
                $sexo,
                $data_nasc,
                'fisioterapeuta'
            ]);

            // Inserção na tabela FISIOTERAPEUTA
            $stmt2 = $pdo->prepare('
                INSERT INTO fisioterapeuta (nome, telefone, endereco, cpf, registro_crefito, especialidade)
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            $stmt2->execute([
                $nome,
                $telefone,
                $cep,           // usamos CEP como endereço
                $cpf,
                $registro_crefito,
                $especialidade
            ]);

            $pdo->commit();

            header('Location: usuarios.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() === '23000') {
                $errors[] = 'Já existe um usuário com este e-mail ou CPF.';
            } else {
                $errors[] = 'Erro ao salvar: ' . $e->getMessage();
            }
        }
    }
}

include __DIR__ . '/partials/header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<style>
    .form-label {
        color: #004b87;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #a9d3ff;
        transition: 0.3s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6ddccf;
        box-shadow: 0 0 5px #6ddccf;
    }

    .card {
        background: #ffffffdd;
        border: none;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .btn-primary {
        background: #0078ff;
        border: none;
    }

    .btn-primary:hover {
        background: #0056b3;
    }
    .btn-outline-primary {
    border-radius: 12px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #6ddccf;
    border-color: #6ddccf;
    color: #fff;
    transform: translateY(-2px);
}
</style>

<script>
// Máscaras
$(document).ready(function(){
    $('#cpf').mask('000.000.000-00');
    $('#telefone').mask('(00) 00000-0000');
    $('#cep').mask('00000-000');
});

 <!-- Validar CPF -->
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
        url: "../admin/validar_cpf.php",
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

<div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="h4 mb-0">Novo Fisioterapeuta</h2>
    <a class="btn btn-outline-primary btn-sm" href="usuarios.php">Voltar</a>
</div>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" class="card shadow-sm p-4" style="border-radius: 15px;">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nome</label>
            <input type="text" name="first_name" class="form-control form-control-lg" value="<?= htmlspecialchars($nome) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">E-mail</label>
            <input type="email" name="email" class="form-control form-control-lg" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">CPF</label>
            <input type="text" name="cpf" id="cpf" class="form-control form-control-lg" value="<?= htmlspecialchars($cpf) ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Telefone</label>
            <input type="text" name="telefone" id="telefone" class="form-control form-control-lg" value="<?= htmlspecialchars($telefone) ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">CEP / Endereço</label>
            <input type="text" name="cep" id="cep" class="form-control form-control-lg" value="<?= htmlspecialchars($cep) ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Sexo</label>
            <select name="sexo" class="form-select form-select-lg" required>
                <option value="" disabled <?= $sexo === '' ? 'selected' : '' ?>>Selecione...</option>
                <option value="M" <?= $sexo === 'M' ? 'selected' : '' ?>>Masculino</option>
                <option value="F" <?= $sexo === 'F' ? 'selected' : '' ?>>Feminino</option>
                <option value="Outro" <?= $sexo === 'Outro' ? 'selected' : '' ?>>Outro</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Data de Nascimento</label>
            <input type="date" name="data_nasc" class="form-control form-control-lg" value="<?= htmlspecialchars($data_nasc) ?>" required>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Senha</label>
            <input type="password" name="password" class="form-control form-control-lg" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Registro CREFITO</label>
            <input type="text" name="registro_crefito" class="form-control form-control-lg" value="<?= htmlspecialchars($registro_crefito) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Especialidade</label>
            <input type="text" name="especialidade" class="form-control form-control-lg" value="<?= htmlspecialchars($especialidade) ?>" required>
        </div>
    </div>

    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary btn-lg px-4">Adicionar Fisioterapeuta</button>
    </div>
</form>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
