<?php
// Retorna as palavras-chave de uma pergunta.
// Recebe: id_pergunta
// Retorna um array (vazio se o id for inválido ou a pergunta não tiver palavras-chave).

function mostrar_palavras($conexao, $id_pergunta)
{
    // Validação do parâmetro
    $id_pergunta = filter_var($id_pergunta, FILTER_VALIDATE_INT);
    if ($id_pergunta === false || $id_pergunta <= 0) {
        return array();
    }

    $stmt = mysqli_prepare($conexao, "SELECT palavras_chave FROM pergunta WHERE id_pergunta = ?");
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    mysqli_stmt_bind_param($stmt, "i", $id_pergunta);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $linha = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    if (!$linha || $linha["palavras_chave"] === null || trim($linha["palavras_chave"]) === "") {
        return array();
    }

    // Separa por vírgulas e limpa os espaços de cada palavra
    $palavras = array();
    foreach (explode(",", $linha["palavras_chave"]) as $palavra) {
        $palavra = trim($palavra);
        if ($palavra !== "") {
            $palavras[] = $palavra;
        }
    }

    return $palavras;
}
