<?php global $url_base; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendador - iniciar sesión</title>
    <link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="<?php  echo $url_base?>css/style.css">
    <link rel="stylesheet" href="<?php  echo $url_base?>css/login.css">
    <!-- <link rel="stylesheet" href="public/css/login.css"> -->
</head>

<body>
    <div class="loginForm">
        <img class="logo" src="images/UADY_logo.svg" alt="" srcset="">
        <form action="">
            <div class="loginField">
                <label for="">Usuario</label>
                <input type="text">
            </div>
            <div class="loginField">
                <label for="">Contraseña</label>
                <input type="password">
            </div>

            <button class="btn successBtn mb1">Ingresar</button>
            <button class="btn resetPassword ">Recuperar contraseña</button>
        </form>
    </div>

</body>

</html>