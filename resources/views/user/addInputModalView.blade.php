<div class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar nuevo campo</h3>
            </div>
            <div class="modal-body">
                <div class="field" style="display:flex;">
                    <select name="type" id="type">
                        <option selected hidden disabled value="0">Selecciona un campo</option>
                        <option value="title[]">Title(dc:title)</option>
                        <option value="project_id[]">Project Identifier(dc:relation)</option>
                        <option value="ref_identificador[]">Alternative Identifier (dc:relation)</option>
                        <option value="ref_publicacion[]">Publication Reference (dc:relation)</option>
                        <option value="ref_datos[]">Dataset Reference (dc:relation)</option>
                        <option value="editor[]">Publisher (dc:publisher)</option>
                        <option value="res_cientifico[]">Publication Type (dc:type)</option>
                        <option value="format[]">Format (dc:format)</option>
                        <option value="idioma[]">Language (dc:language)</option>
                        <option value="cobertura[]">Coverage (dc:coverage)</option>
                        <option value="audiencia[]">Audience (dc:audience)</option>
                        <option value="relacion[]">Relation (dc:relation)</option>
                        <option value="citacion[]">Citacion (dc:relation)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button data-btn="close-modal" class="btn secondaryBtn">Cancelar</button>
                <button data-btn="add-input-modal" class="btn successBtn">Agregar</button>
            </div>
        </div>
    </div>
</div>
<script>
    let select = document.querySelector('select#type');
    document.querySelector('button[data-btn=close-modal]').addEventListener(
        'click', closeModal
    );
    function closeModal() {
        select.value = 0;
        document.querySelector('body').classList.remove('modal-open');
    }
    document.querySelector('button[data-btn=add-input-modal]').addEventListener('click', () => {
        if(select == 0){
            //send error
            return;
        }
             
    let form = document.querySelector('.fields');
    let div = document.createElement('div');
    div.classList.add("inputItem");
    let lbl = document.createElement('label');
    lbl.innerHTML = select.options[select.selectedIndex].text;
    let inp  = document.createElement('input');
    inp.name = select.value;
    //inp.value = 'ola';
    
    div.appendChild(lbl);
    div.appendChild(inp);
    form.appendChild(div);
    closeModal();
    });

</script>
