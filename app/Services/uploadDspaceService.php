<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Exception;

class uploadDspaceService{

    public function importDB($sesionCookie, $id){
        $dbToJson=$this->dbToJson($id);
        $totalJson = count($dbToJson);

        $itemsSubidos = 0;

        foreach ($dbToJson as $iterator) {
            $idCollection = $iterator['idCollection'];
            //$pathCarpeta = isset($iterator['pathCarpeta']) ? $iterator['pathCarpeta'] : null;
            $metadata = $iterator['metadata'];

            try {
                // Subir items y almacenar la respuesta en la variable $respuesta
                $respuesta = $this->uploadItems($metadata, $idCollection, $sesionCookie);
                
                if ($respuesta->status() == 200) {
                    /*Averiguar si se sube el pdf al dspace, en ese caso, adecuar la funcion
                    if ($pathCarpeta !== null) {
                        $this->imageUpload($pathCarpeta, $respuesta['data']['link'], $sesionCookie);
                    }*/
                    $itemsSubidos++;
                }
            } catch (Exception $error) {
                echo 'Error uploading items: ' . $error->getMessage();
            }
        }

        echo "Se subieron {$itemsSubidos} de {$totalJson}";

    }

    private function uploadItems($ejemplo, $idCollection, $sessionid){
        $x=$sessionid[0];
        $headers = [
            'Content-Type' => 'application/json',
            'Cookie' => $x
        ];
        try {
            // Separar pathCarpeta y metadata del objeto ejemplo
            $metadata = $ejemplo;

            // Hacer la solicitud POST al servidor
            $response = Http::withHeaders($headers)->post(env('URLSERVIDOR') . "/rest/collections/{$idCollection}/items", $metadata);

            return $response;
        } catch (Exception $error) {
            echo 'Error: ' . $error->getMessage();
        }
    }
    
    private function dbToJson($id){
        $objetoConJSON=['metadata' => [], 'idCollection' => null];
        //Obtener datos de la base de datos
        $document=Document::find($id);
        $documentTitle=$document->titles;
        $documentCreator=$document->creators;
        $documentResCientifico=$document->resultadosCientificos;
        $columnasOblDocument=[
            'access_level',
            'license_condition',
            'embargo_end_date',
            'pub_date',
            'pub_version',
            'pub_id',
            'resource_id',
            'source'
        ];

        //Obligatorio (Tabla DocumentTitle y DocumentCreator)
        
        foreach($documentTitle as $title){
            $objetoConJSON['metadata'][]=['key' => 'dc:title', 'value'=>$title->title];
        }

        foreach($documentCreator as $creator){
            $objetoConJSON['metadata'][]=['key' => 'dc:creator', 'value'=>$creator->creator];
        }

        //Tabla document
        foreach($columnasOblDocument as $column){
            if($column=='access_level' || $column=='license_condition'){
                $objetoConJSON['metadata'][]=['key' => 'dc:rights', 'value'=>$document->$column];
            }
            if($column=='embargo_end_date'){
                if($document->access_level=='embargoedAccess'){
                    $objetoConJSON['metadata'][]=['key' => 'dc:date', 'value'=>$document->$column];
                }
            }
            if($column=='pub_date'){
                if($document->access_level != 'embargoedAccess'){
                    $objetoConJSON['metadata'][]=['key' => 'dc:date', 'value'=>$document->$column];
                }
            }

            //Recomendados
            if($column=='pub_version' || $column=='pub_id'){
                if($document->pub_version != null){
                    $objetoConJSON['metadata'][]=['key' => 'dc:type', 'value'=>$document->$column];
                }
                if($document->pub_id != null){
                    $objetoConJSON['metadata'][]=['key' => 'dc:type', 'value'=>$document->$column];
                }
            }

            //Obligatorio
            if($column=='resource_id'){
                $objetoConJSON['metadata'][]=['key' => 'dc:identifier', 'value'=>$document->$column];
            }

            //Recomendado
            if($column=='source'){
                if($document->source != null){
                    $objetoConJSON['metadata'][]=['key' => 'dc:source', 'value'=>$document->$column];
                }
            }

        }

        //Tabla ResultadoCientifico Obligatorio
        foreach($documentResCientifico as $resCientifico){
            $objetoConJSON['metadata'][]=['key' => 'dc:type', 'value'=>$resCientifico->res_cientifico];
        }

        return $objetoConJSON;
    }


}