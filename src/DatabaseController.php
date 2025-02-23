<?php

// General singleton class.

/*
 * DatabaseController
 * Creo una clase que se encargará de la conexión a la base de datos.
 * Defino 4 variables estáticas que contienen los datos de conexión.
 * Que sean estáticas significa que no necesito instanciar la clase para acceder a ellas.
 * Los valores son la dirección del servidor, 
 * el nombre de usuario, 
 * la contraseña
 * el nombre de la base de datos.
 * options es un array que contiene dos opciones:
 * ERRMODE_EXCEPTION: para que lance una excepción si hay un error.
 * MYSQL_ATTR_INIT_COMMAND: para que establezca la codificación de caracteres en utf8.
 * Si no le pongo ERRMODE_EXCEPTION, PDO no lanzará una excepción si hay un error,
 * lo que significa que no sabré si la conexión ha fallado.
 * Con SET NAMES utf8, establezco la codificación de caracteres en utf8.
 * Eso significa que puedo almacenar caracteres especiales en la base de datos.
 */

class DatabaseController {
	private static $host = "localhost";
	private static $username = "usuario";
	private static $password = "MyNewPass123*";
	private static $dbname = "links";
	//private $dsn = 'mysql:host='.$host.';dbname='.$dbname;
	private static $options = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
	);

	/* La instancia de la clase. Si es null, no hay ninguna instancia.
	 * Si no es null, hay una instancia de la clase.
	 */

	// Hold the class instance.
	private static $instance = null;

	// The constructor is private
	// to prevent initiation with outer code.
	/* El constructor es privado para evitar que se inicie con código externo.
	 * La única forma de obtener una instancia de la clase es a través del método getInstance.
	 */
	private function __construct()
	{
		// The expensive process (e.g.,db connection) goes here.
	}

	// The object is created from within the class itself
	// only if the class has no instance.
	/* El objeto se crea desde dentro de la clase si la clase no tiene ninguna instancia.
	 * Si la clase tiene una instancia, se devuelve esa instancia.
	 */
	public static function getInstance()
	{
		// El self significa que se refiere a la clase actual.
		if (self::$instance == null)
		{
			self::$instance = new DatabaseController();
		}

		// Devuelve la instancia de la clase.
		return self::$instance;
	}
	/* Con el método connect, creo una conexión a la base de datos.
	 * Creo una instancia de PDO y le paso los datos de conexión.
	 * Si la conexión tiene éxito, devuelvo la conexión.
	 * Si falla, lanzo una excepción y devuelvo null.
	 * Devolver la conexión significa que puedo usarla en otras partes de mi código.
	 * La variable $connection contiene la conexión a la base de datos.
	 * PDO es una clase que PHP proporciona para conectarse a una base de datos.
	 * La clase PDO tiene un constructor que toma 4 argumentos:
	 * el nombre del servidor, el nombre de la base de datos, el nombre de usuario y la contraseña.
	 * El constructor crea una conexión a la base de datos.
	 * Si la conexión tiene éxito, devuelve un objeto PDO.
	 * Si falla, lanza una excepción.
	 * La excepción se captura en el bloque catch.
	 * El bloque catch captura la excepción y la imprime en la pantalla.
	 * Si la conexión falla, devuelvo null.
	 */
	public static function connect () {
		try  {
			$connection = new PDO('mysql:host='.self::$host.';dbname='.self::$dbname, self::$username, self::$password, self::$options);
			return $connection;

		} catch(PDOException $error) {
			//echo $sql . "<br>" . $error->getMessage();
			echo "<br>" . $error->getMessage();
			return null;
		}
	}
}
