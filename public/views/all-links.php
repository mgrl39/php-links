<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Enlaces</title>
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
        <h1>😼 Todos los Enlaces 😼</h1>
        <main>
            <ul class="list-group">
                <?php
                $links = LinkController::getAllLinks();
                if (!$links)
                    echo '<li>No hay enlaces 😿 </li>';
                foreach ($links as $link) {
                    echo '<li><a href="http://www.links.local/token/' . htmlspecialchars($link->token) . '" target="_blank">http://www.links.local/token/' . htmlspecialchars($link->token) . '</a></li>';
                }
                ?>
            </ul>
            <br>
            <a href="/" class="btn btn-lg btn-primary fw-bold">Volver al inicio</a>
            <a href="" class="btn btn-lg btn-secondary fw-bold">Recargar pagina</a>
            <a onclick="window.location.href='/delete-links'" class="btn-lg btn btn-danger fw-bold">Borrar todos los links</a>
            <br>
            <br>
            <a onclick="window.location.href='/random-link'" class="btn-lg btn btn-outline-warning fw-bold">Ver enlace random</a>
            <a onclick="window.location.href='/popular-link'" class="btn-lg btn btn-outline-info fw-bold">Ver enlace mas usado</a>
    </div>
    <script src="../assets/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>