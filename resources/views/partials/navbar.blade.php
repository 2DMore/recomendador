        <!-- <input type="checkbox" name="sidebar" id="sidebar"> -->
        <!DOCTYPE html>
        <div class="sideContainer">
    <nav class="sidebar active">
        <a class="logo" href="">
            <img height="100px" src="images/UADY_logo.svg" alt="logo">
        </a>
        <!-- <label for="sidebar">press</label> -->
        <button class="actionSidebar"><img src="images/right-arrow.png" alt=""></button>
        <!-- <img style="float:right;" src="../../Desktop/STK-20200827-WA0046.webp" alt="" srcset="" height="100%"> -->

        <div class="options-nav">
        <!--Opciones de navegacion borradas agregarlas despues -->
        <?php foreach (get_nav_elements() as $element): ?>
                <a class="option" href="<?php echo $element['link']; ?>">
                    <img class="optionIcon" src="<?php echo $element['img_source']; ?>" alt="">
                    <label><?php echo $element['text']; ?></label>
                </a>
        <?php endforeach; ?>
       <br>
        <a style="border-top: 2px solid" class="option" href="./logout">
                    <img class="optionIcon" src="images/graph.svg" alt="">
                    <label>Cerrar sesión</label>
                </a>
            
        </div>
    </nav>
</div>
<?php
function get_nav_elements() {
	//global $option;
	//$index = $option - 1;
	$navbar_options = [
		0=>[
			[
				'link'=>'./listar',
				'img_source'=>'images/document.svg',
				'text' => 'Listado de documentos',
			],
			[
				'link'=>'./usuarios',
				'img_source'=>'images/users.svg',
				'text' => 'Gestionar usuarios',
			],
			[
				'link'=>'./agregar-usuarios',
				'img_source'=>'images/user.svg',
				'text' => 'Agregar usuarios',
			],
			[
				'link'=>'./estadisticas',
				'img_source'=>'images/graph.svg',
				'text' => 'Estadísticas',
			]
		],
		1=>[
			[
				'link'=>'./nuevos',
				'img_source'=>'images/edit.svg',
				'text' => 'Documentos nuevos',
			],
			[
				'link'=>'./validados',
				'img_source'=>'images/files.svg',
				'text' => 'Documentos validados',
			],
			[
				'link'=>'./estadisticas',
				'img_source'=>'images/graph.svg',
				'text' => 'Estadísticas',
			]
		],
		2=>[
			[
				'link'=>'./newlist',
				'img_source'=>'images/edit.svg',
				'text' => 'Documentos registrados',
			],
			[
				'link'=>'./capturados',
				'img_source'=>'images/files.svg',
				'text' => 'Documentos capturados',
			],

			[
				'link'=>'./validados',
				'img_source'=>'images/valid.svg',
				'text' => 'Validados',
			],
			[
				'link'=>'./estadisticas',
				'img_source'=>'images/graph.svg',
				'text' => 'Estadísticas',
			]
		],
	];
	return $navbar_options[0];//[$index];
}

?>
