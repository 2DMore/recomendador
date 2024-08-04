<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\loginDspaceService;
use App\Services\uploadDspaceService;

class dspaceController extends Controller
{

    protected $loginDspaceService;
    protected $uploadDspaceService;

    public function __construct(LoginDspaceService $loginDspaceService, UploadDspaceService $uploadDspaceService){
        $this->loginDspaceService = $loginDspaceService;
        $this->uploadDspaceService= $uploadDspaceService;
    }

    public function obtenerDocumentos(){
        $documents=Document::all();
        return view('admin.uploadDoc',compact('documents'));
    }

    public function subirMetadatosDspace(Request $request){
        //Obtener id de un boton de una vista
        $id=$request->input('id_doc');
        $sessionCookie=$this->loginDspaceService->loginDspace();
        //dd($sessionCookie);
        $message=$this->uploadDspaceService->importDB($sessionCookie, $id);
        return redirect()->back()->with('status', $message);
         
    }
}
