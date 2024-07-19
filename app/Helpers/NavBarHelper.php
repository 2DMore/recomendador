<?php
function get_nav_elements($option) {
	$index = $option - 1;
	$navbar_options = [
		0=>[//Administrador
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
		1=>[//Usuario
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
		2=>[//Validadores
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
	return $navbar_options[$index]; //
}

?>
