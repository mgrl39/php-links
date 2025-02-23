<?php
class LinkController {

    private $connection;
   
    /* Establece la conexión con la base de datos
     * Usa el metodo estático connect de la clase DatabaseController
     * un metodo estatico se puede llamar sin instanciar la clase
     * Los metodos estaticos son utiles para funciones que no dependen de un objeto en particular 
     */
    public function __construct() {
        $this->connection = DatabaseController::connect();
    }
    
    // Función auxiliar que simplifica la ejecución de consultas SQL
    private function executeQuery($sql, $params = [], $fetchAll = true) {
        // Hacemos un try catch para capturar errores de la base de datos
        try {
            // Preparamos la consulta
            $statement = $this->connection->prepare($sql);
            // Asignamos los valores a los parametros
            foreach ($params as $key => $value) {
                $statement->bindValue($key, $value);
            }
            // Establecemos el modo de recuperación de datos.
            // FETCH_OBJ devuelve un objeto anónimo con propiedades que corresponden a los nombres de las columnas devueltas en el conjunto de resultados
            // Eso significa que podemos acceder a los valores de las columnas de la consulta como si fueran propiedades de un objeto
            // FETCH_ASSOC devuelve un array asociativo
            // Para tontos, FETCH_OBJ es más fácil de usar y nos permite acceder a los valores de las columnas como si fueran propiedades de un objeto
            $statement->setFetchMode(PDO::FETCH_OBJ);
            // Ejecutamos la consulta
            $statement->execute();
    
            // Si $fetchAll es true, devolvemos todos los resultados
            if ($fetchAll) {
                return $statement->fetchAll();
            }
            // Si es false, devolvemos solo el primer resultado
            return $statement->fetch();
        } catch (PDOException $error) {
            // Si hay un error, mostramos el mensaje de error
            echo $sql . "<br>" . $error->getMessage();
            // Devolvemos false
            return false;
        }
    }
    public function getLinks() {
        // Consulta SQL para obtener todos los links
        // El WHERE 1 es solo para que la consulta sea válida
        // Podríamos haber hecho simplemente SELECT * FROM Links
        // Pero es una buena práctica usar WHERE 1 para evitar errores
        $sql = "SELECT * FROM Links WHERE 1";
        // Ejecutamos la consulta
        // executeQuery es un método auxiliar que simplifica la ejecución de consultas SQL
        // Lo que nos devuelva executeQuery lo devolvemos nosotros
        return $this->executeQuery($sql);
    }
    
    public function getLink($token) {
        // Consulta SQL para obtener un link por su token.
        // Usamos un parámetro :token para evitar inyección de SQL
        // se pone :token en la consulta y luego se asigna el valor a ese token en el array de parámetros
        // Es decir, :token se reemplaza por el valor de $token
        $sql = "SELECT * FROM Links WHERE token = :token";
        // El false al final indica que solo queremos un resultado
        return $this->executeQuery($sql, [':token' => $token], false);
    }
    
    public function exist($token) {
        // Consulta SQL para comprobar si un link existe
        // Usamos un parámetro :token para evitar inyección de SQL
        $sql = "SELECT * FROM Links WHERE token = :token";
        // Ejecutamos la consulta.
        // Si obtenemos un resultado, el link existe
        // Si no obtenemos un resultado, el link no existe
        $result = $this->executeQuery($sql, [':token' => $token], false);
        // Si obtenemos un resultado, devolvemos true
        // Si no obtenemos un resultado, devolvemos false
        return $result ? true : false;
    }
    
    /* Función para generar un token aleatorio */
	public function generateHash($size = 32)
	{
        // Caracteres que se pueden usar para generar el token
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        // Longitud de los caracteres
		$charactersLength = strlen($characters);
		$token = '';
        // Generamos un token de $size caracteres
		for ($i = 0 ; $i < $size ; $i++) {
            // random_int es una función segura para generar números aleatorios
            // le pasamos el rango de caracteres, que va del 0 al tamaño de los caracteres - 1
			$token .= $characters[random_int(0, $charactersLength - 1)];
		}
        // Devolvemos el token
		return ($token);
	}
    
    /* Función para añadir un link a la base de datos */
    public function numberOfUsages($token) {
        // Consulta SQL para obtener el número de usos de un link
        // Usamos un parámetro :token para evitar inyección de SQL
        $sql = "SELECT * FROM Links WHERE token = :token";
        // Ejecutamos la consulta y la guardamos en $link
        $link = $this->executeQuery($sql, [':token' => $token], false);
        // Si el link contiene algo, eso quiere decir que el link existe
        // Devolvemos el número de usos del link, para referirnos a un atributo de un objeto, se usa -> en lugar de .
        // link es un objeto, y usages es un atributo de ese objeto
        // si no, indicamos null
        return $link ? $link->usages : null;
    }

    /* Function que devuelve un enlace "aleatorio" de los que ya existen */
    public function randomExistentLink() {
        /* Consulta SQL para obtener un link aleatorio
         * Usamos ORDER BY RAND() para obtener un resultado aleatorio
         * LIMIT 1 para obtener solo un resultado */
        $sql = "SELECT * FROM Links ORDER BY RAND() LIMIT 1";
        // Ejecutamos la consulta y devolvemos el resultado.
        return $this->executeQuery($sql, [], false);
    }
    
    public function mostPopularLink() {
        $sql = "SELECT * FROM Links ORDER BY usages DESC LIMIT 1";
        return $this->executeQuery($sql, [], false);
    }
    
    public function addUsage($token) {
        // Obtenemos el link por su token
        // Si el link existe, obtenemos el link.
        if ($link = $this->getLink($token)) {
            // hacemos una consulta SQL para actualizar el número de usos del link
            $sql = "UPDATE Links SET usages = :usages WHERE token = :token";
            // Ejecutamos la consulta.
            return $this->executeQuery($sql, [':token' => $token, ':usages' => $link->usages + 1], false);
        } 
        return false;

    }
    public static function getAllLinks() {
        $sql = "SELECT * FROM Links WHERE 1";
        $statement = DatabaseController::connect()->prepare($sql);
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $statement->execute();
        return $statement->fetchAll();
    }

    public static function createLink() {
        // Generamos un token
        // new self() es lo mismo que new LinkController()
        // self se refiere a la clase actual
        // entonces new self() es lo mismo que new LinkController()
        // esto se usa para llamar a un método de la misma clase
        // pero porque hace falta el new? porque generateHash es un método de instancia, no un método estático
        // despues de ejecutar new self(), se ejecuta generateHash(32)
        $newHash = (new self)->generateHash(32);

        // Si el token ya existe, generamos otro token
        // Si el token no existe, lo añadimos a la base de datos
        if ((new self)->exist($newHash)) {
            return self::createLink();
        } else {
            try  {
                // Hago un insert en la tabla Links.
                // Los values tienen el : porque son parámetros
                $sql = "INSERT INTO Links (token, usages) VALUES (:token, :usages)";
            
                // El new self significa que se está llamando a un método de la misma clase
                // el (new self)->connection significa que se está llamando a la propiedad connection de la clase
                $statement = (new self)->connection->prepare($sql);
                $statement->bindValue(':token', $newHash);
                $statement->bindValue(':usages', 0);
                $statement->setFetchMode(PDO::FETCH_OBJ);
                $statement->execute();
                // Devolvemos el token
                return $newHash;
    
              } catch(PDOException $error) {
                  echo $sql . "<br>" . $error->getMessage();
                  return null;
              }
        }
    }
    
    /* Consulta SQL para eliminar todos los links.
     * Se usa un try catch para capturar errores de la base de datos
     * Si hay un error, se muestra el mensaje de error y se devuelve false 
     * Si no hay errores, se devuelve true
     * Siempre es buena práctica devolver true o false para saber si la operación ha sido exitosa
     */
    public function deleteAllLinks() {
        try {
            // Aqui no hemos hecho lo de executeQuery porque no necesitamos devolver nada
            $sql = "DELETE FROM Links";
            // Preparamos la consulta
            $statement = $this->connection->prepare($sql);
            // Ejecutamos la consulta
            $statement->execute();
            // Devolvemos true para indicar que la operación ha sido exitosa
            // Ya que si llegamos a este punto, es que no ha habido errores
            return true;
        } catch (PDOException $error) {
            // Si hay un error, mostramos el mensaje de error
            echo "Error: " . $error->getMessage();
            // Devolvemos false para indicar que la operación no ha sido exitosa
            return false;
        }
    }
    }
?> 