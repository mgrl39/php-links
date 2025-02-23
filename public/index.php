<?php
/*
require_once "../vendor/autoload.php";
require_once "../src/DatabaseController.php";
require_once "../src/LinkController.php";



?>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="mgrl39">
		<meta name="author" content="mgrl39">
		<meta name="generator" content="Hugo 0.122.0">
		<title> Hola </title>
		<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3"> -->
		<link href="assets/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- <link href="assets/style.css" rel="stylesheet">
		<link href="product.css" rel="stylesheet"> -->
	</head>
<body>
<div class="text-center">
<form method="post">
	<input type="submit" class="btn btn-primary btn-sm" name=""></button>
</form>
</div>

</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$projectController = new ProjectController();
	echo "<h2>" . $projectController->createLink() . "</h2>";
}
?>

<!-- https://www-geeksforgeeks-org.translate.goog/how-to-call-php-function-on-the-click-of-a-button/?_x_tr_sl=en&_x_tr_tl=es&_x_tr_hl=es&_x_tr_pto=sc -->
<!-- https://mdbootstrap.com/how-to/bootstrap/button-center/ -->

*/
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once "../vendor/autoload.php";

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/views/';

$chunks = explode("/", $request);
switch ($chunks[1]) {
	case '':
	case '/':
		require __DIR__ . $viewDir . 'links.php';
		break ;
	
	case 'token':
		$token = $chunks[2];
		require __DIR__ . $viewDir . 'tokens.php';
		break ;
	case 'all-links':
		require __DIR__ . $viewDir . 'all-links.php';
		break ;	
	case 'popular-links':
		require __DIR__ . $viewDir . 'popular-links.php';
		break ;
	case 'delete-links':
		require __DIR__ . $viewDir . 'delete-links.php';
		break ;
	case 'popular-link':
		require __DIR__ . $viewDir . 'popular-links.php';
		break ;
	case 'random-link':
		require __DIR__ . $viewDir . 'random-links.php';
		break ;
	case 'not-found':
	default:
		http_response_code(404);
		require __DIR__ . $viewDir . '404.php';
}