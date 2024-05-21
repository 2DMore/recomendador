<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvertedIndex;
use App\Models\Document;

class userController extends Controller
{
    private $model;
    public function __CONSTRUCT() {
        $this->model = new Document();
    }
    
    function accion_listar() {
        include ('views/user/list.php');
    }
    
    function accion_guardar() {
        global $auth_first_page;
        if(isset($_POST['submit'])) {
            $nameErr = $typeErr = '';
            // echo '<!--' . var_dump($_POST['name']) . '--><br>';
            $fileMoved = $this->moveFile();
            if ( $fileMoved == 'moved'){
                header('Location: '. $auth_first_page, true, 303);
            } else {
                //lanzar error
            }
        }
    }

    function accion_capturados(){
        include ('views/user/added.php');
    }
    
    function accion_validados(){
        include ('views/user/validated.php');
    }
    
    function accion_estadisticas(){
        include ('views/user/statisticsView.php');
    }

    //enviar çon código de errores en return??
    function moveFile() {
        $pdf = $_FILES["pdf"];
        if($pdf['error']) return 'Error en el archivo';
        if($pdf['type'] != 'application/pdf') return 'No es un pdf';
    
        $new_name = time() . '.pdf';//add the user's id at the beggining
        $location = './uploads/';
        $file_store = $location . $new_name;
        $fileWasMoved = move_uploaded_file($pdf['tmp_name'], $file_store);
        
        if(!$fileWasMoved) return 'cannot moved';       
    
        $parser = new \Smalot\PdfParser\Parser();
        $pdfData = $parser->parseFile($file_store);
        
        $details = $pdfData->getDetails();
        foreach ($details as $property => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            echo $property . ' => ' . $value . "<br>";
        }
    
        $pages  = $pdfData->getPages();
        // Loop over each page to extract text.
        // foreach ($pages as $page) {
        //     echo '<p>' . $page->getText() . '</p>';
        //     echo '<br>';
        // }
        $invIndex = new InvertedIndex($file_store);
        $this->savePDF($pdf, $pages, $invIndex->getInvertedIndex());
        return 'moved';
    }

    function savePDF($data, $pages, $arr) {
        $pdfText = '';
        // var_dump($arr);
        foreach ($pages as $page) {
            $pdfText = $pdfText . $page->getText() . '\n\n';
        }
        $mostCommon = '';
        foreach (array_slice($arr, 0, 10) as $clave => $valor) {
            $mostCommon .= $clave . ', ';
        }
        $document = new Document();
        $document->setAttributes(
            $data['name'],
            $mostCommon,
            $pdfText,
            'no extras'
        );
        // $arr = [
        //     'name' => $data['name'], 
        //     'content' => $pdfText, 
        //     'description' => '...', 
        //     'other_details' => '...'
        // ];
        $this->model->guardar($document);
    }
}
