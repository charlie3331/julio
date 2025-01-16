<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Cambiar si usas un servidor diferente
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña del usuario
$dbname = "kango"; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si los datos fueron enviados desde el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $noParte = $_POST['noParte'];

    // Validar que al menos uno de los campos no esté vacío
    if (!empty($nombre) || !empty($noParte)) {
        // Construir la consulta SQL dinámica
        $sql = "DELETE FROM productos WHERE ";
        $params = [];
        $types = "";

        if (!empty($nombre)) {
            $sql .= "nombre = ?";
            $params[] = $nombre;
            $types .= "s";
        }

        if (!empty($noParte)) {
            $sql .= (count($params) > 0 ? " OR " : "") . "noParte = ?";
            $params[] = $noParte;
            $types .= "s";
        }

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            // Ejecutar y verificar si la eliminación fue exitosa
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Producto eliminado exitosamente.";
                } else {
                    echo "No se encontró ningún producto con los datos proporcionados.";
                }
            } else {
                echo "Error al eliminar el producto: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    } else {
        echo "Por favor, proporciona al menos un criterio (Nombre o Número de Parte).";
    }
}

// Cerrar conexión
$conn->close();
?>
