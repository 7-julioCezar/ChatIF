
<?php
require 'conexao.php'; 

echo "<h1>Integração PHP + MariaDB</h1>";

// ========== 1. INSERIR DADOS ==========
try {
    $sql = "INSERT INTO usuario (id,nome, email, idade) VALUES (?,?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([1,'João Pedro', 'joao@email.com', 23]);
    
    echo "<p style='color:green'>✅ usuario inserido com sucesso! ID: " . $pdo->lastInsertId() . "</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>Erro ao inserir: " . $e->getMessage() . "</p>";
}

// ========== 2. CONSULTAR DADOS ==========
echo "<h2>Lista de Usuario</h2>";

$stmt = $pdo->query("SELECT * FROM usuario ORDER BY id_usuario");
$usuarios = $stmt->fetchAll();

if (count($usuarios) > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr>
            <th>id<th>
            <th>Nome</th>
            <th>Email</th>
            <th>Idade</th>
          </tr>";


          $stmt = $pdo->query("SELECT * FROM pergunta");
          $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($usuarios as $usuario) {
        echo "<tr>";
        echo "<td>{$usuario['nome']}</td>";
        echo "<td>{$usuario['email']}</td>";
        echo "<td>{$usuario['idade']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Nenhum usuario cadastrado.</p>";
}
?>
