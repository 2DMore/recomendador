<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvertedIndex;
use App\Models\Document;
use App\Models\DocumentAudiencia;
use App\Models\DocumentCitacion;
use App\Models\DocumentCobertura;
use App\Models\DocumentColaborador;
use App\Models\DocumentCreator;
use App\Models\DocumentEditor;
use App\Models\DocumentFormato;
use App\Models\DocumentIdioma;
use App\Models\DocumentMateria;
use App\Models\DocumentProject;
use App\Models\DocumentReferenciaDatos;
use App\Models\DocumentReferenciaIdentificador;
use App\Models\DocumentReferenciaPublicacion;
use App\Models\DocumentRelacion;
use App\Models\DocumentResultadoCientifico;
use App\Models\DocumentResumen;
use App\Models\DocumentTitle;
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
                if (empty($metadata)) { //En caso de quedarnos sin creditos
                    return response()->json(['error' => 'No metadata found'], 404);
                }

            } else {
                /*Conservar esto en caso de que se necesite saber que error hay al usar la API
                return response()->json([
                    'error' => 'Failed to communicate with the API',
                    'message' => $response->body()
                ], $response->status());
                */
                foreach ($metadata as $key => $value) {
                    if (!empty($value)) {

                        Session::put('metadata.' . $key, $value);
                    }
                }
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
        $document=new Document();
        $document->access_level= $request->input('access_level') ?? 'No encontrado';
        $document->license_condition=$request->input('license_condition') ?? 'No encontrado';
        $document->pub_date=$request->input('pub_date') ?? 'No encontrado';
        $document->pub_version=$request->input('pub_version') ?? 'No encontrado';
        $document->pub_id=$request->input('pub_id') ?? 'No encontrado';
        $document->resource_id=$request->input('resource_id') ?? 'No encontrado';
        $document->source=$request->input('source') ?? 'No encontrado';
        $document->embargo_end_date=$request->input('embargo_date') ?? 'No encontrado';

        $document->path=$pdfPath;


        $document->save();

        if (!$document->id) {
            return response()->json([
                'error' => 'No se encontro ID',
                'message' => 'NO SE ENCONTRO ID'
            ]);
        }

        // Guardar datos en tabla DocumentAudiencia
        if ($request->has('audiencia')) {
            foreach ($request->input('audiencia') as $audiencia) {
                $documentAudiencia = new DocumentAudiencia();
                $documentAudiencia->document_id = $document->id;
                $documentAudiencia->audiencia = $audiencia;
                $documentAudiencia->save();
            }
        }

        // Guardar datos en tabla DocumentCitacion
        if ($request->has('citacion')) {
            foreach ($request->input('citacion') as $citacion) {
                $documentCitacion = new DocumentCitacion();
                $documentCitacion->document_id = $document->id;
                $documentCitacion->citacion = $citacion;
                $documentCitacion->save();
            }
        }

        // Guardar datos en tabla DocumentCobertura
        if ($request->has('cobertura')) {
            foreach ($request->input('cobertura') as $cobertura) {
                $documentCobertura = new DocumentCobertura();
                $documentCobertura->document_id = $document->id;
                $documentCobertura->cobertura = $cobertura;
                $documentCobertura->save();
            }
        }

        // Guardar datos en tabla DocumentColaborador
        if ($request->has('contributor') && $request->has('id_contributor_type') && $request->has('id_contributor')) {
            $contributors = $request->input('contributor');
            $idContributorTypes = $request->input('id_contributor_type');
            $idContributors = $request->input('id_contributor');
    
            foreach ($contributors as $index => $contributor) {
                $documentColaborador = new DocumentColaborador();
                $documentColaborador->document_id = $document->id;
                $documentColaborador->contributor = $contributor;
                $documentColaborador->contributor_id = $idContributors[$index];
                $documentColaborador->contributor_id_type = $idContributorTypes[$index];
                $documentColaborador->save();
            }
        }

        // Guardar datos en tabla DocumentCreator
        if ($request->has('creator') && $request->has('id_creator_type') && $request->has('id_creator')) {
            $creators = $request->input('creator');
            $idCreatorTypes = $request->input('id_creator_type');
            $idCreators = $request->input('id_creator');
    
            foreach ($creators as $index => $creator) {
                $documentCreator = new DocumentCreator();
                $documentCreator->document_id = $document->id;
                $documentCreator->creator = $creator;
                $documentCreator->creator_id = $idCreators[$index];
                $documentCreator->creator_id_type = $idCreatorTypes[$index];
                $documentCreator->save();
            }
        }

        // Guardar datos en tabla DocumentEditor
        if ($request->has('editor')) {
            foreach ($request->input('editor') as $editor) {
                $documentEditor = new DocumentEditor();
                $documentEditor->document_id = $document->id;
                $documentEditor->editor = $editor;
                $documentEditor->save();
            }
        }

        // Guardar datos en tabla DocumentFormat
        if ($request->has('format')) {
            foreach ($request->input('format') as $format) {
                $documentFormat = new DocumentFormato();
                $documentFormat->document_id = $document->id;
                $documentFormat->format = $format;
                $documentFormat->save();
            }
        }

        // Guardar datos en tabla DocumentIdioma
        if ($request->has('idioma')) {
            foreach ($request->input('idioma') as $idioma) {
                $documentIdioma = new DocumentIdioma();
                $documentIdioma->document_id = $document->id;
                $documentIdioma->idioma = $idioma;
                $documentIdioma->save();
            }
        }

        // Guardar datos en tabla DocumentMateria
        if ($request->has('materia')) {
            foreach ($request->input('materia') as $materia) {
                $documentMateria = new DocumentMateria();
                $documentMateria->document_id = $document->id;
                $documentMateria->materia = $materia;
                $documentMateria->save();
            }
        }

        // Guardar datos en tabla DocumentProject
        if ($request->has('project_id')) {
            foreach ($request->input('project_id') as $project_id) {
                $documentProject = new DocumentProject();
                $documentProject->document_id = $document->id;
                $documentProject->project_id = $project_id;
                $documentProject->save();
            }
        }

        // Guardar datos en tabla DocumentReferenciaDatos
        if ($request->has('ref_datos')) {
            foreach ($request->input('ref_datos') as $ref_datos) {
                $documentRefDatos = new DocumentReferenciaDatos();
                $documentRefDatos->document_id = $document->id;
                $documentRefDatos->ref_datos = $ref_datos;
                $documentRefDatos->save();
            }
        }

        // Guardar datos en tabla DocumentReferenciaIdentificador
        if ($request->has('ref_identificador')) {
            foreach ($request->input('ref_identificador') as $ref_identificador) {
                $documentRefIdentificador = new DocumentReferenciaIdentificador();
                $documentRefIdentificador->document_id = $document->id;
                $documentRefIdentificador->ref_identificador = $ref_identificador;
                $documentRefIdentificador->save();
            }
        }

        // Guardar datos en tabla DocumentReferenciaPublicacion
        if ($request->has('ref_publicacion')) {
            foreach ($request->input('ref_publicacion') as $ref_publicacion) {
                $documentRefPublicacion = new DocumentReferenciaPublicacion();
                $documentRefPublicacion->document_id = $document->id;
                $documentRefPublicacion->ref_publicacion = $ref_publicacion;
                $documentRefPublicacion->save();
            }
        }

        // Guardar datos en tabla DocumentRelacion
        if ($request->has('relacion')) {
            foreach ($request->input('relacion') as $relacion) {
                $documentRelacion = new DocumentRelacion();
                $documentRelacion->document_id = $document->id;
                $documentRelacion->relacion = $relacion;
                $documentRelacion->save();
            }
        }

        // Guardar datos en tabla DocumentResultadoCientifico
        if ($request->has('res_cientifico')) {
            foreach ($request->input('res_cientifico') as $res_cientifico) {
                $documentResCientifico = new DocumentResultadoCientifico();
                $documentResCientifico->document_id = $document->id;
                $documentResCientifico->res_cientifico = $res_cientifico;
                $documentResCientifico->save();
            }
        }

        // Guardar datos en tabla DocumentResumen
        if ($request->has('resumen')) {
            foreach ($request->input('resumen') as $resumen) {
                $documentResumen = new DocumentResumen();
                $documentResumen->document_id = $document->id;
                $documentResumen->resumen = $resumen;
                $documentResumen->save();
            }
        }

        // Guardar datos en tabla DocumentTitle
        if ($request->has('title')) {
            foreach ($request->input('title') as $title) {
                $documentTitle = new DocumentTitle();
                $documentTitle->document_id = $document->id;
                $documentTitle->title = $title;
                $documentTitle->save();
            }
        }


        return redirect('/nuevos');
    }

    public function countTokens($text) {
        // Aproximación basada en el número de palabras
        $words = explode(' ', $text);
        $numTokens = count($words) * 1.3; // Aproximación: cada palabra es 1.3 tokens
        return ceil($numTokens);
    }
}
