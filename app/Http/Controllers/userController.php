<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\InvertedIndex;
use App\Models\Document;
use App\Models\User;

class UserController extends Controller
{
    /*private $model;
    public function __CONSTRUCT() {
        $this->model = new Document();
    }*/

    function accion_listar() {
        include ('views/user/list.php');
    }

    public function logout(){
        auth()->logout();
        return redirect("/");
    }

    public function register(Request $request){

        $datos = $request->validate([
            'name' => ['required', Rule::unique('users', 'name')],
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => ['required'],
        ]);
        $datos['password']=bcrypt($datos['password']);
        $datos['rol'] = 1;
        session(['user_type' => $datos['rol']]);
        $user=User::create($datos);
        auth()->login($user);
        return redirect('/estadisticas');
    }

    public function login(Request $request){
        $datos= $request->validate([
            'login_name'=>'required',
            'login_password'=>'required'
        ]);

        if (auth()->attempt(['name' => $datos['login_name'], 'password'=>$datos['login_password']])){
            $request->session()->regenerate();
            $user = auth()->user();
            session(['user_type' => $user->rol]);
        }

        return redirect('/estadisticas');
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

    /*function savePDF($data, $pages, $arr) {
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
    }*/
}
