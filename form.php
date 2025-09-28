<!-- Formulário Simples só pra povoar banco -->
<html>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Formulario 1</title>
    </head>
    <body>
        <form action="back_hortas.php" method="POST">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome_produtor" placeholder="Digite o seu nome:">
                <br>
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone_produtor" placeholder="Digite o seu telefone:">
                <br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email_produtor" placeholder="Digite o seu email:">
                <br>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha_produtor" placeholder="Digite o sua senha:">
                <br>
                <input type="submit" value="Gravar" id="gravar">
            </div>
        </form>
    </body>
    </html>
</html>