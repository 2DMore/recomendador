<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvertedIndex;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
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

            $text=$parsedPDF->getPages()[0]->getText();

            $prompt="Proporcione los metadatos de Dublin Core para el siguiente texto. La respuesta debe estar en el siguiente formato:\n
                - Title: [Título del recurso]\n
                - Creator: [Nombre del autor o creador]\n
                - Subject: [Tema o asunto del recurso]\n
                - Description: [Descripción del contenido]\n
                - Publisher: [Nombre del editor]\n
                - Contributor: [Otros contribuidores]\n
                - Date: [Fecha de creación o publicación]\n
                - Type: [Tipo de recurso]\n
                - Format: [Formato del recurso]\n
                - Identifier: [Identificador único]\n
                - Source: [Fuente original del recurso]\n
                - Language: [Idioma del recurso]\n
                - Relation: [Relaciones con otros recursos]\n
                - Coverage: [Cobertura espacial o temporal]\n
                - Rights: [Derechos de uso y acceso]: \n\n
                Texto:" . $text;

            $maxPromptTokens=1024;
            $tokens=$this->countTokens($prompt);

            if ($tokens > $maxPromptTokens) {
                // Limitar el texto si excede el número máximo de tokens
                $words = explode(' ', $text);
                $limitedText = implode(' ', array_slice($words, 0, intval($maxPromptTokens / 1.3)));
                $prompt = "Proporcione los metadatos de Dublin Core para el siguiente texto. La respuesta debe estar en el siguiente formato:\n
                - Title: [Título del recurso]\n
                - Creator: [Nombre del autor o creador]\n
                - Subject: [Tema o asunto del recurso]\n
                - Description: [Descripción del contenido]\n
                - Publisher: [Nombre del editor]\n
                - Contributor: [Otros contribuidores]\n
                - Date: [Fecha de creación o publicación]\n
                - Type: [Tipo de recurso]\n
                - Format: [Formato del recurso]\n
                - Identifier: [Identificador único]\n
                - Source: [Fuente original del recurso]\n
                - Language: [Idioma del recurso]\n
                - Relation: [Relaciones con otros recursos]\n
                - Coverage: [Cobertura espacial o temporal]\n
                - Rights: [Derechos de uso y acceso]: \n\n
                Texto:" . $limitedText;
            }

            $apiKey = env('ANTHROPIC_API_KEY');
            $url = 'https://api.anthropic.com/v1/messages';
            //Utilizar model haiku si se consumen muchos tokens
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])->post($url, [
                'model' => 'claude-3-sonnet-20240229',
                'max_tokens' => 1024,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ]
            ]);

            if ($response->successful()) {
                // Decodificar el mensaje JSON
                
                $data = json_decode($response, true);
                
                // Extraer el texto del contenido
                $text = $data['content'][0]['text'];

                // Expresión regular para extraer los metadatos
                $pattern = "/- Title:\s*(?<Title>.*?)\n\n" .
                "- Creator:\s*(?<Creator>.*?)\n\n" .
                "- Subject:\s*(?<Subject>.*?)\n\n" .
                "- Description:\s*(?<Description>.*?)\n\n" .
                "- Publisher:\s*(?<Publisher>.*?)\n\n" .
                "- Contributor:\s*(?<Contributor>.*?)\n\n" .
                "- Date:\s*(?<Date>.*?)\n\n" .
                "- Type:\s*(?<Type>.*?)\n\n" .
                "- Format:\s*(?<Format>.*?)\n\n" .
                "- Identifier:\s*(?<Identifier>.*?)\n\n" .
                "- Source:\s*(?<Source>.*?)\n\n" .
                "- Language:\s*(?<Language>.*?)\n\n" .
                "- Relation:\s*(?<Relation>.*?)\n\n" .
                "- Coverage:\s*(?<Coverage>.*?)\n\n" .
                "- Rights:\s*(?<Rights>.*?)(?:\n|$)/s";


                if (preg_match($pattern, $text, $matches)) {
                    $metadata = [
                        'Title' => $matches['Title'],
                        'Creator' => $matches['Creator'],
                        'Subject' => $matches['Subject'],
                        'Description' => $matches['Description'],
                        'Publisher' => $matches['Publisher'],
                        'Contributor' => $matches['Contributor'],
                        'Date' => $matches['Date'],
                        'Type' => $matches['Type'],
                        'Format' => $matches['Format'],
                        'Identifier' => $matches['Identifier'],
                        'Source' => $matches['Source'],
                        'Language' => $matches['Language'],
                        'Relation' => $matches['Relation'],
                        'Coverage' => $matches['Coverage'],
                        'Rights' => $matches['Rights']
                    ];
                }
                if (empty($metadata)) {
                    return response()->json(['error' => 'No metadata found'], 404);
                }

            } else {
                return response()->json([
                    'error' => 'Failed to communicate with the API',
                    'message' => $response->body()
                ], $response->status());
            }

            //dd($response);
            
            
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
        $pdf->title= $request->input('dc:title') ?? 'No encontrado';
        $pdf->creator=$request->input('dc:creator') ?? 'No encontrado';
        $pdf->access_level=$request->input('dc:rights') ?? 'No encontrado';
        $pdf->contributor=$request->input('dc:contributor') ?? 'No encontrado';
        $pdf->date=$request->input('dc:date') ?? 'No encontrado';
        $pdf->pub_type=$request->input('dc:type') ?? 'No encontrado';
        $pdf->resource_identifier=$request->input('dc:identifier') ?? 'No encontrado';
        $pdf->proj_identifier=$request->input('dc:relation') ?? 'No encontrado';
        $pdf->subject=$request->input('dc:subject') ?? 'No encontrado';
        $pdf->description=$request->input('dc:description') ?? 'No encontrado';
        $pdf->publisher=$request->input('dc:publisher') ?? 'No encontrado';
        $pdf->language=$request->input('dc:language') ?? 'No encontrado';

        $pdf->path=$pdfPath;
        $pdf->save();

        return redirect('/nuevos');
    }

    public function countTokens($text) {
        // Aproximación basada en el número de palabras
        $words = explode(' ', $text);
        $numTokens = count($words) * 1.3; // Aproximación: cada palabra es 1.3 tokens
        return ceil($numTokens);
    }
}
