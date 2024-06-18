<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvertedIndex;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

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

    public function guardarDoc(Request $request){
        $request->validate([
            'pdf'=>'required|mimes:pdf|max:2048'
        ]);

        if($request->file('pdf')){
            $file=$request->file('pdf');

            // Obtener el nombre original del archivo
            $originalName = $file->getClientOriginalName();

            // Crear un nombre único para el archivo
            $uniqueName = Str::uuid() . '_' . $originalName;
            $filePath=$file->storeAs('pdfs',$uniqueName,'public');

            $absolutePath=storage_path(('app/public/'.$filePath));

            Session::put('pdf_path', $filePath);

            //Extracccion de metadatos
            $parser=new \Smalot\PdfParser\Parser();
            $parsedPDF=$parser->parseFile($absolutePath);
            $metadata=$parsedPDF->getDetails();

            
            Session::put('metadata', $metadata);

            /*Estos datos ponerlos en otra funcion cuando se busque actualizar los metadatos*/ 
            /*$pdf=new Document();
            $pdf->title= $metadata['Title'] ?? $originalName;
            $pdf->creator=$metadata['Author'] ?? 'No encontrado';
            $pdf->description=$metadata['Subject'] ?? 'No encontrado';
            $pdf->path=$filePath;
            $pdf->save();*/

            return redirect('/nuevos');

        }
        return redirect()->back()->withErrors('Error al subir el archivo.');


    }

    public function subirMetadatos(Request $request){
        $pdfPath=Session::get('pdf_path');
        if(!$pdfPath){
            return redirect()->back()->withErrors('No se encontró el archivo PDF en la sesión.');
        }
        $pdf=new Document();
        $metadata=Session::get('metadata');
        $pdf->title= $metadata['Title'] ?? 'No encontrado';
        $pdf->creator=$metadata['Author'] ?? 'No encontrado';
        $pdf->description=$metadata['Subject'] ?? 'No encontrado';
        $pdf->path=$pdfPath;
        $pdf->save();

        return redirect('/nuevos');
    }
}
