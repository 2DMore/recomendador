
@include ('partials.header')
    @include ('partials.navbar')
<div class="content">
    <div class="title">
        <h3>Documentos agregados</h3>
    </div>

    <div class="body">
    
        <div class="list">
            <?php foreach($this->model->listar() as $r): ?>
                <!-- <tr>
                    <td>
                        <a onclick="javascript:return confirm('¿Seguro de eliminar este registro?');" href="?c=Alumno&a=Eliminar&id=<?php echo $r->id; ?>">Eliminar</a>
                    </td> 
                </tr> -->

                <div class="listItem">
                    <div class="item">
                        <p class="name"><?= $r->name; ?></p>
                        <label class="added">Agregado: 
                            <span class="date"><?= $r->created_at;?></span>
                        </label>
                    </div>
                    <a class="btn watchItemBtn" href="./editar/<?= $r->id;?>"></a>
                    <!-- <button class="btn watchItemBtn"></button> -->
                </div>
            <?php endforeach; ?>
        </div>
        <!-- <div class="list">
            <div class="listItem">
                <div class="item">
                    <p class="name">Tesis 1</p>
                    <label class="added">Agregado: <span class="date">12/12/2020</span></label>
                </div>
                <button class="btn watchItemBtn"></button>
            </div>
        </div> -->
    </div>

</div>
@include ('partials.footer.php')
