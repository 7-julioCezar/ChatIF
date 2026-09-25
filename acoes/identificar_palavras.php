<?php
// Identifica as palavras de um texto.
// Recebe: texto
// Retorna um array com as palavras (minúsculas, sem repetição e com 3+ caracteres).

function identificar_palavras($texto)
{
    $texto = trim($texto);
    if ($texto === "") {
        return array();
    }

    // 1. Converte para minúsculas (mantém acentos)
    $texto = mb_strtolower($texto, "UTF-8");

    // 2. Remove caracteres especiais (mantém letras, números e espaços)
    $texto = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $texto);

    // 3. Separa as palavras
    $palavras = preg_split('/\s+/u', $texto, -1, PREG_SPLIT_NO_EMPTY);

    // 4. Ignora palavras menores que 3 caracteres
    $resultado = array();
    foreach ($palavras as $palavra) {
        if (mb_strlen($palavra, "UTF-8") >= 3) {
            $resultado[] = $palavra;
        }
    }

    // 5. Elimina palavras repetidas
    return array_values(array_unique($resultado));
}
