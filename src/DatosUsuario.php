<?php

class DatosUsuario {

    private $id;
    private $nombre;
    private $passwd;
    private $img;
    private $correo;
    private $descripcion;
    private $id_grupo;

    const ID_GRUPO_ADMIN = 1;
    const ID_GRUPO_USUARIO = 2;

    public function __construct($id, $nombre = "anónimo", $passwd, $img = "./img/perfiles/default.png", $correo, $descripcion = "", $id_grupo = self::ID_GRUPO_USUARIO) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->passwd = $passwd;
        $this->img = $img;
        $this->correo = $correo;
        $this->descripcion = $descripcion;
        $this->id_grupo = $id_grupo;
    }

    public function getId() { return $this->id;}
    public function setId($id) { $this->id = $id; }
    
    public function getNombre() { return $this->nombre;}
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getPasswd() { return $this->passwd;}
    public function setPasswd($passwd) { $this->passwd = $passwd; }

    public function getImg() { return $this->img;}
    public function setImg($img) { $this->img = $img; }

    public function getCorreo() { return $this->correo;}
    public function setCorreo($correo) { $this->correo = $correo; }

    public function getDescripcion() { return $this->descripcion;}
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getId_grupo() { return $this->id_grupo;}
    public function setId_grupo($id_grupo) { $this->id_grupo = $id_grupo; }

    public function validar(){
        return true;
    }
}

?>