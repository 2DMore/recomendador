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
    
    
    <div class="content">
        <div class="title">
            <h3>Subir un documento al Repositorio</h3>
        </div>

        <div class="body">
            <form action="upload/metadatos" method="POST">
                @csrf
                <label for="documento">Selecciona un documento:</label>
                <select name="id_doc" id="documento">
                    @foreach($documents as $document)
                        <option value="{{ $document->id }}">{{ $document->title->first()->title }}</option>
                    @endforeach
                </select>
                <button type="submit">Subir Metadatos</button>
            </form>
        </div>
    </div>
</body>

</html>