<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendador</title>
    <link rel="shortcut icon" href="public/images/favicon.svg" type="image/x-icon">
    <script src="js/script.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div id="confirmationPopup" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; background-color: #f0f0f0; border: 1px solid #ccc;">
        <p id="popupMessage"></p>
    </div>

    @if (session('status'))
        <script>
            $(document).ready(function(){
                $('#popupMessage').text('{{ session('status') }}');
                $('#confirmationPopup').show();

                setTimeout(function(){
                    $('#confirmationPopup').fadeOut('slow');
                }, 3000); // Ocultar el popup después de 3 segundos
            });
        </script>
    @endif

    <script>
        $(document).ready(function(){
            $('#uploadForm').on('submit', function(e){
                e.preventDefault(); // Evitar que el formulario se envíe normalmente

                $.ajax({
                    type: 'POST',
                    url: 'upload/metadatos',
                    data: $(this).serialize(),
                    success: function(response){
                        $('#popupMessage').text(response.message);
                        $('#confirmationPopup').show();

                        if(response.message === "Se subio el documento correctamente"){
                            setTimeout(function(){
                                $('#confirmationPopup').hide();
                            }, 3000); // Ocultar el popup después de 3 segundos
                        }
                    },
                    error: function(xhr, status, error){
                        $('#popupMessage').text('Error al subir los metadatos.');
                        $('#confirmationPopup').show();
                    }
                });
            });
        });
    </script>

</body>

</html>