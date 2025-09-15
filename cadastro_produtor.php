<?php
// --- LÓGICA DO BACKEND ---

// Inicia as variáveis como vazias.
$mensagem_sucesso = '';
$mensagem_erro = '';
$resposta_da_api = '';
$mostrar_formulario = true; // Nova variável para controlar a visibilidade do formulário

// 1. VERIFICA SE O FORMULÁRIO FOI ENVIADO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. INCLUI A CONEXÃO COM O BANCO
    include "banco.php"; 

    // 3. COLETA OS DADOS DO FORMULÁRIO
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';

    // 4. VALIDA OS CAMPOS
    if (!empty($nome) && !empty($email) && !empty($telefone)) {
        try {
            // 5. INSERE NO BANCO DE DADOS
            $sql = "INSERT INTO produtor (nome, email, telefone) VALUES (:nome, :email, :telefone)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':telefone', $telefone);

            // 6. EXECUTA A QUERY
            if ($stmt->execute()) {
                $mensagem_sucesso = "Produtor cadastrado com sucesso!";
                $mostrar_formulario = false; // Esconde o formulário após o sucesso

                // --- LÓGICA PARA CHAMAR A API GEMINI ---
                $prompt = "Você é um designer gráfico especializado em criar identidades visuais para pequenos negócios. Sua tarefa é gerar o layout de texto para um cartão de visita para um novo produtor da plataforma 'Horta Sustentável Connect'. O design deve ser limpo, moderno e profissional. Organize as informações de forma clara e atraente, como se estivesse a diagramar um cartão de visita real. Use os seguintes dados:\n\n"
                        . "- Nome do Produtor: " . $nome . "\n"
                        . "- Título: Produtor Local Sustentável\n"
                        . "- Email de Contato: " . $email . "\n"
                        . "- Telefone: " . $telefone . "\n\n"
                        . "Apresente o resultado final apenas com o texto formatado para o cartão, sem adicionar nenhuma outra explicação.";

                $dados_api = ['contents' => [['parts' => [['text' => $prompt]]]]];
                $json_para_api = json_encode($dados_api);

                $api_key = ''; // <<<---- CONTINUE USANDO A SUA CHAVE AQUI!

                $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $api_key;

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_para_api);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, 1);

                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    $resposta_da_api = 'Erro na chamada cURL: ' . curl_error($ch);
                } else {
                    $resultado = json_decode($response);
                    
                    if (isset($resultado->error)) {
                        $resposta_da_api = "Erro da API Gemini: " . $resultado->error->message;
                    } else {
                        // Extrai o texto final para o utilizador
                        $resposta_da_api = $resultado->candidates[0]->content->parts[0]->text ?? 'Não foi possível extrair a resposta da API.';
                    }
                }
                curl_close($ch);

            } else {
                $mensagem_erro = "Erro ao cadastrar o produtor.";
            }
        } catch (PDOException $e) {
             $mensagem_erro = "Erro no banco de dados: " . $e->getMessage();
        }
    } else {
        $mensagem_erro = "Por favor, preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtor</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #1c1e21; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; }
        .container { width: 100%; max-width: 500px; }
        form { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        fieldset { border: none; padding: 0; }
        legend { font-size: 1.5em; font-weight: 600; text-align: center; margin-bottom: 20px; color: #333; }
        label { display: block; margin-bottom: 5px; font-weight: 500; }
        input[type="text"], input[type="email"], input[type="tel"] { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        input[type="submit"] { width: 100%; background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 1em; font-weight: bold; }
        input[type="submit"]:hover { background-color: #218838; }
        .mensagem { padding: 15px; margin-bottom: 20px; border-radius: 6px; text-align: center; border: 1px solid transparent; }
        .sucesso { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .erro { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        pre { white-space: pre-wrap; word-wrap: break-word; font-family: "Courier New", Courier, monospace; font-size: 1em; }
        .api-response { background-color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; border-radius: 8px; text-align: center; }
        .api-response h3 { margin-top: 0; color: #333; }
        .api-response pre { background-color: #e9f5ff; border-left: 5px solid #007bff; padding: 20px; border-radius: 8px; text-align: left; }
        .btn-voltar { display: inline-block; background-color: #6c757d; color: white; padding: 12px 20px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .btn-voltar:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">

        <?php if ($mostrar_formulario): ?>
            <form action="cadastro_produtor.php" method="post">
                <fieldset>
                    <legend>Cadastrar Novo Produtor</legend>
                    <?php if (!empty($mensagem_erro)): ?>
                        <div class="mensagem erro"><?php echo htmlspecialchars($mensagem_erro); ?></div>
                    <?php endif; ?>
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required />
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="exemplo@email.com" required>
                    <label for="telefone">Telefone:</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(XX) XXXXX-XXXX" required>
                    <input type="submit" value="Salvar Produtor">
                </fieldset>
            </form>
        <?php else: ?>
            <div class="api-response">
                <h3><?php echo htmlspecialchars($mensagem_sucesso); ?></h3>
                <pre><?php echo htmlspecialchars($resposta_da_api); ?></pre>
                <a href="cadastro_produtor.php" class="btn-voltar">Cadastrar Outro?</a>
            </div>
        <?php endif; ?>
        
    </div>
</body>
</html>

