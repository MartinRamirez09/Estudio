<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'ecomerce';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Leer datos del POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Insertar el pedido
    $stmt = $pdo->prepare("INSERT INTO pedido (usuario_id, fecha_pedido, estado, total) VALUES (:usuario_id, :fecha_pedido, :estado, :total)");
    $stmt->execute([
        ':usuario_id' => $data['usuario_id'],
        ':fecha_pedido' => $data['fecha_pedido'],
        ':estado' => $data['estado'],
        ':total' => $data['total']
    ]);

    $pedidoId = $pdo->lastInsertId();

    // Opcional: Insertar los detalles del pedido (productos)
    $stmtDetalle = $pdo->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio) VALUES (:pedido_id, :producto_id, :cantidad, :precio)");
    foreach ($data['articulos'] as $producto) {
        $stmtDetalle->execute([
            ':pedido_id' => $pedidoId,
            ':producto_id' => $producto['id'],
            ':cantidad' => $producto['cantidad'],
            ':precio' => $producto['precio']
        ]);
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>