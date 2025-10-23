<?php
header('Content-Type: application/json; charset=utf-8');

// Função de validação de CPF
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica tamanho
    if (strlen($cpf) != 11) return false;

    // Rejeita CPFs inválidos conhecidos (números repetidos)
    if (preg_match('/^(\d)\1{10}$/', $cpf)) return false;

    // Valida os dígitos verificadores
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }

    return true;
}

// Pega o CPF da requisição (POST ou GET)
$cpf = $_POST['cpf'] ?? $_GET['cpf'] ?? null;

if (!$cpf) {
    echo json_encode(['erro' => true, 'mensagem' => 'CPF não informado']);
    exit;
}

// Valida e retorna o resultado
if (validarCPF($cpf)) {
    echo json_encode(['valido' => true, 'mensagem' => 'CPF válido']);
} else {
    echo json_encode(['valido' => false, 'mensagem' => 'CPF inválido']);
}
