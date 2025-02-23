<?php

// La variable $token la creamos en index.php 
$linkController = new LinkController();

$emoji = "";

if ($linkController->exist($token)) {
    $linkController->addUsage($token);
    $usages = $linkController->numberOfUsages($token);

    // Se podia hacer perfectamente un switch.
    // Lo que tenemos aquí es un array asociativo.
    // Si el número de usos no está en el array, se mostrará un emoji por defecto.
    $emojis = [
        1 => "👍",
        2 => "👌",
        3 => "😘",
        4 => "🖕",
        5 => "⛔",
        6 => "🚫",
        7 => "💀",
        8 => "👻",
        9 => "👽",
        10 => "🤖"
    ];
    $emoji = $emojis[$usages] ?? "⛔";
} else {
    header("Location: http://www.links.local/not-found");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, 
                                        initial-scale=1.0" />
        <title>Token</title>
        <link href="../assets/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/cover.css" rel="stylesheet">
        <style>
            body,
            html {
                height: 100%;
            }
        </style>
    </head>

    <body class="d-flex justify-content-center text-bg-dark align-items-center">
        <div class="col-md-12 text-center ">
            <h1 class="fs-1"><?php echo $emoji ?></h1>
            <p>Usages: <?php echo $usages ?></p>
            <a href="" class="btn btn-lg btn-primary fw-bold">Recargar página</a>
            <a href="/" class="btn btn-lg btn-secondary fw-bold">Volver al inicio</a>
            <a onclick="window.location.href='/all-links'" class="btn-lg btn btn-success fw-bold">Todos los enlaces</a>
        </div>
    </body>
</html>