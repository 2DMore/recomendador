<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendador</title>
    <link rel="shortcut icon" href="public/images/favicon.svg" type="image/x-icon">
    <script src="js/script.js" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/list-user.css">
</head>
<body>
    
     @include('partials.navbar')
    <div class="content">
        <div class="title">
            <h3>Lista de usuarios agregados</h3>
        </div>

        <div class="body">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Alejandro</td>
                        <td>alex@hotmail.com</td>
                        <td>Usuario</td>
                        <td>
                            <button class="btn dangerBtn">Eliminar</button>
                            <button class="btn warningBtn">Editar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Alejandro</td>
                        <td>alex@hotmail.com</td>
                        <td>Administrador</td>
                        <td>
                            <button class="btn dangerBtn">Eliminar</button>
                            <button class="btn warningBtn">Editar</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Alejandro</td>
                        <td>alex@hotmail.com</td>
                        <td>Validador</td>
                        <td>
                            <button class="btn dangerBtn">Eliminar</button>
                            <button class="btn warningBtn">Editar</button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>