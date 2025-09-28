<?php
// Define que a resposta será no formato JSON
header("Content-Type: application/json; charset=UTF-8");
// Permite requisições de qualquer origem (em produção, restrinja ao seu domínio)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "banco.php";

// Pega o corpo da requisição (que deve ser um JSON)
$dados = json_decode(file_get_contents("php://input"));

// Array para a resposta
$resposta = array();

// Verifica se os dados necessários foram enviados
if (
    !empty($dados->nome) &&
    !empty($dados->cpf) &&
    !empty($dados->telefone) &&
    !empty($dados->email) &&
    !empty($dados->senha) &&
    !empty($dados->id_horta)
) {
    try {
        // (SEGURANÇA) Cria um hash seguro da senha
        $hash_senha = password_hash($dados->senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO produtor (nome_log, nr_cpf, telefone_log, email_log, hash_senha, hortas_id_hortas) 
                VALUES (:nome, :cpf, :telefone, :email, :senha, :id_horta)";

        $stmt = $conn->prepare($sql);

        // Limpa os dados para evitar injeção de XSS
        $nome = htmlspecialchars(strip_tags($dados->nome));
        $cpf = htmlspecialchars(strip_tags($dados->cpf));
        $telefone = htmlspecialchars(strip_tags($dados->telefone));
        $email = htmlspecialchars(strip_tags($dados->email));
        $id_horta = htmlspecialchars(strip_tags($dados->id_horta));

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $hash_senha);
        $stmt->bindValue(':id_horta', $id_horta);

        if ($stmt->execute()) {
            // Define o código de resposta HTTP - 201 Created
            http_response_code(201);
            $resposta = array("status" => "sucesso", "mensagem" => "Produtor cadastrado com sucesso.");
        } else {
            // 503 service unavailable
            http_response_code(503);
            $resposta = array("status" => "erro", "mensagem" => "Não foi possível cadastrar o produtor.");
        }
    } catch (PDOException $e) {
        http_response_code(500);
        // Em produção, não exponha a mensagem de erro detalhada
        $resposta = array("status" => "erro", "mensagem" => "Erro no banco de dados.");
        // error_log($e->getMessage()); // Log do erro real
    }
} else {
    // 400 bad request
    http_response_code(400);
    $resposta = array("status" => "erro", "mensagem" => "Dados incompletos.");
}

// Envia a resposta final em formato JSON
echo json_encode($resposta);
?>