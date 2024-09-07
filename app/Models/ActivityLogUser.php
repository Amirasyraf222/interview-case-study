<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLogUser extends Model
{ 
    protected $fillable = [
        'user_id',
        'action'
    ];
 	
    protected $table = 'activity_log_users'; 
    protected $primaryKey = 'id'; 


    public static function log($data = [])
    { 
        $defaults = [];

        $dataFormatted = [];
        foreach ($data as $key => $value)
        {
            $dataFormatted[Str::snake($key)] = $value;
        }

        $data = array_merge($defaults, $dataFormatted);
 
        static::create($data);

        return true;
    }


}
