<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database extends Model
{
    public static function connect()
    {
        global $config;
        $data = $config['database'];
            /*$pdo = new PDO( 
                $data['connection'].';dbname='.$data['name'].';charset=utf8', 
                $data['username'], 
                $data['password'],
                $data['options']
        );*/
        //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
        return null;//$pdo;
    }
}
