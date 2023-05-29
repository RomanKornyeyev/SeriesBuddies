<?php

/*
Clase para facilitar las conexiones y consultas a bases de datos
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
  const PENDIENTE = "pendiente";
  const ACEPTADA = "aceptada";

  const REGISTROS_POR_PAGINA = 6;
  const MAX_BUDDIES_FEED = 3;
  const MAX_PAG_PAGINADOR = 3;

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
  
  // ====== SELECTS ======
  
  //Obtiene los datos personales de un usuario en concretado dado su id
  public static function obtenInfoBuddieTarjeta ($db, $idUsuario) {
    $db->ejecuta("SELECT 
                  u.id, u.nombre, u.img, 
                  CONCAT('@',u.nombre, '#', u.id)'alias', 
                  u.descripcion, 
                  DATE_FORMAT(ult_contacto, '%d %b %Y', 'es-ES')'fecha' 
                  FROM usuarios u 
                  WHERE u.id=?;", 
                  $idUsuario);

    return $db->obtenDatos()[0];
  }

  //Obtiene los ids de las series de un usuario en concreto dado su id
  public static function obtenInfoBuddieIdSeries ($db, $idUsuario) {
    $db->ejecuta("SELECT DISTINCT(id_serie) FROM respuestas WHERE id_usuario=?;", $idUsuario);
    return $db->obtenDatos();  
  }

  //Obtiene la imagen, nombre y su id de los amigos de un usuario en concreto
  public static function obtenInfoBuddieBuddies ($db, $idUsuario) {
    $db->ejecuta("SELECT 
                  u.nombre, u.img, id_receptor 
                  FROM peticiones p 
                  INNER JOIN usuarios u 
                  ON p.id_receptor=u.id 
                  WHERE estado='aceptada' 
                  AND id_emisor = ?;", 
                  $idUsuario);

    return $db->obtenDatos();
  }

  public static function obtenTotalUsuarios($db)
  {
    $db->ejecuta(
      "SELECT COUNT(id) AS total_usuarios FROM usuarios;"
    );
    $consulta = $db->obtenElDato();
    
    if ($consulta != "") {
      return $consulta;
    }else{
      return "";
    }
  }

  public static function obtenPeticion($db, $emisor, $receptor)
  {
    $db->ejecuta(
      "SELECT * FROM peticiones WHERE (id_receptor=? AND id_emisor=?) OR (id_receptor=? AND id_emisor=?);",
      $receptor, $emisor, $emisor, $receptor
    );
    $consulta = $db->obtenElDato();
    
    if ($consulta != "") {
      return $consulta;
    }else{
      return "";
    }
  }

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

  //Nombre, comentario, fecha e imagen del usuario + id de la serie en determinado rango
  public static function obtenRespuestasSerie ($db, $idSerie, $registroInicial)
  {
    $db->ejecuta("SELECT u.nombre, u.id as 'id_user', r.id as 'id_respuesta', id_serie, contenido, fecha, DATE_FORMAT(fecha, '%d %b %Y', 'es-ES') as fecha_formateada, img 
                  FROM respuestas r 
                  INNER JOIN usuarios u 
                  ON u.id=r.id_usuario 
                  WHERE id_serie=? 
                  ORDER BY fecha DESC
                  LIMIT ?, ?;", 
    $idSerie, $registroInicial, self::REGISTROS_POR_PAGINA);
    
    return $db->obtenDatos();
  }

  //Total de comentarios que hay de esa serie
  public static function obtenTotalRespuestas ($db, $idSerie)
  {
    $db->ejecuta('SELECT COUNT(*) FROM respuestas where id_serie = ?;', $idSerie);
    return $db->obtenElDato()['COUNT(*)'];  
  }

  public static function obtenInfoRespuesta($db, $idRespuesta)
  {
    $db->ejecuta(
      "SELECT u.id, contenido FROM respuestas r INNER JOIN usuarios u ON r.id_usuario=u.id WHERE r.id=?;",
      $idRespuesta
    );
    $consulta = $db->obtenElDato();
    // return $consulta;
    if ($consulta != "") {
      return $consulta;
    }else{
      return "";
    }
  }

  //Fotos de los primeros 5 usuarios que han comentado esta serie
  public static function obtenPrimerosBuddiesSerie ($db, $idSerie) {
    // $db->ejecuta('SELECT id_serie, u.id, u.img 
    // FROM respuestas r 
    // INNER JOIN usuarios u 
    // ON u.id=r.id_usuario 
    // WHERE id_serie=?
    // GROUP BY u.id
    // LIMIT ?;',
    // $idSerie, self::MAX_BUDDIES_FEED);
    $db->ejecuta('SELECT u.nombre, u.img, u.id, COUNT(r.id) AS total_respuestas
    FROM usuarios u
    JOIN respuestas r ON u.id = r.id_usuario
    WHERE r.id_serie = ?
    GROUP BY u.nombre
    ORDER BY total_respuestas DESC
    LIMIT ?;',
    $idSerie, self::MAX_BUDDIES_FEED);
    
    return $db->obtenDatos();
  }

  public static function obtenListadoBuddies ($db, $registroInicial) {
    //Devuelve la informacion del usuario que ha comentado en esa serie
    $db->ejecuta (
      "WITH t as (select u.id, u.nombre, u.img, CONCAT('@',u.nombre,'#', u.id) AS alias, count(p.id) as total_amigos 
      from usuarios u 
      left join peticiones p 
      on u.id=p.id_emisor 
      and p.estado='aceptada'
      group by u.id)
      
      SELECT t.id, t.nombre, t.img, t.alias, COUNT(DISTINCT(r.id_serie)) AS total_series, COUNT(r.id_serie) AS total_respuestas, t.total_amigos
      FROM t
      LEFT JOIN respuestas r ON t.id=r.id_usuario
      GROUP BY t.id LIMIT ?,?;",
      $registroInicial, self::REGISTROS_POR_PAGINA
    );
    $listadoBuddies = $db->obtenDatos();

    return $listadoBuddies;
  }

  public static function obtenListadoBuddiesPorSerie ($db, $idSerie, $registroInicial) {
    $db->ejecuta (
      "WITH t as (select u.id, u.nombre, u.img, CONCAT('@',u.nombre,'#', u.id) AS alias, count(p.id) as total_amigos 
      from usuarios u 
      left join peticiones p 
      on u.id=p.id_emisor 
      and p.estado='aceptada'
      group by u.id)
      
      SELECT t.id, t.nombre, t.img, t.alias, COUNT(DISTINCT(r.id_serie)) AS total_series, COUNT(r.id_serie) AS total_respuestas, t.total_amigos
      FROM t
      LEFT JOIN respuestas r ON t.id=r.id_usuario
      WHERE r.id_serie = ?
      GROUP BY t.id LIMIT ?,?;",
      $idSerie, $registroInicial, self::REGISTROS_POR_PAGINA
    );
    $listadoBuddies = $db->obtenDatos();

    return $listadoBuddies;
  }

  /*
  public static function obtenListadoBuddiesPorSerie ($db, $idSerie) {
    //Devuelve la informacion del usuario que ha comentado en esa serie
    $db->ejecuta ("SELECT DISTINCT(id_serie), u.id, nombre, img FROM respuestas r INNER JOIN usuarios u ON u.id=r.id_usuario WHERE id_serie=? LIMIT ?;", $idSerie, self::MAX_BUDDIES_FEED);
    $listadoBuddies = $db->obtenDatos();

    //Devuelve el usuario y el total de comentarios/respuestas que ha hecho en la pagina en general
    $db->ejecuta("SELECT id_usuario, COUNT(id_usuario) FROM respuestas GROUP BY id_usuario;");
    $totalRespuestas = $db->obtenDatos();
    
    //Devuelve el total de series en las que ha comentado un usuario
    $db->ejecuta("WITH t AS (SELECT id_serie, id_usuario FROM respuestas GROUP BY id_usuario, id_serie ORDER BY id_usuario, id_serie) SELECT COUNT(id_serie) FROM t GROUP BY id_usuario;");
    $totalSeries = $db->obtenDatos();

    foreach ($listadoBuddies as $key => $value) {
        if ($listadoBuddies[$key]['id'] == $totalRespuestas[$key]['id_usuario']) {
            $listadoBuddies[$key]['alias'] = "@".$listadoBuddies[$key]['nombre']."#".$listadoBuddies[$key]['id'];
            $listadoBuddies[$key]['total_series'] = $totalSeries[$key]['COUNT(id_serie)'];
            $listadoBuddies[$key]['total_respuestas'] = $totalRespuestas[$key]['COUNT(id_usuario)'];
            $listadoBuddies[$key]['total_buddies'] = random_int(1, 20);
            $listadoBuddies[$key]['total_chips'] = random_int(1, 20);
        }
    }

    return $listadoBuddies;
  }
  */


  // ====== INSERTS ======

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

  public static function insertarRespuesta($db, $idSerie, $idUsuario, $mensaje) : bool
  {
    $db->ejecuta(
      "INSERT INTO respuestas (id_serie, id_usuario, contenido) VALUES (?, ?, ?)",
      $idSerie, $idUsuario, $mensaje
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  // ====== UPDATES ======

  public static function actualizarRespuesta($db, $idRespuesta, $mensaje) : bool
  {
    $db->ejecuta(
      "UPDATE respuestas SET contenido=? WHERE id=?",
      $mensaje, $idRespuesta
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  public static function actualizarContra($db, $id, $pass) : bool
  {
    $db->ejecuta(
      "UPDATE usuarios SET contra = ? WHERE id = ?;",
      $pass, $id
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

  public static function actualizarContactoStaff($db, $id, $fecha) : bool
  {
    $db->ejecuta(
      "UPDATE usuarios SET ult_contacto = ? WHERE id = ?;",
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

  // ====== DELETES ======

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

  public static function eliminaRespuesta($db, $id) : bool
  {
    $db->ejecuta(
      "DELETE FROM respuestas WHERE id=?",
      $id
    );
    if ($db->getExecuted()) {
      return true;
    }else{
      return false;
    }
  }

  // ====== EXTRA (PAGINACION, ETC.) ======

  public static function obtenLimitesPaginacion ($paginaActual, $totalPaginas) {
    $limites['primera'] = $paginaActual - ($paginaActual % self::MAX_PAG_PAGINADOR) + 1;

    if ($limites['primera'] > $paginaActual) { 
      $limites['primera'] = $limites['primera'] - self::MAX_PAG_PAGINADOR; 
    }

    if ($limites['primera'] + (self::MAX_PAG_PAGINADOR-1) > $totalPaginas ) {
      $limites['ultima'] = $totalPaginas;
    } else {
      $limites['ultima'] = $limites['primera'] + (self::MAX_PAG_PAGINADOR-1);
    }

    return $limites;
  }

  public static function obtenPaginacion($paginaBase, $paginaActual, $totalPaginas, $argumentos = array()) : String
  {
    //variables a rellenar
    $paginacion = "";
    $args = "";
    
    //si el array tiene valores, rellenamos la variable args para meterla posteriormente al link
    if(!empty($argumentos)){
      foreach ($argumentos as $key => $value) {
        if($value != ""){
          $args .= "&".$key."=".$value;
        }
      }
    }
    
    //Botones << y < para aquellas que no sean la primera pagina
    if ($paginaActual != 1) {
      $paginacion = "
        <a href='./$paginaBase.php?pagina=1$args' class='btn btn--pagination-size'>&lt;&lt;</a>
        <a href='./$paginaBase.php?pagina=".($paginaActual-1)."$args' class='btn btn--pagination-size'>&lt;</a>
      ";
    }

    //Pagina actual
    $paginacion = $paginacion . "&nbsp;<span class='primary-color'>".$paginaActual." de ".$totalPaginas ."</span>&nbsp;";

    //Boton > y >>
    if ($paginaActual != $totalPaginas) {
      $paginacion = $paginacion . "
        <a href='./$paginaBase.php?pagina=".($paginaActual+1)."$args' class='btn btn--pagination-size'>&gt;</a>
        <a href='./$paginaBase.php?pagina=".$totalPaginas."$args' class='btn btn--pagination-size'>&gt;&gt;</a>
      ";
    }

    return "<div class='pages'>".$paginacion."</div>";
  }

  //obtener la URL actual
  public static function obtenUrlActual() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $currentURL = $protocol . $domain . $_SERVER['REQUEST_URI'];

    return $currentURL;
  }

  //quitar un parametro a la URL
  // *********** QUITAR PUERTO PARA PRODUCCIÓN *******************
  public static function eliminarParametroUrl($url, $parametro) : String
  {
    // Obtener las partes de la URL
    $parsedUrl = parse_url($url);
    print_r($parsedUrl);

    // Verificar si existe la query en la URL
    if (isset($parsedUrl['query'])) {
      // Convertir la query en un array asociativo
      parse_str($parsedUrl['query'], $queryArray);

      // Remover el parámetro "action" del array
      unset($queryArray["".$parametro]);

      // Reconstruir la query sin el parámetro "action"
      $newQuery = http_build_query($queryArray);

      // Reconstruir la URL con la nueva query
      $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . ":" . $parsedUrl['port'] . $parsedUrl['path'] . '?' . $newQuery;
      
      return $newUrl;
    } else {
      return $url; // No hay query en la URL
    }
  }
  


}

?>