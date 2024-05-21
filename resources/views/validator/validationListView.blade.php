
@include ('partials.header');
@include ('partials.navbar');
<div class="content">

    <div class="title">
        <h3>Validación de documentos</h3>
    </div>

    <div class="body">
        <div class="validationActions">
            <a class="documentUrl" href="#">ver <span>Tesis 1</span></a>
            <div>
                <button class="btn dangerBtn">Rechazar</button>
                <button class="btn successBtn">Validar</button>
            </div>
        </div>
        <div class="inputItem">
            <label for="">dc. coverage. spatial</label>
            <div class="inputField">
                <input type="text" name="" id="">
                <button class="btn secondaryBtn commentIcon">
                    <span class="">Comentar</span>
                </button>
            </div>
        </div>
    </div>
</div>
@include('partials.footer')