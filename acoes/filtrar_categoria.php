<?php
// Filtra as perguntas de uma categoria.
// Recebe: id_categoria
// Retorna um array de perguntas (vazio se o id for inválido ou não houver resultados).

function filtrar_categoria($conexao, $id_categoria)
{
    // Validação do parâmetro
    $id_categoria = filter_var($id_categoria, FILTER_VALIDATE_INT);
    if ($id_categoria === false || $id_categoria <= 0) {
        return array();
    }

    $sql = "SELECT p.id_pergunta, p.pergunta, p.resposta, p.palavras_chave,
                   p.data_criacao, c.nome AS categoria, u.nome AS autor
            FROM pergunta p
            LEFT JOIN categoria c ON c.id_categoria = p.id_categoria
            LEFT JOIN usuario u ON u.id_usuario = p.id_usuario
            WHERE p.id_categoria = ?
            ORDER BY p.data_criacao DESC";

    $stmt = mysqli_prepare($conexao, $sql);
    if (!$stmt) {
        die("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }

    mysqli_stmt_bind_param($stmt, "i", $id_categoria);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $lista = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $lista;
}

// Carrega todas as categorias (usada para preencher o SELECT da tela de testes).
function listar_categorias($conexao)
{
    $resultado = mysqli_query($conexao, "SELECT id_categoria, nome FROM categoria ORDER BY nome");

    if (!$resultado) {
        die("Erro ao listar categorias: " . mysqli_error($conexao));
    }

    return mysqli_fetch_all($resultado, MYSQLI_ASSOC);
}
