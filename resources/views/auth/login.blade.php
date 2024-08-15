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
    @auth
        <div class="loginForm">
            <img class="logo" src="images/UADY_logo.svg" alt="" srcset="">
            <form action="/logout" method="POST">
                <button class="dangerBtn">Cerrar sesión</button>
            </form>
            <form action="/home" method="POST">
                <button class="primaryBtn">Cerrar sesión</button>
            </form>
        </div>
    @else
        <div class="loginForm">
            <@php

            @endphp
            @csrf
            <img class="logo" src="images/UADY_logo.svg" alt="" srcset="">
            <form action="/userlogin" method="POST">
                @csrf
                <div class="loginField">
                    <label for="">email</label>
                    <input name="email" type="text">
                </div>

                <div class="loginField">
                    <label for="">Contraseña</label>
                    <input name="password"  type="password">
                </div>

                <button type="submit" class="btn successBtn mb1">Ingresar</button>
            </form>
        </div>
    @endauth



</body>

</html>
