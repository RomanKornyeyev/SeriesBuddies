<?php 

    namespace clases\mailer;

    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mailer{

        public static function sendEmail($correo, $asunto, $cuerpo)
        {
            //Create an instance; passing `true` enables exceptions
            global $CONFIG;
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();                                            //Send using SMTP
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->Username   = $CONFIG["mail_user"];                   //SMTP username
                $mail->Password   = $CONFIG["mail_pass"];                   //SMTP password                

                //Recipients
                $mail->setFrom('no.reply.seriesbuddies@gmail.com', 'SeriesBuddies');    //desde donde lo envía
                $mail->addAddress($correo);                                             //Add a recipient

                //Content
                $mail->isHTML(true);                            //Set email format to HTML
                $mail->Subject = $asunto;
                $mail->Body    = $cuerpo;
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                //echo 'Message has been sent';
            } catch (Exception $e) {
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        // *********** MAILS *******************
        // MAIL VERIFICACIÓN
        public static function pintaEmailVerificacion($rutaBase, $nb, $tkn) : String
        {
            return "
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Verifica tu cuenta y únete a nuestra comunidad</title>
                </head>
                <body style='font-family: Arial, sans-serif; background-color: #f8f8f8; color: #333333;'>
                    <header style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select: none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </header>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff;'>
                        <h2 style='text-align: center;'>¡Verifica tu cuenta y conviértete en buddy!</h2>
                        <p>Hola <b>$nb</b>,</p>
                        <p>¡Bienvenido/a a SeriesBuddies! Estamos encantados de tenerte a bordo y queremos asegurarnos de que tu experiencia sea excepcional desde el principio. Para ello, necesitamos verificar tu cuenta y asegurarnos de que todos los datos sean correctos.</p>
                        <br>
                        <p style='text-align: center;'>Completa tu registro haciendo clic en el botón:
                            <br>
                            <a style='text-decoration: none; display: inline-block; user-select: none; cursor: pointer; white-space: nowrap; text-align: center; vertical-align: middle; font-weight: 700; font-size: 16px; padding: 7px 14px; background-color: #FFCD19; color: #000000; border-radius: 5px; border: 1px solid transparent;' href='".$rutaBase."/verify.php?token=$tkn'>COMPLETAR MI REGISTRO</a>
                            <br>
                        </p>
                        <br>
                        <p>Si el botón no funciona, también puedes copiar y pegar el siguiente enlace en la barra de direcciones de tu navegador:</p>
                        <p>$rutaBase/verify.php?token=$tkn</p>
                        <p>Una vez que hayas verificado tu cuenta, tendrás acceso completo a todas las funciones y beneficios de nuestra plataforma. Podrás interactuar con otros usuarios y recibir actualizaciones exclusivas.</p>
                        <p>¡Gracias por unirte a nuestra comunidad! Esperamos verte pronto.</p>
                        <p>Si no has creado una cuenta en nuestro sitio web, por favor ignora este correo electrónico. Es posible que alguien haya ingresado tu dirección de correo electrónico por error.</p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p>Equipo de SeriesBuddies</p>
                    </div>
                    <footer style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select:none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </footer>
                </body>
                </html>
            ";
        }


        // MAIL REGISTRO COMPLETADO
        public static function pintaEmailVerificacionCompletada($nb) : String
        {
            return "
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Verifica tu cuenta y únete a nuestra comunidad</title>
                </head>
                <body style='font-family: Arial, sans-serif; background-color: #f8f8f8; color: #333333;'>
                    <header style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select: none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </header>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff;'>
                        <h2 style='text-align: center;'>¡Cuenta verificada!</h2>
                        <p>¡Bienvenido/a a SeriesBuddies <b>$nb</b>! Tu cuenta ha sido verificada.</p>
                        <p>Ya tienes acceso completo a todas las funciones y beneficios de nuestra plataforma. Esperamos que tengas la mejor experiencia posible ¡Diviértete!</p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p>Equipo de SeriesBuddies</p>
                    </div>
                    <footer style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select:none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </footer>
                </body>
                </html>
            ";
        }

        // MAIL RECOVERY
        public static function pintaEmailRecovery($rutaBase, $nb, $tkn) : String
        {
            return "
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Verifica tu cuenta y únete a nuestra comunidad</title>
                </head>
                <body style='font-family: Arial, sans-serif; background-color: #f8f8f8; color: #333333;'>
                    <header style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select: none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </header>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff;'>
                        <h2 style='text-align: center;'>¡Reestablece tu contraseña!</h2>
                        <p>Hola <b>$nb</b>,</p>
                        <p>Has solicitado un reestablecer tu contraseña, haz click en el siguiente botón para reestablecerla:</p>
                        <br>
                        <p style='text-align: center;'>
                            <a style='text-decoration: none; display: inline-block; user-select: none; cursor: pointer; white-space: nowrap; text-align: center; vertical-align: middle; font-weight: 700; font-size: 16px; padding: 7px 14px; background-color: #FFCD19; color: #000000; border-radius: 5px; border: 1px solid transparent;' href='".$rutaBase."/recovery.php?token=$tkn'>REESTABLECER CONTRASEÑA</a>
                            <br>
                        </p>
                        <br>
                        <p>Si el botón no funciona, también puedes copiar y pegar el siguiente enlace en la barra de direcciones de tu navegador:</p>
                        <p>$rutaBase/recovery.php?token=$tkn</p>
                        <p>Si no has sido tu, símplemente ignora este mensaje. Es posible que alguien haya ingresado tu dirección de correo electrónico por error.</p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p>Equipo de SeriesBuddies</p>
                    </div>
                    <footer style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select:none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </footer>
                </body>
                </html>
            ";
        }

        // MAIL RECOVERY COMPLETED
        public static function pintaEmailRecoveryCompleted($nb) : String
        {
            return "
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Verifica tu cuenta y únete a nuestra comunidad</title>
                </head>
                <body style='font-family: Arial, sans-serif; background-color: #f8f8f8; color: #333333;'>
                    <header style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select: none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </header>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff;'>
                        <h2 style='text-align: center;'>¡Contraseña reestablecida!</h2>
                        <p>Hola <b>$nb</b>,</p>
                        <p>Tu contraseña ha sido reestablecida con éxito.</p>
                        <br>
                        <p>Saludos cordiales,</p>
                        <p>Equipo de SeriesBuddies</p>
                    </div>
                    <footer style='max-width: 600px; margin: 0 auto; padding: 20px; background-color: #ffffff; height: 35px; background-color: #17181D; text-align: center;'>
                        <img style='height:35px; user-select:none;' src='https://i.imgur.com/pt56BsH.png' alt='SeriesBuddies'>
                    </footer>
                </body>
                </html>
            ";
        }

    }

?>