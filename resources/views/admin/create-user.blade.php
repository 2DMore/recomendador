<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendador</title>
    <link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon">
    <script src="js/script.js" defer></script>
    <!-- <link rel="stylesheet" href="public/css/validator.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    @include('partials.navbar')
    <div class="content">
        <div class="title">
            <h3>Agregar usuario</h3>
        </div>

        <div class="body">

            <div class="inputItem">
                <label for="">Nombre</label>
                <div class="inputField">
                    <input type="text">
                </div>
            </div>
            <div class="inputItem">
                <label for="">Correo</label>
                <div class="inputField">
                    <input type="mail">
                </div>
            </div>
            <div class="inputItem">
                <label for="">Contraseña</label>
                <div class="inputField">
                    <input type="password">
                </div>
            </div>
            <div class="inputItem">
                <label for="">Rol</label>
                <div class="inputField">
                    <select>
                        <option selected>Elige un rol</option>
                    </select>
                </div>
            </div>

            <div class="actions">
                <button class="btn successBtn">Validar</button>
            </div>
        </div>
    </div>
</body>

</html>