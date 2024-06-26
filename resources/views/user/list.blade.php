
    @include('partials.header')
    @include('partials.navbar')
    <div class="content">
        <div class="title">
            <h3>Agregar nuevo documento</h3>
        </div>
        <div class="body">
            <div id="drop-area">
                <form method="post" action="./guardar"  enctype='multipart/form-data'>
                    <input name="pdf" accept="application/pdf" style="display:block;" type="file">
                    <div id="drag-pdf">
                        <!-- <input type="file"  name="drag-pdf" placeholder="drag file" hidden> -->
                    </div>
                    <div style="text-align: right; margin-bottom: 20px;">
                        <button type="button" class="btn successBtn">Agregar campos adicionales</button>
                    </div>
                    <!-- <input type="file" id="fileElem" multiple accept="image/*" onchange="handleFiles(this.files)"> -->
                    <!-- <label class="button" for="fileElem">Select some files</label> -->
                    <div class="fields">
                        <div class="inputItem">
                            <label for="">Title (dc:title)</label>
                            <input type="text" name="dc:title">
                        </div>
                        <div class="inputItem">
                            <label for="">Creator (dc:creator)</label>
                            <input type="text" name="dc:creator">
                        </div>
                        <div class="inputItem">
                            <label for="">Access Level (dc:rights)</label>
                            <input type="text" name="dc:rights">
                        </div>
                        <div class="inputItem">
                            <label for="">License Condition (dc:rights)</label>
                            <input type="text" name="dc:rights">
                        </div>
                       
                        <div class="inputItem">
                            <label for="">Contributor (dc:contributor)</label>
                            <input type="text" name="dc:contributor">
                        </div>
                       
                        <div class="inputItem">
                            <label for="">Publication Date (dc:date)</label>
                            <input type="text" name="dc:date">
                        </div>
                       
                        <div class="inputItem">
                            <label for="">Publication Type (dc:type)</label>
                            <input type="text" name="dc:type">
                        </div>
                       
                        <div class="inputItem">
                            <label for="">Resource Identifier (dc:identifier)</label>
                            <input type="text" name="dc:identifier">
                        </div>
                       
                        <div class="inputItem">
                            <label for="">Project Identifier (dc:relation)</label>
                            <input type="text" name="dc:relation">
                        </div>
                        <div class="inputItem">
                            <label for="">Date (dc:date)</label>
                            <input type="text" name="dc:date">
                        </div>
                        <div class="inputItem">
                            <label for="">Dataset Reference (dc:relation)</label>
                            <input type="text" name="dc:relation">
                        </div>
                        <div class="inputItem">
                            <label for="">Subject (dc:subject)</label>
                            <input type="text" name="dc:subject">
                        </div>
                        <div class="inputItem">
                            <label for="">Description (dc:description)</label>
                            <input type="text" name="dc:description">
                        </div>
                        <div class="inputItem">
                            <label for="">Publisher (dc:publisher)</label>
                            <input type="text" name="dc:publisher">
                        </div>
                        <div class="inputItem">
                            <label for="">Language (dc:language)</label>
                            <input type="text" name="dc:language">
                            <!-- este podría ser un select?-->
                        </div>

                        <!-- <div class="newInput">
                            <div class="inputItem">
                                <label for="">dc. creator1</label>
                                <input type="text" name="" id="">
                            </div>
                            <button class="btn dangerBtn iconDeleteBtn">
                        </div> -->
                       
                        <!-- <div class="newInput">
                            <div class="inputItem">
                                <label for="">Creator (dc. creator)</label>
                                <input type="text" name="" id="">
                            </div>
                            <button class="btn dangerBtn iconDeleteBtn"></button>

                            <div class="inputItem">
                                <label for="">dc. creator. id</label>
                                <input type="text" name="" id="">
                            </div>
                        </div>
                        <div class="newInput">
                            <div class="inputItem">
                                <label for="">dc. creator1</label>
                                <input type="text" name="" id="">
                            </div>
                            <button class="btn dangerBtn iconDeleteBtn">
                        </div> -->
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
    </script>
@include ('partials.footer')