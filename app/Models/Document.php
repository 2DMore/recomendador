<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;

class Document extends Model
{
    private $db;
    
    public $id;
    public $created_at;
    public $name;
    public $description;
    public $content;
    public $other_details;

    protected $fillable = [
        'title',
        'path',
        'creator',
        'access_level',
        'license_condition',
        'contributor',
        'pub_date',
        'pub_type',
        'resource_identifier',
        'proj_identifier',
        'date',
        'dataset_ref',
        'subject',
        'description',
        'publisher',
        'language',
    ];

    /*public function __CONSTRUCT() {
		try {
            $this->db = Database::connect();
            //$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e) {
			die($e->getMessage());
		}
	}*/
    
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
	}

    public function guardar(Document $data) {
        try {
        $sql = "INSERT INTO documents (name, description, content, other_details)
                VALUES (?, ?, ?, ?)";
    
        /*$this->db->prepare($sql)
                ->execute(
                array(
                    $data -> name,
                    $data -> description,
                    $data -> content,
                    $data -> other_details
                )
            );*/
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
    }
}
