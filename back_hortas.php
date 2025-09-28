<?php

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    include "banco.php";

    $nome = $_POST['nome_produtor'];
    $telefone = $_POST['telefone_produtor'];
    $email = $_POST['email_produtor'];
    $senha = $_POST['senha_produtor'];    

    if(!empty($nome) && !empty($telefone) && !empty($email) && !empty($senha)) {
        try{
            $sql = "INSERT INTO produtor (nome_log, telefone_log, email_log, hash_senha) VALUES (:nome, :telefone, :email, :senha)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':hash', $senha); 
        
            if($stmt->execute()){
                $mensagem_sucesso = "Produtor cadastrado com sucesso!";
                echo $mensagem_sucesso;
                $mostrar_formulario = false;
            } else {
                $mensagem_erro = "Erro ao cadastrar o produtor.";
                echo $mensagem_erro;
            } 
        } catch (PDOException $e) {
            $mensagem_erro = "Erro no banco de dados: " . $e->getMessage();
            echo $mensagem_erro;
    }
}
}

?>