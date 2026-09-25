<?php
require_once "Banco/conexao.php";
require_once "Banco/main.php";
require_once "acoes/listar_perguntas.php";
require_once "acoes/filtrar_categoria.php";
require_once "acoes/buscar_palavra.php";
require_once "acoes/identificar_palavras.php";
require_once "acoes/mostrar_palavras.php";

// Função para escapar textos na tela
function h($texto)
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, "UTF-8");
}

// Exibe uma lista de perguntas na tela
function exibir_perguntas($lista, $id_mostrar, $palavras_pergunta)
{
    if (count($lista) == 0) {
        echo "<p>Nenhuma pergunta encontrada.</p>";
        return;
    }

    foreach ($lista as $p) {
        echo "<div class='pergunta'>";
        echo "<p><strong>Pergunta:</strong> " . h($p["pergunta"]) . "</p>";
        echo "<p><strong>Resposta:</strong> " . h($p["resposta"]) . "</p>";
        echo "<p><strong>Categoria:</strong> " . h($p["categoria"]) . "</p>";
        echo "<p><strong>Autor:</strong> " . h($p["autor"]) . "</p>";
        echo "<p><strong>Data de criação:</strong> " . h(date("d/m/Y H:i", strtotime($p["data_criacao"]))) . "</p>";
        echo "<p><strong>Palavras-chave:</strong> " . h($p["palavras_chave"]) . "</p>";

        echo "<form method='post' action='index.php'>";
        echo "<input type='hidden' name='acao' value='mostrar_palavras'>";
        echo "<input type='hidden' name='id_pergunta' value='" . (int) $p["id_pergunta"] . "'>";
        echo "<button type='submit'>Mostrar palavras-chave</button>";
        echo "</form>";

        // Se este é o botão que foi clicado, mostra as palavras-chave em lista
        if ($id_mostrar == $p["id_pergunta"]) {
            echo "<div class='resultado'>";
            if (count($palavras_pergunta) == 0) {
                echo "<p>Esta pergunta não possui palavras-chave.</p>";
            } else {
                echo "<ul>";
                foreach ($palavras_pergunta as $palavra) {
                    echo "<li>" . h($palavra) . "</li>";
                }
                echo "</ul>";
            }
            echo "</div>";
        }

        echo "</div>";
    }
}

// ----- Variáveis da tela -----
$acao = isset($_POST["acao"]) ? $_POST["acao"] : "";
$titulo = "Todas as perguntas";
$mensagem = "";
$busca = "";
$id_categoria = 0;
$texto = "";
$palavras_texto = null;   // null = ainda não usado
$id_mostrar = 0;
$palavras_pergunta = array();
$perguntas = array();

// ----- Processa a ação escolhida -----
if ($acao == "pesquisar") {
    $busca = isset($_POST["busca"]) ? trim($_POST["busca"]) : "";
    if ($busca == "") {
        $mensagem = "Digite uma palavra ou frase para pesquisar.";
        $perguntas = listar_perguntas($conexao);
    } else {
        $titulo = "Resultado da pesquisa por: " . $busca;
        $perguntas = buscar_palavra($conexao, $busca);
    }

} elseif ($acao == "filtrar") {
    $id_categoria = filter_var(isset($_POST["id_categoria"]) ? $_POST["id_categoria"] : "", FILTER_VALIDATE_INT);
    if ($id_categoria === false || $id_categoria <= 0) {
        $id_categoria = 0;
        $mensagem = "Selecione uma categoria para filtrar.";
        $perguntas = listar_perguntas($conexao);
    } else {
        $titulo = "Perguntas da categoria selecionada";
        $perguntas = filtrar_categoria($conexao, $id_categoria);
    }

} elseif ($acao == "identificar") {
    $texto = isset($_POST["texto"]) ? trim($_POST["texto"]) : "";
    if ($texto == "") {
        $mensagem = "Digite uma frase para identificar as palavras.";
    } else {
        $palavras_texto = identificar_palavras($texto);
    }
    $perguntas = listar_perguntas($conexao);

} elseif ($acao == "mostrar_palavras") {
    $id_mostrar = filter_var(isset($_POST["id_pergunta"]) ? $_POST["id_pergunta"] : "", FILTER_VALIDATE_INT);
    if ($id_mostrar === false || $id_mostrar <= 0) {
        $id_mostrar = 0;
        $mensagem = "Pergunta inválida.";
    } else {
        $palavras_pergunta = mostrar_palavras($conexao, $id_mostrar);
    }
    $perguntas = listar_perguntas($conexao);

} else {
    $perguntas = listar_perguntas($conexao);
}

$categorias = listar_categorias($conexao);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ChatIF - Tela de testes</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<h1>ChatIF - Tela de testes</h1>

<?php if ($mensagem != "") { ?>
    <p class="mensagem"><?php echo h($mensagem); ?></p>
<?php } ?>

<!-- Campo de pesquisa -->
<div class="secao">
    <h2>Pesquisar pergunta</h2>
    <form method="post" action="index.php">
        <input type="hidden" name="acao" value="pesquisar">
        <input type="text" name="busca" size="50" maxlength="200" value="<?php echo h($busca); ?>" placeholder="Digite uma pergunta, palavra ou frase">
        <button type="submit">Pesquisar</button>
    </form>
</div>

<!-- Filtro por categoria -->
<div class="secao">
    <h2>Filtrar por categoria</h2>
    <form method="post" action="index.php">
        <input type="hidden" name="acao" value="filtrar">
        <select name="id_categoria">
            <option value="">-- Selecione --</option>
            <?php foreach ($categorias as $c) { ?>
                <option value="<?php echo (int) $c["id_categoria"]; ?>"
                    <?php if ($id_categoria == $c["id_categoria"]) echo "selected"; ?>>
                    <?php echo h($c["nome"]); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Filtrar</button>
    </form>
</div>

<!-- Identificação de palavras -->
<div class="secao">
    <h2>Identificar palavras</h2>
    <form method="post" action="index.php">
        <input type="hidden" name="acao" value="identificar">
        <textarea name="texto" rows="4" cols="60" placeholder="Digite qualquer frase"><?php echo h($texto); ?></textarea>
        <br>
        <button type="submit">Identificar palavras</button>
    </form>

    <?php if ($palavras_texto !== null) { ?>
        <div class="resultado">
            <?php if (count($palavras_texto) == 0) { ?>
                <p>Nenhuma palavra identificada.</p>
            <?php } else { ?>
                <p>Palavras identificadas (<?php echo count($palavras_texto); ?>):</p>
                <ul>
                    <?php foreach ($palavras_texto as $palavra) { ?>
                        <li><?php echo h($palavra); ?></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<!-- Lista de perguntas -->
<div class="secao">
    <h2><?php echo h($titulo); ?></h2>
    <p>Total: <?php echo count($perguntas); ?></p>
    <?php exibir_perguntas($perguntas, $id_mostrar, $palavras_pergunta); ?>
</div>

</body>
</html>
