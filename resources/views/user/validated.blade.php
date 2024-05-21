<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendador</title>
    <link rel="shortcut icon" href="images/favicon.svg" type="image/x-icon">
    <script src="js/script.js" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/validator.css">
    
</head>

<body>

     @include('partials.header')
     @include('partials.navbar')
    <div class="content">
        <div class="title">
            <h3>Documentos validados</h3>
        </div>

        <div class="body">
    <div class="list">
        <div class="listItem">
            <div class="item">
                <p class="name">Tesis 1</p>
                <label class="added">Agregado: <span class="date">12/12/2020</span></label>
            </div>
            <button class="btn watchItemBtn"></button>
        </div>
        <div class="listItem">
            <div class="item">
                <p class="name">Tesis 1</p>
                <label class="added">Agregado: <span class="date">12/12/2020</span></label>
            </div>
            <button class="btn watchItemBtn"></button>
        </div>
        <div class="listItem">
            <div class="item">
                <p class="name">Tesis 1</p>
                <label class="added">Agregado: <span class="date">12/12/2020</span></label>
            </div>
            <button class="btn watchItemBtn"></button>
        </div>
        <div class="listItem">
            <div class="item">
                <p class="name">Tesis 1</p>
                <label class="added">Agregado: <span class="date">12/12/2020</span></label>
            </div>
            <button class="btn watchItemBtn"></button>
        </div>
        <div class="listItem">
            <div class="item">
                <p class="name">Tesis 1</p>
                <label class="added">Agregado: <span class="date">12/12/2020</span></label>
            </div>
            <button class="btn watchItemBtn"></button>
        </div>
        
    </div>
</div>

    </div>
</body>

    </div>
    @include ('partials.footer')