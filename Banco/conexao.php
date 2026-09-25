<?php

$host = 'localhost';
$port = 3307;          
$user = 'root';
$password = '';        
$database = 'ifc_assist';

try{
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
        $user,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT 1 AS status");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($resultado['status'] == 1) {
        echo "O Banco de dados está respondendo corretamente!<br>";
        echo "Versão do servidor: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    }
    
} catch (PDOException $e) {
    echo "Erro ao conectar no banco:<br>";
    echo $e->getMessage();
}
?>