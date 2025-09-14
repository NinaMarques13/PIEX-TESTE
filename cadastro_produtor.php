<?php
if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    include "banco.php";
    
    $nome=$_POST['nome'] ?? '';
    $telefone=$_POST['telefone'] ?? '';
    $email=$_POST['email'] ?? '';

    if (!empty($nome) && !empty($email) && !empty($telefone)) {
        try{
            $sql = "INSERT INTO produtor (nome, email, telefone) VALUES (:nome, :email, :telefone)";
            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':telefone', $telefone);

            if ($stmt->execute()){
                $mensagem_sucesso = "Produtor cadastrado com sucesso!";
            }else {
                $mensagem_erro = "Erro ao cadastrar o produtor.";
            }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro no banco de dados: " . $e->getMessage();
    } 
    } else {
        $mensagem_erro = "Por favor, preencha todos o campos.";
    }
}  
?>
<html>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Interação com o usuário</title>
    </head>
    <body>
        <h1>Olá Seja bem vindo</h1>

    <form action="cadastro_produtor.php" method="post">
        <fieldset>
            <legend>Cadastrar novo produtor</legend>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="mensagem sucesso"><?php echo $mensagem_sucesso; ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="mensagem erro"><?php echo $mensagem_erro; ?></div>
            <?php endif; ?>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required/>
            <br/>
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" placeholder="Digite seu email" required/>
            <br/>
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" placeholder="Digite seu telfone" required/>
            <br/>
            <input type="submit" name="upload" value="Salvar">
        </fieldset>
    </form>
    </body>
    </html>
</html>
