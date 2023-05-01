<?php

/*
Clase para facilitar las conexiones y consultas a bases de datos
Por Jorge Dueñas Lerín
*/


class DWESBaseDatos {

  private $conexion = null;
  private $sentencia = null;
  private $executed = false;
  
  const LONG_TOKEN = 64;
  const ADMIN = "admin";
  const USUARIO = "usuario";
  const VERIFICADO_SI = "si";
  const VERIFICADO_NO = "no";

  /*
    Patrón Singletone para poder usar la clase en proyectos más grandes
  */

  private static $instanciaUnica = null;

  private function __construct() { } // Solo se puede crear desde el método obtenerInstancia

  public static function obtenerInstancia() {
    if (self::$instanciaUnica == null)
    {
      self::$instanciaUnica = new DWESBaseDatos();
    }

    return self::$instanciaUnica;
  }

  function inicializa(
    $basedatos,         // Nombre debe ser especificado O el archivo si es SQLite
    $usuario  = 'root', // Ignorado si es SQLite
    $pass     = '1234', // Ignorado si es SQLite
    $motor    = 'mysql',
    $serverIp = 'localhost',
    $charset  = 'utf8mb4',
    $options  = null
  ) {
    if($motor != "sqlite") {
      $cadenaConexion = "$motor:host=$serverIp;dbname=$basedatos;charset=$charset";
    } else {
      $cadenaConexion = "$motor:$basedatos";
    }

    if($options == null){
      $options = [
        PDO::ATTR_EMULATE_PREPARES   => false, // La preparación de las consultas no es simulada
                                                // más lento pero más seguro
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Cuando se produce un error
                                                                // salta una excepción
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Cuando traemos datos lo hacemos como array asociativo
      ];
    }

    try {
      if($motor != "sqlite") {
        $this->conexion = new PDO($cadenaConexion, $usuario, $pass, $options);
      } else {
        $this->conexion = new PDO($cadenaConexion, null, null, $options);
      }
    } catch (Exception $e) {
      error_log($e->getMessage());
      exit('No ha sido posible la conexión');
    }
  }

  /*
    Permite ejecutar una consulta preparada con parámetros posicionales.
      Parámetros
        1º SQL
        2º ... parámetros o array con parámetros
  */
  function ejecuta(string $sql, ...$parametros) {
    $this->sentencia = $this->conexion->prepare($sql);

    if($parametros == null){
      $this->executed = $this->sentencia->execute();
      return;
    }

    if($parametros != null && is_array($parametros[0])) {
      $parametros = $parametros[0]; // Si nos pasan un array lo usamos como parámetro
    }

    $this->executed = $this->sentencia->execute($parametros);
  }

  function obtenDatos(){
    return $this->sentencia->fetchAll();
  }

  function obtenElDato(){
    return $this->sentencia->fetch();
  }

  function getLastId(){
    return $this->conexion->lastInsertId();
  }

  function getExecuted(){
    return $this->executed;
  }

  function __destruct(){
    $this->conexion = null;
  }

  // ***************** MANEJO DE DATOS *****************

  public static function obtenUsuarioPorMail($db, $correo)
  {
    $db->ejecuta(
      "SELECT * FROM usuarios WHERE correo=?;",
      $correo
    );
    $consulta = $db->obtenElDato();
    // return $consulta;
    if ($consulta != "") {
      return $consulta;
    }else{
      return "";
    }
  }

  public static function obtenUsuarioPorToken($db, $tkn)
  {
    $db->ejecuta(
      "SELECT * FROM usuarios WHERE id=(SELECT id_usuario FROM tokens WHERE valor=?);",
      $tkn
    );
    $consulta = $db->obtenElDato();
    // return $consulta;
    if ($consulta != "") {
      return $consulta;
    }else{
      return "";
    }
  }

  public static function obtenToken($db, $tkn)
  {
    $db->ejecuta(
      "SELECT * FROM tokens WHERE valor=?;",
      $tkn
    );
    $consulta = $db->obtenElDato();
    // return $consulta;
    if ($consulta != "") {
      return $consulta;
    }else{
      return "";
    }
  }

  public static function insertarUsuario($db, $nombre, $contra, $correo, $privilegio, $verificado) : bool
  {
    $db->ejecuta(
      "INSERT INTO usuarios (nombre, contra, correo, privilegio, verificado) VALUES (?,?,?,?,?);",
      $nombre, $contra, $correo, $privilegio, $verificado
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  public static function insertarToken($db, $id, $token) : bool
  {
    $db->ejecuta(
      "INSERT INTO tokens (id_usuario, valor) VALUES (?, ?);",
      $id, $token
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  public static function actualizarImgUsuario($db, $id, $img) : bool
  {
    $db->ejecuta(
      "UPDATE usuarios SET img = ? WHERE id = ?;",
      $img, $id
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  public static function actualizarSolicitudTkn($db, $id, $fecha) : bool
  {
    $db->ejecuta(
      "UPDATE usuarios SET ult_tkn_solicitado = ? WHERE id = ?;",
      $fecha, $id
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  public static function verificaUsuario($db, $id) : bool
  {
    $db->ejecuta(
      "UPDATE usuarios SET verificado = ? WHERE id = ?;",
      self::VERIFICADO_SI, $id
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  public static function eliminaTokensUsuario($db, $id) : bool
  {
    $db->ejecuta(
      "DELETE FROM tokens WHERE id_usuario=?",
      $id
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }
}

?>