<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "banco.php";

$dados = json_decode(file_get_contents("php://input"));
$resposta = array();

if (!empty($dados->email) && !empty($dados->senha)) {
    try {
        $sql = "SELECT id_produtor, nome_log, hash_senha FROM produtor WHERE email_log = :email LIMIT 1";
        $stmt = $conn->prepare($sql);

        $email = htmlspecialchars(strip_tags($dados->email));
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $linha = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_produtor = $linha['id_produtor'];
            $nome_produtor = $linha['nome_log'];
            $hash_senha_banco = $linha['hash_senha'];

            // Verifica se a senha enviada corresponde ao hash salvo no banco
            if (password_verify($dados->senha, $hash_senha_banco)) {
                http_response_code(200); // OK
                $resposta = array(
                    "status" => "sucesso",
                    "mensagem" => "Login bem-sucedido.",
                    "dados_usuario" => array(
                        "id" => $id_produtor,
                        "nome" => $nome_produtor
                    )
                    // Aqui você poderia gerar e retornar um token JWT para sessões seguras
                );
            } else {
                http_response_code(401); // Unauthorized
                $resposta = array("status" => "erro", "mensagem" => "Senha incorreta.");
            }
        } else {
            http_response_code(404); // Not Found
            $resposta = array("status" => "erro", "mensagem" => "Usuário não encontrado.");
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $resposta = array("status" => "erro", "mensagem" => "Erro no banco de dados.");
    }
} else {
    http_response_code(400); // Bad Request
    $resposta = array("status" => "erro", "mensagem" => "Email e senha são obrigatórios.");
}

echo json_encode($resposta);
?>