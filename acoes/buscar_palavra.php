<?php
// Busca perguntas por palavra ou frase (pergunta, resposta e palavras_chave).
// Recebe: texto da busca
// Retorna um array de perguntas (vazio se a busca for inválida ou não houver resultados).

function buscar_palavra($conexao, $busca)
{
    // Validação do parâmetro
    $busca = trim($busca);
    if ($busca === "" || mb_strlen($busca) > 200) {
        return array();
    }

    // Escapa os curingas do LIKE para que sejam tratados como texto comum
    $busca = addcslashes($busca, "%_\\");
    $termo = "%" . $busca . "%";

    $sql = "SELECT p.id_pergunta, p.pergunta, p.resposta, p.palavras_chave,
                   p.data_criacao, c.nome AS categoria, u.nome AS autor
            FROM pergunta p
            LEFT JOIN categoria c ON c.id_categoria = p.id_categoria
            LEFT JOIN usuario u ON u.id_usuario = p.id_usuario
            WHERE p.pergunta LIKE ?
               OR p.resposta LIKE ?
               OR p.palavras_chave LIKE ?
            ORDER BY p.data_criacao DESC";

    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    mysqli_stmt_bind_param($stmt, "sss", $termo, $termo, $termo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $lista = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $lista;
}
