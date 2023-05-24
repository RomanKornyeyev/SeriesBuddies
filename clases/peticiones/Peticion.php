<?php

    namespace clases\peticiones;

    class Peticion{

        private $esAdmin;

        public const ESTADO_PENDIENTE = "pendiente";
        public const ESTADO_ACEPTADO = "aceptada";

        public const ACCION_ENVIAR = "enviar";
        public const ACCION_CANCELAR = "cancelar";
        public const ACCION_ACEPTAR = "aceptar";
        public const ACCION_RECHAZAR = "rechazar";
        public const ACCION_ELIMINAR = "eliminar";


        public function __construct($esAdmin = false){
            $this->esAdmin = $esAdmin;
        }

        public function pintaSesionNoIniciada($id) : String
        {
            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-1'>
                        <button class='btn btn--card' onclick='subir(this)'>
                            <i class='fa-solid fa-id-card'></i>&nbsp;
                            <span class='primary-font'>Volver</span>
                            &nbsp;<i class='fa-solid fa-arrow-down'></i>
                        </button>
                    </div>
                    <div class='buddy__footer buddy__footer--primary grid-col-1'>
                        <a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>
                    </div>
                </div>
            ";
        }

        public function pintaAmistadNula($id, $paginaActual, $totalPaginas) : String
        {
            $prueba = "enviar";
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
            }
            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-1'>
                        <button class='btn btn--card' onclick='subir(this)'>
                            <i class='fa-solid fa-id-card'></i>&nbsp;
                            <span class='primary-font'>Volver</span>
                            &nbsp;<i class='fa-solid fa-arrow-down'></i>
                        </button>
                    </div>
                    <div class='buddy__footer buddy__footer--primary $grid'>
                        <button class='btn btn--card' onclick='peticion(this, $id, `".self::ACCION_ENVIAR."`, $paginaActual, $totalPaginas)'>Conectar</button>
                        <a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaAmistadEnviada($id, $paginaActual, $totalPaginas) : String 
        {
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
            }
            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-2'>
                        <button class='btn btn--card btn--error-card' onclick='peticion(this, $id, `".self::ACCION_CANCELAR."`, $paginaActual, $totalPaginas)'>
                            <span class='primary-font'>Cancelar&nbsp;</span>
                            <i class='fa-solid fa-xmark'></i>
                        </button>
                        <button class='btn btn--card' onclick='subir(this)'>
                            <i class='fa-solid fa-id-card'></i>&nbsp;
                            <span class='primary-font'>Volver</span>
                            &nbsp;<i class='fa-solid fa-arrow-down'></i>
                        </button>
                    </div>
                    <div class='buddy__footer buddy__footer--primary $grid'>
                        <button class='btn btn--card' onclick='bajar(this)'>
                            <i class='fa-solid fa-user-group'></i>&nbsp;
                            <span class='primary-font'>Enviada</span>
                            &nbsp;<i class='fa-solid fa-arrow-up'></i>
                        </button>
                        <a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaAmistadRecibida($id, $paginaActual, $totalPaginas) : String
        {
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
            }
            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-3'>
                        <button class='btn btn--card btn--success-card' onclick='peticion(this, $id, `".self::ACCION_ACEPTAR."`, $paginaActual, $totalPaginas)'>
                            <span class='primary-font'>Aceptar&nbsp;</span>
                            <i class='fa-solid fa-check'></i>
                        </button>
                        <button class='btn btn--card btn--error-card' onclick='peticion(this, $id, `".self::ACCION_RECHAZAR."`, $paginaActual, $totalPaginas)'>
                            <span class='primary-font'>Rechazar&nbsp;</span>
                            <i class='fa-solid fa-xmark'></i>
                        </button>
                        <button class='btn btn--card' onclick='subir(this)'>
                            <i class='fa-solid fa-id-card'></i>&nbsp;
                            <span class='primary-font'>Volver</span>
                            &nbsp;<i class='fa-solid fa-arrow-down'></i>
                        </button>
                    </div>
                    <div class='buddy__footer buddy__footer--primary $grid'>
                        <button class='btn btn--card' onclick='bajar(this)'>
                            <i class='fa-solid fa-user-group'></i>&nbsp;
                            <span class='primary-font'>Recibida</span>
                            &nbsp;<i class='fa-solid fa-arrow-up'></i>
                        </button>
                        <a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaAmistadMutua($id, $paginaActual, $totalPaginas) : String
        {
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
            }
            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-2'>
                        <button class='btn btn--card btn--error-card' onclick='peticion(this, $id, `".self::ACCION_ELIMINAR."`, $paginaActual, $totalPaginas)'>
                            <span class='primary-font'>Eliminar&nbsp;</span>
                            <i class='fa-solid fa-xmark'></i>
                        </button>
                        <button class='btn btn--card' onclick='subir(this)'>
                            <i class='fa-solid fa-id-card'></i>&nbsp;
                            <span class='primary-font'>Volver</span>
                            &nbsp;<i class='fa-solid fa-arrow-down'></i>
                        </button>
                    </div>
                    <div class='buddy__footer buddy__footer--primary $grid'>
                        <button class='btn btn--card' onclick='bajar(this)'>
                            <i class='fa-solid fa-user-group'></i>&nbsp;
                            <span class='primary-font'>Sois amig@s</span>
                            &nbsp;<i class='fa-solid fa-arrow-up'></i>
                        </button>
                        <a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaCardFooter($sesionIniciada, $esAdmin, $db, $id, $idUsuarioActual, $paginaActual, $totalPaginas) : String
        {
            // //si el user no tiene sesión iniciada
            // if (!$sesionIniciada) {
            //     return $peticionFooter->pintaSesionNoIniciada($id);

            // //si el user SI TIENE la sesión iniciada
            // }else{
            //     echo "antes de la consulta";
            //     //obtenemos info sobre el estado de petición de amistad
            //     $peticion = $db->obtenPeticion($db, $idUsuarioActual, $id);
            //     echo "despues de la cons * ".$peticion;

            //     //si ninguno ha mandado petición de amistad
            //     if ($peticion == "" || $peticion == null) {
            //         echo $peticionFooter->pintaAmistadNula($id, $paginaActual, $totalPaginas);
                     
            //     //si el user actual (SESIÓN) ha ENVIADO peti al user seleccioando
            //     }else if($peticion['estado'] == self::ESTADO_PENDIENTE && $peticion['id_emisor'] == $idUsuarioActual) {
            //         return $peticionFooter->pintaAmistadEnviada($id, $paginaActual, $totalPaginas);

            //     //si el user actual (SESIÓN) ha RECIBIDO peti del user seleccioando    
            //     }else if($peticion['estado'] == self::ESTADO_PENDIENTE && $peticion['id_receptor'] == $idUsuarioActual) {
            //         return $peticionFooter->pintaAmistadRecibida($id, $paginaActual, $totalPaginas);

            //     //si son AMOGUS  
            //     }else if($peticion['estado'] == self::ESTADO_ACEPTADA) {
            //         return $peticionFooter->pintaAmistadMutua($id, $paginaActual, $totalPaginas);
            //     }
            // }
        }

    }

?>