<?php 
include 'db.php'; 

// 2. Recebe os dados do formulário
$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$endereco = $_POST['endereco'];
  


// 3. (opcional mas recomendado) Criptografa a senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// 4. Comando SQL para inserir os dados
$sql = "INSERT INTO usuario (nome, email, senha, endereco) VALUES ('$nome','$email', '$senha_hash', '$endereco')";

// 5. Executa o comando
if ($conn->query($sql) === TRUE) {
    echo "Usuário cadastrado com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, endereco) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $email, $senha_hash, $endereco);
$stmt->execute();

// 6. Fecha a conexão
$conn->close();
?>