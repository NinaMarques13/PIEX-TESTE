<?php

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtor</title>
</head>
<body>

    <!-- Formulário Simples só pra povoar banco -->
    <form action="back_produtor.php" method="POST">
        <div>
            <legend>Cadastre o Produtor:</legend>
            
            <label for="nome_produtor">Nome:</label>
            <input type="text" id="nome_produtor" name="nome_produtor" placeholder="Digite o seu nome" required>
            <br>

            <label for="cpf_produtor">CPF:</label>
            <input type="text" id="cpf_produtor" name="cpf_produtor" placeholder="Digite o seu CPF" required>
            <br>

            <label for="telefone_produtor">Telefone:</label>
            <input type="tel" id="telefone_produtor" name="telefone_produtor" placeholder="Digite o seu telefone" required>
            <br>

            <label for="email_produtor">Email:</label>
            <input type="email" id="email_produtor" name="email_produtor" placeholder="Digite o seu email" required>
            <br>

            <label for="senha_produtor">Senha:</label>
            <input type="password" id="senha_produtor" name="senha_produtor" placeholder="Digite a sua senha" required>
            <br>
            
            <label for="id_horta">Horta:</label>
            <select id="id_horta" name="id_horta" required>
                <option value="" disabled selected>-- Escolha uma horta --</option>
                <?php if (!empty($lista_de_hortas)): ?>
                    <?php foreach ($lista_de_hortas as $horta): ?>
                        <option value="<?php echo htmlspecialchars($horta['id_hortas']); ?>">
                            <?php echo htmlspecialchars($horta['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>Nenhuma horta encontrada</option>
                <?php endif; ?>
            </select>
            <br>
            <br>

            <input type="submit" value="Cadastrar" id="cadastrar">
        </div>
    </form>

</body>
</html>

