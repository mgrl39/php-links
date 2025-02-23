<?php
require_once "../vendor/autoload.php";

// Instancia del controlador
$controller = new LinkController();

// Elimina todos los links
// Si se eliminan correctamente, se mostrará un mensaje de éxito.
// De lo contrario, se mostrará un mensaje de error.
// El mensaje se mostrará en la vista.
// deletAllLinks() es un método que se encuentra en LinkController.
// Pero no es un metodo estático, por lo que se necesita una instancia de la clase.
if ($controller->deleteAllLinks()) {
    $message = "Nos hemos quedado sin links.";
} else {
    $message = "Ocurrió un error al eliminar los links.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Links</title>
    <link href="../assets/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/cover.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
        }
    </style>
</head>

<body class=" text-bg-dark d-flex justify-content-center align-items-center">
    <div class="col-md-12 text-center  ">
        <h1>🗑️ Eliminación de Links</h1>
        <p><?php echo $message; ?></p>
        <a href="/" class="btn btn-lg btn-primary fw-bold">Volver al inicio</a>
        <a onclick="window.location.href='/all-links'" class="btn-lg btn btn-success fw-bold">Todos los enlaces</a>
    </div>
</body>

</html>