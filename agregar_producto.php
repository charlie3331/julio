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

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($noParte)) {
        // Preparar la consulta SQL
        $sql = "INSERT INTO productos (nombre, noParte) VALUES (?, ?)";

        // Preparar declaración
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $noParte);

        // Ejecutar y verificar si la inserción fue exitosa
        if ($stmt->execute()) {
            echo "Producto agregado exitosamente.";
        } else {
            echo "Error al agregar el producto: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}

// Cerrar conexión
$conn->close();
?>
