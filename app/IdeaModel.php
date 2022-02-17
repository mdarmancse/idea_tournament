<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdeaModel extends Model
{
    protected $table='idea_models';
    public function user(){
        return $this->hasMany(AdminModel::class,'id','user_id');
    }
}
