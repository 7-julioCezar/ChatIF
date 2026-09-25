<?php
// Lista todas as perguntas, da mais recente para a mais antiga.
// Retorna um array de perguntas.

function listar_perguntas($conexao)
{
    $sql = "SELECT p.id_pergunta, p.pergunta, p.resposta, p.palavras_chave,
                   p.data_criacao, c.nome AS categoria, u.nome AS autor
            FROM pergunta p
            LEFT JOIN categoria c ON c.id_categoria = p.id_categoria
            LEFT JOIN usuario u ON u.id_usuario = p.id_usuario
            ORDER BY p.data_criacao DESC";

    $resultado = mysqli_query($conexao, $sql);

    if (!$resultado) {
        die("Erro ao listar perguntas: " . mysqli_error($conexao));
    }

    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
