<?php


    //*********** DA DEMASIADOS PROBLEMAS Y QUEBRADEROS DE CABEZA, DESCARTADA DE MOMENTO ************
    //*********** TAMBIÉN COMENTAR QUE NO LE VEO VENTAJAS ************
    // queda aquí el intento


class DatosUsuario {

    private $id;
    private $nombre;
    private $contra;
    private $img;
    private $correo;
    private $descripcion;
    private $privilegios;

    const ADMIN = "admin";
    const USUARIO = "usuario";

    public function __construct($id, $nombre = "anónimo", $contra, $img = "./img/perfiles/default.png", $correo, $descripcion = "", $privilegios = self::USUARIO) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->contra = $contra;
        $this->img = $img;
        $this->correo = $correo;
        $this->descripcion = $descripcion;
        $this->privilegios = $privilegios;
    }

    public function getId() { return $this->id;}
    public function setId($id) { $this->id = $id; }
    public function getNombre() { return $this->nombre;}
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function getContra() { return $this->contra;}
    public function setContra($contra) { $this->contra = $contra; }
    public function getImg() { return $this->img;}
    public function setImg($img) { $this->img = $img; }
    public function getCorreo() { return $this->correo;}
    public function setCorreo($correo) { $this->correo = $correo; }
    public function getDescripcion() { return $this->descripcion;}
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function getPrivilegios() { return $this->privilegios;}
    public function setPrivilegios($privilegios) { $this->privilegios = $privilegios; }

    public function esAdmin() : bool
    {
        if ($this->privilegios == self::ADMIN)
            return true;
        else
            return false;
    }
}

?>