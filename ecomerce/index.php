<?php
require_once 'conexiones/conexion.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Obtener productos
$sql = "SELECT * FROM productos WHERE stock > 0";
$result = $conn->query($sql);
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>

<?php
require_once 'conexiones/conexion.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Obtener los filtros de la URL (GET)
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$precio_max = isset($_GET['precio']) ? (int)$_GET['precio'] : 0;

// Construir la consulta SQL con filtros
$sql = "SELECT * FROM productos WHERE stock > 0";

if ($categoria) {
    $sql .= " AND categoria = '" . $conn->real_escape_string($categoria) . "'";
}

if ($precio_max > 0) {
    $sql .= " AND precio <= $precio_max";
}

$result = $conn->query($sql);
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <script src="https://kit.fontawesome.com/c64e771b8d.js" crossorigin="anonymous"></script>
    <title>E-commerce Deportivo</title>
</head>
<body>
    <div class="container">
        <nav id="carrito">
            <i class="fa-solid fa-cart-shopping">
                <div class="buy-card">
                    <ul class="nav-card">
                        <li>Imagen</li>
                        <li>Nombre</li>
                        <li>Precio</li>
                        <li>Cantidad</li>
                        <li></li>
                    </ul>
                    <div class="lista_de_productos">
                    </div>
                    <button id="vaciar_carrito">VACIAR CARRITO</button>
                    <button id="pagar_Ahora" onclick="pagar_compra()">Pagar Ahora</button>
                </div>
            </i>
        </nav>
        <h1>Artículos Deportivos</h1>
         <!-- Botón de filtro y formulario -->
         <div class="filtro-container">
            <button id="filtro-btn">Filtrar Productos</button>
            <div id="filtro-menu" class="filtro-menu">
                <form action="index.php" method="GET">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria" id="categoria">
                        <option value="">Todas</option>
                        <option value="Ciclismo">Ciclismo</option>
                        <option value="Futbol">Futbol</option>
                        <option value="Atletismo">Atletismo</option>
                    </select>

                    <label for="precio">Precio máximo:</label>
                    <input type="number" name="precio" id="precio" placeholder="Precio máximo" min="0">

                    <button type="submit">Aplicar Filtros</button>
                </form>
            </div>
        </div>
        <div class="grid" id="listaProductos">
            <?php foreach($productos as $producto): ?>
            <div class="item">
                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>">
                <div class="info">
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($producto['categoria']); ?></p>
                    <div class="precio">
                        <p class="descuento"><?php echo CURRENCY . number_format($producto['precio'], 0, ',', '.'); ?></p>
                        <p><?php echo CURRENCY . number_format($producto['precio_normal'], 0, ',', '.'); ?></p>
                    </div>
                    <button class="agregar-carrito" data-id="<?php echo $producto['id']; ?>">Agregar Al Carrito</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
