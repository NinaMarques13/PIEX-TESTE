<?php

// Verifica se o método da requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Inclui o arquivo de conexão com o banco de dados
    include "banco.php";

    // 1. Recebe todos os dados do formulário
    $nome = $_POST['nome_produtor'];
    $cpf = $_POST['cpf_produtor'];
    $telefone = $_POST['telefone_produtor'];
    $email = $_POST['email_produtor'];
    $senha = $_POST['senha_produtor'];
    $id_horta = $_POST['id_horta'];

    // 2. Valida se todos os campos foram preenchidos
    if (!empty($nome) && !empty($cpf) && !empty($telefone) && !empty($email) && !empty($senha) && !empty($id_horta)) {
        
        try {
            // 3. (SEGURANÇA) Cria um hash seguro da senha
            // Nunca salve senhas como texto puro no banco de dados!
            $hash_senha = password_hash($senha, PASSWORD_DEFAULT);

            // 4. Prepara o comando SQL para inserir os dados nas colunas corretas
            $sql = "INSERT INTO produtor (nome_log, nr_cpf, telefone_log, email_log, hash_senha, hortas_id_hortas) 
                    VALUES (:nome, :cpf, :telefone, :email, :senha, :id_horta)";

            $stmt = $conn->prepare($sql);

            // 5. Associa os valores recebidos aos parâmetros do comando SQL
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':cpf', $cpf);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $hash_senha); // Salva a senha criptografada
            $stmt->bindValue(':id_horta', $id_horta);

            // 6. Executa o comando
            if ($stmt->execute()) {
                // Se a execução for bem-sucedida, exibe mensagem de sucesso
                echo "Produtor cadastrado com sucesso!";
            } else {
                // Se houver um erro na execução, exibe mensagem de erro
                echo "Erro ao cadastrar o produtor.";
            }

        } catch (PDOException $e) {
            // Se houver um erro de conexão ou no comando SQL, captura a exceção
            // Em um ambiente de produção, é melhor registrar o erro em um log do que exibi-lo na tela
            echo "Erro no banco de dados: " . $e->getMessage();
        }

    } else {
        // Se algum campo estiver vazio, exibe uma mensagem
        echo "Erro: Todos os campos são obrigatórios!";
    }
} else {
    // Se o acesso ao script não for via POST, redireciona ou exibe erro
    echo "Acesso inválido.";
}

?>