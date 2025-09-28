<?php

// Verifica se o método da requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Inclui o arquivo de conexão com o banco de dados
    include "banco.php";

    // --- Recebe os dados da Horta ---
    $nome_horta = $_POST['nome_horta'];
    $cnpj_horta = $_POST['cnpj_horta'];
    $descricao_horta = $_POST['descricao_horta'];

    // --- Recebe os dados do Endereço ---
    $rua = $_POST['rua_endereco'];
    $bairro = $_POST['bairro_endereco'];
    $cep = $_POST['cep_endereco'];
    $cidade = $_POST['cidade_endereco'];
    $estado = $_POST['estado_endereco'];
    $pais = $_POST['pais_endereco'];

    // Valida se todos os campos obrigatórios foram preenchidos
    if (empty($nome_horta) || empty($cnpj_horta) || empty($rua) || empty($bairro) || empty($cep) || empty($cidade) || empty($estado) || empty($pais)) {
        echo "Erro: Todos os campos são obrigatórios!";
        exit;
    }

    try {
        // Inicia uma transação: ou tudo funciona, ou nada é salvo.
        $conn->beginTransaction();

        // 1. Insere o endereço na tabela 'endereco_hortas'
        $sql_endereco = "INSERT INTO endereco_hortas (nm_rua, nr_cep, nm_bairro, nm_estado, nm_cidade, nm_pais) 
                         VALUES (:rua, :cep, :bairro, :estado, :cidade, :pais)";
        $stmt_endereco = $conn->prepare($sql_endereco);
        $stmt_endereco->bindValue(':rua', $rua);
        $stmt_endereco->bindValue(':cep', $cep);
        $stmt_endereco->bindValue(':bairro', $bairro);
        $stmt_endereco->bindValue(':estado', $estado);
        $stmt_endereco->bindValue(':cidade', $cidade);
        $stmt_endereco->bindValue(':pais', $pais);
        $stmt_endereco->execute();

        // Pega o ID do endereço que acabamos de criar
        $id_endereco_inserido = $conn->lastInsertId();

        // 2. Insere a horta na tabela 'hortas', usando o ID do endereço
        $sql_horta = "INSERT INTO hortas (endereco_hortas_id_endereco_hortas, nr_cnpj, nome, descricao, receitas_geradas) 
                      VALUES (:id_endereco, :cnpj, :nome, :descricao, 0)";
        $stmt_horta = $conn->prepare($sql_horta);
        $stmt_horta->bindValue(':id_endereco', $id_endereco_inserido);
        $stmt_horta->bindValue(':cnpj', $cnpj_horta);
        $stmt_horta->bindValue(':nome', $nome_horta);
        $stmt_horta->bindValue(':descricao', $descricao_horta);
        $stmt_horta->execute();

        // Se tudo deu certo até aqui, confirma as alterações no banco
        $conn->commit();

        echo "Horta cadastrada com sucesso!";

    } catch (PDOException $e) {
        // Se qualquer um dos comandos falhar, desfaz todas as alterações
        $conn->rollBack();
        echo "Erro ao cadastrar a horta: " . $e->getMessage();
    }

} else {
    // Se o acesso ao script não for via POST, redireciona ou exibe erro
    echo "Acesso inválido.";
}
?>
