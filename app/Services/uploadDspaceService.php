<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Exception;

use function Psy\debug;

class uploadDspaceService{

    public function importDB($sesionCookie, $id){
        $dbToJson=$this->dbToJson($id);
        $totalJson = count($dbToJson['metadata']);

        $itemsSubidos = 0;

        $idCollection = env('COLLECTION_ID');
        try {
            // Subir items y almacenar la respuesta en la variable $respuesta
            
            
            if ($this->uploadItems($dbToJson, $idCollection, $sesionCookie)) {
                /*Averiguar si se sube el pdf al dspace, en ese caso, adecuar la funcion
                if ($pathCarpeta !== null) {
                    $this->imageUpload($pathCarpeta, $respuesta['data']['link'], $sesionCookie);
                }*/
                $itemsSubidos++;
            }else{
                throw new Exception("Document not found in collection");
            }
        } catch (Exception $error) {
            throw new Exception('Error uploading item: ' . $error->getMessage());
        }
        
        return "Se subio el documento correctamente";

    }

    private function checkItem($metadata, $idCollection, $sessionid){
        $x=$sessionid[0];
        $pattern = "/(JSESSIONID=[^;]+);/";
        preg_match($pattern, $x, $matches);

        if (isset($matches[1])) {
            $jsessionid = $matches[1];
        }

        try{
            $client = new \GuzzleHttp\Client();

            $collectionItemsResponse = $client->get(env('URLSERVIDOR') . "/rest/collections/{$idCollection}/items", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Cookie' => $jsessionid,
                ]
            ]);

            //Extraer el item de la respuesta GET

            $collectionItems=json_decode($collectionItemsResponse->getBody(), true);
            $idItem=null;

            foreach ($collectionItems as $item) {
                if($item['name']===$metadata){
                    $idItem=$item['uuid'];
                    break;
                }
            }
            if($idItem){
                $itemResponse=$client->get(env('URLSERVIDOR') . "/rest/items/{$idItem}/metadata", [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Cookie' => $jsessionid,
                    ]
                ]);

                $itemMetadata=json_decode($itemResponse->getBody(), true);

                foreach ($itemMetadata as $meta){
                    foreach($metadata['metadata'] as $key=>$dbMeta){
                        if ($meta['key'] === $dbMeta['key'] && $meta['value'] === $dbMeta['value']) {
                            // Eliminar el elemento coincidente
                            unset($metadata['metadata'][$key]);
                        }
                    }
                }
                if (empty($metadata['metadata'])) {
                    return true;
                } else {
                    return false;
                }

            }else{
                throw new Exception("Document not found in collection");
            }
            
        }catch (Exception $error) {
            return 'Error checking item: ' . $error->getMessage();
        }
    }

    private function uploadItems($ejemplo, $idCollection, $sessionid){
        $x=$sessionid[0];
        $pattern = "/(JSESSIONID=[^;]+);/";
        preg_match($pattern, $x, $matches);

        if (isset($matches[1])) {
            $jsessionid = $matches[1];
        }
        try {
            $client = new \GuzzleHttp\Client();
            
            $metadata = json_encode($ejemplo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            
            // Hacer la solicitud POST al servidor
            $response = $client->post(env('URLSERVIDOR') . "/rest/collections/{$idCollection}/items", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Cookie' => $jsessionid,
                ],
                'body' => $metadata
            ]);
            
            //return $response;
        } catch (Exception $error) {
            //Chequeo
            if(!$this->checkItem($ejemplo, $idCollection, $sessionid)){
                throw new Exception("Document not found in collection");
            }
        }
        return true;
    }
    
    private function dbToJson($id){
        $objetoConJSON=['metadata' => []];//, 'idCollection' => env('COLLECTION_ID')];
        //Obtener datos de la base de datos
        $document=Document::find($id);
        $documentTitle=$document->title;
        $documentCreator=$document->creator;
        $documentAudiencia=$document->audiencia ?? [];
        $documentCitacion=$document->citacion ?? [];
        $documentCobertura=$document->cobertura ?? [];
        $documentColaborador=$document->colaborador ?? [];
        $documentEditor=$document->editor ?? [];
        $documentFormato=$document->formato ?? [];
        $documentIdioma=$document->idioma ?? [];
        $documentMateria=$document->materia ?? [];
        $documentProject=$document->project ?? [];
        $documentRefDatos=$document->referenciaDatos ?? [];
        $documentRefId=$document->referenciaIdentificacion ?? [];
        $documentRefPub=$document->referenciaPublicacion ?? [];
        $documentRelacion=$document->relacion ?? [];
        $documentResCientifico=$document->resultadoCientifico;
        $documentResumen=$document->resumen ?? [];
        //Fin de obtencion de datos de DB
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
            $objetoConJSON['metadata'][]=['key' => 'dc.title', 'value'=>$title->title];
        }

        foreach($documentCreator as $creator){
            $objetoConJSON['metadata'][]=['key' => 'dc.creator', 'value'=>$creator->creator];
        }
        
        //Tabla document
        foreach($columnasOblDocument as $column){
            if($column=='access_level' || $column=='license_condition'){
                $objetoConJSON['metadata'][]=['key' => 'dc.rights', 'value'=>$document->$column];
            }
            if($column=='embargo_end_date'){
                if($document->access_level=='embargoedAccess'){
                    $objetoConJSON['metadata'][]=['key' => 'dc.date', 'value'=>$document->$column];
                }
            }
            if($column=='pub_date'){
                if($document->access_level != 'embargoedAccess'){
                    $objetoConJSON['metadata'][]=['key' => 'dc.date', 'value'=>$document->$column];
                }
            }

            //Recomendados
            if($column=='pub_version' || $column=='pub_id'){
                if($document->pub_version != null){
                    $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$document->$column];
                }
                if($document->pub_id != null){
                    $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$document->$column];
                }
            }

            //Obligatorio
            if($column=='resource_id'){
                $objetoConJSON['metadata'][]=['key' => 'dc.identifier', 'value'=>$document->$column];
            }

            //Recomendado
            if($column=='source'){
                if($document->source != null){
                    $objetoConJSON['metadata'][]=['key' => 'dc.source', 'value'=>$document->$column];
                }
            }

        }

        //Tabla ResultadoCientifico Obligatorio
        foreach($documentResCientifico as $resCientifico){
            $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$resCientifico->res_cientifico];
        }

        //Tabla Document Audiencia Opcional
        if (!empty($documentAudiencia)){
            foreach($documentAudiencia as $audiencia){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$audiencia->audiencia];
            }
        }
        //Tabla DocumentCitacion Opcional
        if (!empty($documentCitacion)){
            foreach($documentCitacion as $citacion){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$citacion->citacion];
            }
        }
        //Tabla DocumentCobertura Opcional
        if (!empty($documentCobertura)){
            foreach($documentCobertura as $cobertura){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$cobertura->cobertura];
            }
        }
        //Tabla DocumentColaborador Obligatorio si aplica
        if (!empty($documentColaborador)){
            foreach($documentColaborador as $contributor){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$contributor->contributor];
            }
        }
        //Tabla Document Editor Opcional
        if (!empty($documentEditor)){
            foreach($documentEditor as $editor){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$editor->editor];
            }
        }
        //Tabla DocumentFormato Opcional
        if (!empty($documentFormato)){
            foreach($documentFormato as $formato){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$formato->format];
            }
        }
        //Tabla DocumentIdioma Opcional
        if (!empty($documentIdioma)){
            foreach($documentIdioma as $idioma){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$idioma->idioma];
            }
        }
        //Tabla Document Materia Opcional
        if (!empty($documentMateria)){
            foreach($documentMateria as $materia){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$materia->materia];
            }
        }
        //Tabla DocumentProject Opcional
        if (!empty($documentProject)){
            foreach($documentProject as $project){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$project->project_id];
            }
        }
        //Tabla DocumentReferenciaDatos Opcional
        if (!empty($documentRefDatos)){
            foreach($documentRefDatos as $refDatos){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$refDatos->ref_datos];
            }
        }
        //Tabla DocumentReferenciaIdentificador Opcional
        if (!empty($documentRefId)){
            foreach($documentRefId as $refId){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$refId->ref_identificador];
            }
        }
        //Tabla DocumentReferenciaPublicacion Opcional
        if (!empty($documentRefPub)){
            foreach($documentRefPub as $refPub){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$refPub->ref_publicacion];
            }
        }
        //Tabla DocumentRelacion Opcional
        if (!empty($documentRelacion)){
            foreach($documentRelacion as $relacion){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$relacion->relacion];
            }
        }

        //Tabla DocumentResumen Opcional
        if (!empty($documentResumen)){
            foreach($documentResumen as $resumen){
                $objetoConJSON['metadata'][]=['key' => 'dc.type', 'value'=>$resumen->resumen];
            }
        }

        return $objetoConJSON;
    }


}