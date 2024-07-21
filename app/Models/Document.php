<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Document extends Model
{
    /*private $db;
    
    public $id;
    public $created_at;
    public $name;
    public $description;
    public $content;
    public $other_details;*/

    protected $fillable = [
        'path',
        'access_level',
        'license_condition',
        'embargo_end_date',
        'pub_date',
        'pub_version',
        'pub_id',
        'resource_id',
        'source'
    ];

    public function audiencia(){
        return $this->hasMany(DocumentAudiencia::class);
    }

    public function citacion(){
        return $this->hasMany(DocumentCitacion::class);
    }

    public function cobertura(){
        return $this->hasMany(DocumentCobertura::class);
    }

    public function colaborador(){
        return $this->hasMany(DocumentColaborador::class);
    }

    public function creator(){
        return $this->hasMany(DocumentCreator::class);
    }

    public function editor(){
        return $this->hasMany(DocumentEditor::class);
    }

    public function formato(){
        return $this->hasMany(DocumentFormato::class);
    }

    public function idioma(){
        return $this->hasMany(DocumentIdioma::class);
    }

    public function materia(){
        return $this->hasMany(DocumentMateria::class);
    }

    public function project(){
        return $this->hasMany(DocumentProject::class);
    }

    public function referenciaDatos(){
        return $this->hasMany(DocumentReferenciaDatos::class);
    }

    public function referenciaIdentificador(){
        return $this->hasMany(DocumentReferenciaIdentificador::class);
    }

    public function referenciaPublicacion(){
        return $this->hasMany(DocumentReferenciaPublicacion::class);
    }

    public function relacion(){
        return $this->hasMany(DocumentRelacion::class);
    }

    public function resultadoCientifico(){
        return $this->hasMany(DocumentResultadoCientifico::class);
    }

    public function resumen(){
        return $this->hasMany(DocumentResumen::class);
    }

    public function title(){
        return $this->hasMany(DocumentTitle::class);
    }

    /*public function __CONSTRUCT() {
		try {
            $this->db = Database::connect();
            //$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e) {
			die($e->getMessage());
		}
	}*/
    /*
    public function printTest() {
        return 'document class function print test';
    }
    
    public function listar() {
		try {

			//$stm = $this->db->prepare("SELECT * FROM documents");
			//$stm->execute();

			//return $stm->fetchAll(PDO::FETCH_OBJ);
		}
		catch(Exception $e) {
			die($e->getMessage());
		}
	}*/

    /*public function guardar(Document $data) {
        try {
        $sql = "INSERT INTO documents (name, description, content, other_details)
                VALUES (?, ?, ?, ?)";
    
        $this->db->prepare($sql)
                ->execute(
                array(
                    $data -> name,
                    $data -> description,
                    $data -> content,
                    $data -> other_details
                )
            );
            // $data['name'],
            //         $data['description'],
            //         $data['content'],
            //         $data['other_details']
            
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function setAttributes($name, $description, $content, $other_details, $id = 0) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->content = $content;
        $this->other_details = $other_details;
    }*/
}
