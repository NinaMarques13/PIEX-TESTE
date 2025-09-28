<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Horta</title>
</head>
<body>

    <!-- Formulário Simples para cadastrar uma nova horta -->
    <form action="back_horta.php" method="POST">
        <div>
            <fieldset>
                <legend><strong>Dados da Horta</strong></legend>
                
                <label for="nome_horta">Nome da Horta:</label>
                <input type="text" id="nome_horta" name="nome_horta" placeholder="Ex: Horta Comunitária Central" required>
                <br>

                <label for="cnpj_horta">CNPJ:</label>
                <input type="text" id="cnpj_horta" name="cnpj_horta" placeholder="Digite o CNPJ" required>
                <br>

                <label for="descricao_horta">Descrição:</label>
                <br>
                <textarea id="descricao_horta" name="descricao_horta" rows="4" cols="50" placeholder="Fale um pouco sobre a horta"></textarea>
                <br>
            </fieldset>
            
            <br>

            <fieldset>
                <legend><strong>Endereço da Horta</strong></legend>
                
                <label for="rua_endereco">Rua:</label>
                <input type="text" id="rua_endereco" name="rua_endereco" placeholder="Nome da rua e número" required>
                <br>
                
                <label for="bairro_endereco">Bairro:</label>
                <input type="text" id="bairro_endereco" name="bairro_endereco" placeholder="Nome do bairro" required>
                <br>

                <label for="cep_endereco">CEP:</label>
                <input type="text" id="cep_endereco" name="cep_endereco" placeholder="Apenas números" required>
                <br>

                <label for="cidade_endereco">Cidade:</label>
                <input type="text" id="cidade_endereco" name="cidade_endereco" placeholder="Nome da cidade" required>
                <br>

                <label for="estado_endereco">Estado (UF):</label>
                <input type="text" id="estado_endereco" name="estado_endereco" maxlength="2" placeholder="Ex: SP" required>
                <br>

                <label for="pais_endereco">País:</label>
                <input type="text" id="pais_endereco" name="pais_endereco" value="Brasil" required>
                <br>

            </fieldset>
            
            <br>
            <input type="submit" value="Cadastrar Horta">
        </div>
    </form>

</body>
</html>
