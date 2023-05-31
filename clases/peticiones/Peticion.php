<?php

    namespace clases\peticiones;

    class Peticion{

        private $esAdmin;

        public const FOOTER_BUDDIES = 1;
        public const FOOTER_PROFILE = 2;
        public const NOTIFICACION_PROFILE = 3;

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

        public function pintaAmistadNula($id, $paginaActual, $totalPaginas, $tipo) : String
        {
            $grid = "";
            $perfil = "";
            $eliminar = "";
            
            if($tipo == self::FOOTER_BUDDIES){
                $grid = "grid-col-2";
                $perfil = "<a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>";
                if ($this->esAdmin) {
                    $grid = "grid-col-3";
                    $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
                }
            }else if ($tipo == self::FOOTER_PROFILE){
                $grid = "grid-col-1";
                $perfil = "";
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
                        <button class='btn btn--card' onclick='peticion(this, $id, `".self::ACCION_ENVIAR."`, $paginaActual, $totalPaginas, $tipo)'>Conectar</button>
                        $perfil
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaAmistadEnviada($id, $paginaActual, $totalPaginas, $tipo) : String 
        {
            $grid = "xd";
            $perfil = "";
            $eliminar = "";

            if($tipo == self::FOOTER_BUDDIES){
                $grid = "grid-col-2";
                $perfil = "<a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>";
                if ($this->esAdmin) {
                    $grid = "grid-col-3";
                    $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
                }
            }else if (intval($tipo) == self::FOOTER_PROFILE){
                $grid = "grid-col-1";
                $perfil = "";
            }

            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-2'>
                        <button class='btn btn--card btn--error-card' onclick='peticion(this, $id, `".self::ACCION_CANCELAR."`, $paginaActual, $totalPaginas, $tipo)'>
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
                        $perfil
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaAmistadRecibida($id, $paginaActual, $totalPaginas, $tipo) : String
        {
            $grid = "";
            $perfil = "";
            $eliminar = "";

            if($tipo == self::FOOTER_BUDDIES){
                $grid = "grid-col-2";
                $perfil = "<a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>";
                if ($this->esAdmin) {
                    $grid = "grid-col-3";
                    $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
                }
            }else if ($tipo == self::FOOTER_PROFILE){
                $grid = "grid-col-1";
                $perfil = "";
            }

            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-3'>
                        <button class='btn btn--card btn--success-card' onclick='peticion(this, $id, `".self::ACCION_ACEPTAR."`, $paginaActual, $totalPaginas, $tipo)'>
                            <span class='primary-font'>Aceptar&nbsp;</span>
                            <i class='fa-solid fa-check'></i>
                        </button>
                        <button class='btn btn--card btn--error-card' onclick='peticion(this, $id, `".self::ACCION_RECHAZAR."`, $paginaActual, $totalPaginas, $tipo)'>
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
                        $perfil
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaAmistadRecibidaNotificacion($id, $nb, $img) : String
        {
            return "
                <div class='petition-card'>
                    <div class='petition-card__info'>
                        <div class='petition-card-img-wrapper'>
                            <a href='./profile.php?id=$id'>
                                <img class='img-fit' src='$img' alt='img-perfil'>
                            </a>
                        </div>
                        <h3 class='petition-card-name'><a href='./profile.php?id=$id'>$nb</a></h3>
                    </div>
                
                
                    <div class='buddy__footer-external-layer'>
                        <div class='buddy__footer-internal-layer' id='$id'>
                            <div class='buddy__footer buddy__footer--primary grid-col-1'>
                                <button class='btn btn--card' onclick='subir(this)'>
                                    <i class='fa-solid fa-id-card'></i>&nbsp;
                                    <span class='primary-font'>Volver</span>
                                    &nbsp;<i class='fa-solid fa-arrow-down'></i>
                                </button>
                            </div>
                            <div class='buddy__footer buddy__footer--primary grid-col-2'>
                                <button class='btn btn--card btn--success-card' onclick='peticionNotificacion(this, $id, `aceptar`, `".self::NOTIFICACION_PROFILE."`)'>
                                    <span class='primary-font'>Aceptar&nbsp;</span>
                                    <i class='fa-solid fa-check'></i>
                                </button>
                                <button class='btn btn--card btn--error-card' onclick='peticionNotificacion(this, $id, `rechazar`, `".self::NOTIFICACION_PROFILE."`)'>
                                    <span class='primary-font'>Rechazar&nbsp;</span>
                                    <i class='fa-solid fa-xmark'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            ";
        }

        public function pintaAmistadMutua($id, $paginaActual, $totalPaginas, $tipo) : String
        {
            $grid = "";
            $perfil = "";
            $eliminar = "";

            if($tipo == self::FOOTER_BUDDIES){
                $grid = "grid-col-2";
                $perfil = "<a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>";
                if ($this->esAdmin) {
                    $grid = "grid-col-3";
                    $eliminar = "<button class='btn btn--card btn--error-card' onclick='eliminar(this, $id, $paginaActual, $totalPaginas)'>Eliminar</button>";
                }
            }else if ($tipo == self::FOOTER_PROFILE){
                $grid = "grid-col-1";
                $perfil = "";
                // if ($this->esAdmin) {
                //     $grid = "grid-col-2";
                //     $eliminar = "<button class='btn btn--card btn--error-card'>Eliminar</button>";
                // }
            }

            return "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-2'>
                        <button class='btn btn--card btn--error-card' onclick='peticion(this, $id, `".self::ACCION_ELIMINAR."`, $paginaActual, $totalPaginas, $tipo)'>
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
                            <span class='primary-font'>Amig@s</span>
                            &nbsp;<i class='fa-solid fa-arrow-up'></i>
                        </button>
                        $perfil
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