
    @include('partials.header')
    @include('partials.navbar')
    <div class="content">
        <div class="title">
            <h3>Agregar nuevo documento</h3>
        </div>
        <div class="body">
            <div id="drop-area">

                <form id="metaForm" method="POST" action="nuevos/store"  enctype='multipart/form-data'>
                    @csrf
                    @if(!Session::has('pdf_path'))
                        <input name="pdf" accept="application/pdf" style="display:block;" type="file">
                    @else
                        <p>Archivo PDF ya subido. Si deseas cambiarlo, sube un nuevo archivo.</p>
                        <input name="pdf" accept="application/pdf" style="display:block;" type="file">
                        <input type="hidden" name="pdf_path" value="{{ Session::get('pdf_path') }}">
                    <!--<div id="drag-pdf">
                        <input type="file"  name="drag-pdf" placeholder="drag file" hidden>
                    </div>-->
                    @endif
                    <div style="text-align: right; margin-bottom: 20px;">
                        <button type="submit" class="btn primaryBtn" value="guardar">Subir pdf</button>
                    </div>
                </form>

                <form id="metaform" method="POST" action="nuevos/submit">
                    @csrf
                    <div style="text-align: right; margin-bottom: 20px;">
                        <button type="button" class="btn successBtn">Agregar campos adicionales</button>
                    </div>
                    <!-- <input type="file" id="fileElem" multiple accept="image/*" onchange="handleFiles(this.files)"> -->
                    <!-- <label class="button" for="fileElem">Select some files</label> -->
                    <div class="fields">
                        <!--(1)Titulo OBLIGATORIO REPETIBLE -->
                        <div class="inputItem">
                            <label for="">Title (dc:title)</label>
                            <input type="text" name="title[]" value="{{session('metadata')['Title'] ?? ''}}" placeholder="Title:Subtitle" required>
                        </div>
                        <!--(2,3)Creador OBLIGATORIO REPETIBLE -->
                        <div class="creators-group">
                            <div class="inputItem creator-group">
                                <label for="">Creator (dc:creator)</label>
                                <input type="text" name="creator[]" value="{{session('metadata')['Creator'] ?? ''}}" required>
                                <label for="">Creator ID Type (dc:creator)</label>
                                <select name="id_creator_type[]">
                                    <option value="CURP">CURP</option>
                                    <option value="CVU">CVU</option>
                                    <option value="ORCID">ORCID</option>
                                    <option value="DNI">DNI</option>
                                </select>
                                <input type="text" name="id_creator[]" value="" placeholder="ID Creador (CURP, CVU, ORCID, DNI)" required>
                            </div>
                        </div>
                        
                        <button type="button" class="btn successBtn" id="add-creator">+</button>
                        <button type="button" class="btn dangerBtn" id="remove-creator">-</button>
                        <!--(5)Nivel de acceso OBLIGATORIO NO REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Access Level (dc:rights)</label>
                            <select name="access_level" id="access_level" required onchange="handleAccessLevelChange()">
                                <option value="closedAccess">Cerrado</option>
                                <option value="embargoedAccess">Embargado</option>
                                <option value="openAccess">Abierto</option>
                                <option value="restrictedAccess">Restringido</option>
                            </select>
                        </div>
                        <div class="inputItem" id="embargo_date_field" style="display:none;">
                            <label for="">Embargo Finalization Date (dc:date)</label>
                            <input type="text" name="embargo_date" id="embargo_date" value="" placeholder="AAAA-MM-DD">
                        </div>
                        <!--(6)Condiciones de licencia  OBLIGATORIO NO REPETIBLE-->
                        <div class="inputItem">
                            <label for="">License Condition (dc:rights)</label>
                            <input type="text" name="license_condition" value="{{session('metadata')['Licence'] ?? ''}}" required>
                        </div>
                       
                       <!--(16) Fecha de publicacion OBLIGATORIO NO REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Publication Date (dc:date)</label>
                            <input type="text" name="pub_date" value="{{session('metadata')['Date'] ?? ''}}" placeholder="AAAA-MM-DD" required>
                        </div>
                       <!--(17) Tipo de resultado cientifico OBLIGATORIO NO REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Publication Type (dc:type)</label>
                            <input type="text" name="pub_type" value="{{session('metadata')['Type'] ?? ''}}" required>
                        </div>
                        <!-- (21)Identificador del Recurso OBLIGATORIO NO REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Resource Identifier (dc:identifier)</label>
                            <input type="text" name="resource_id" value="{{session('metadata')['Identifier'] ?? ''}}" required>
                        </div>
                        <!--(4)Identificador del proyecto OBLIGATORIO CUANDO APLICA REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Project Identifier (dc:relation)</label>
                            <input type="text" name="project_id[]" value="{{session('metadata')['Relation'] ?? ''}}">
                        </div>
                        <!--(14,15)Colaboradores u otros autores REPETIBLE OBLIGATORIO SI APLICA (Traductores, asesores, revisores, ilustradores)-->
                        <div class="contributors-group">
                            <div class="inputItem contributor-group">
                                <label for="">Contributor (dc:contributor)</label>
                                <input type="text" name="contributor[]" value="{{session('metadata')['Contributor'] ?? ''}}">
                                <label for="">Contributor ID Type (dc:contributor)</label>
                                <select name="id_contributor_type[]">
                                    <option value="CURP">CURP</option>
                                    <option value="CVU">CVU</option>
                                    <option value="ORCID">ORCID</option>
                                    <option value="DNI">DNI</option>
                                    <option value="NO">No aplica</option>
                                </select>
                                <input type="text" name="id_contributor[]" value="" placeholder="ID Creador (CURP, CVU, ORCID, DNI)">
                            </div>
                        </div>
                        
                        <button type="button" class="btn successBtn" id="add-contributor">+</button>
                        <button type="button" class="btn dangerBtn" id="remove-contributor">-</button>
                        <!-- (13)Editor OBLIGATORIO CUANDO APLICA REPETIBLE -->
                        <div class="inputItem">
                            <label for="">Publisher (dc:publisher)</label>
                            <input type="text" name="publisher[]" value="{{session('metadata')['Publisher'] ?? ''}}">
                        </div>
                       <!-- (18)Version de la publicacion NO REPETIBLE-->
                        <div class="inputItem">
                        <label for="">Publication Version (dc:type)</label>
                        <select name="pub_version">
                            <option value="draft">Borrador</option>
                            <option value="submittedVersion">Subido</option>
                            <option value="acceptedVersion">Aceptado</option>
                            <option value="publishedVersion">Publicado</option>
                            <option value="updatedVersion">Actualizado</option>
                        </select>
                        </div>
                        <!--(19)Identificador de Publicacion NO REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Publication Identifier (dc:type)</label>
                            <input type="text" name="pub_id" value="">
                        </div>
                        <!--(10)Referencia del conjuento de datos OBLIGATORIO CUANDO APLICA REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Dataset Reference (dc:relation)</label>
                            <input type="text" name="dataset_ref[]"value="">
                        </div>
                        <!--(11)Materia OPCIONAL REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Subject (dc:subject)</label>
                            <input type="text" name="materia[]" value="{{session('metadata')['Subject'] ?? ''}}">
                        </div>
                        
                        <!--(12)Descripcion o resumen OBLIGATORIO CUANDO APLICA REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Description (dc:description)</label>
                            <input type="text" name="resumen[]" value="{{session('metadata')['Description'] ?? ''}}">
                        </div>
                        <!--(22)Fuente NO REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Source (dc:source)</label>
                            <input type="text" name="source" value="">
                        </div>
                        <!--(23)Idioma OBLIGATORIO CUANDO APLICA REPETIBLE-->
                        <div class="inputItem">
                            <label for="">Language (dc:language)</label>
                            <input type="text" name="language[]" value="{{session('metadata')['Language'] ?? ''}}">
                            <!-- este podría ser un select?-->
                        </div>

                    </div>
                    
                    <button class="btn successBtn" type="submit" name="submit">Aceptar</button>

                </form>
            </div>
        </div>

    @include ('user.addInputModalView')
    </div>
    <script>
    
    document.querySelector('button[type=button]').addEventListener(
        'click', () => {
            document.querySelector('body').classList.toggle('modal-open');
        }
    );

    function handleAccessLevelChange() {
        var accessLevel = document.getElementById('access_level').value;
        var embargoDateField = document.getElementById('embargo_date_field');
        if (accessLevel === 'embargoedAccess') {
            embargoDateField.style.display = 'block';
            document.getElementById('embargo_date').required = true;
        } else {
            embargoDateField.style.display = 'none';
            document.getElementById('embargo_date').required = false;
        }
    }

    document.getElementById('add-creator').addEventListener('click', function () {
        var creatorGroup = document.querySelector('.creator-group').cloneNode(true);
        creatorGroup.querySelectorAll('input').forEach(input => input.value = '');
        document.querySelector('.creators-group').appendChild(creatorGroup);
    });

    document.getElementById('remove-creator').addEventListener('click', function () {
        var creatorGroups = document.querySelectorAll('.creator-group');
        if (creatorGroups.length > 1) {
            creatorGroups[creatorGroups.length - 1].remove();
        } else {
            alert('Debe haber al menos un conjunto de creador y creador ID.');
        }
    });

    document.getElementById('add-contributor').addEventListener('click', function () {
        var contributorGroup = document.querySelector('.contributor-group').cloneNode(true);
        contributorGroup.querySelectorAll('input').forEach(input => input.value = '');
        document.querySelector('.contributors-group').appendChild(contributorGroup);
    });

    document.getElementById('remove-contributor').addEventListener('click', function () {
        var contributorGroups = document.querySelectorAll('.contributor-group');
        if (contributorGroups.length > 1) {
            contributorGroups[contributorGroups.length - 1].remove();
        } else {
            alert('Debe haber al menos un conjunto de contributor y contributor ID.');
        }
    });

    

    </script>
@include ('partials.footer')