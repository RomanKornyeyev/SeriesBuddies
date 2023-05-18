<?php

    namespace clases\peticiones;

    class Peticion{

        private $esAdmin;

        public function __construct($esAdmin = false){
            $this->esAdmin = $esAdmin;
        }

        public function pintaSesionNoIniciada($id)
        {
            echo "
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

        public function pintaAmistadNula($id)
        {
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card'>Eliminar</button>";
            }
            echo "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-1'>
                        <button class='btn btn--card' onclick='subir(this)'>
                            <i class='fa-solid fa-id-card'></i>&nbsp;
                            <span class='primary-font'>Volver</span>
                            &nbsp;<i class='fa-solid fa-arrow-down'></i>
                        </button>
                    </div>
                    <div class='buddy__footer buddy__footer--primary $grid'>
                        <button class='btn btn--card'>Conectar</button>
                        <a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>
                        $eliminar
                    </div>
                </div>
            ";
        }

        public function pintaAmistadEnviada($id)
        {
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card' href='#'>Eliminar</button>";
            }
            echo "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-2'>
                        <button class='btn btn--card btn--error-card' href='#'>
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

        public function pintaAmistadRecibida($id)
        {
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card' href='#'>Eliminar</button>";
            }
            echo "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-3'>
                        <button class='btn btn--card btn--success-card' href='#'>
                            <span class='primary-font'>Aceptar&nbsp;</span>
                            <i class='fa-solid fa-check'></i>
                        </button>
                        <button class='btn btn--card btn--error-card' href='#'>
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

        public function pintaAmistadMutua($id)
        {
            $grid = "";
            $eliminar = "";
            if ($this->esAdmin) {
                $grid = "grid-col-3";
                $eliminar = "<button class='btn btn--card btn--error-card' href='#'>Eliminar</button>";
            }
            echo "
                <div class='buddy__footer-internal-layer' id='$id'>
                    <div class='buddy__footer buddy__footer--primary grid-col-2'>
                        <button class='btn btn--card btn--error-card' href='#'>
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
                            <span class='primary-font'>Es amig@</span>
                            &nbsp;<i class='fa-solid fa-arrow-up'></i>
                        </button>
                        <a class='btn btn--card' href='./profile.php?id=$id'>Perfil</a>
                        $eliminar
                    </div>
                </div>
            ";
        }

    }

?>