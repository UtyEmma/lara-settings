<?php

namespace Utyemma\LaraSetting\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model {

    protected $fillable = ['label', 'value', 'group', 'key'];

    function __tostring(){
        return $this->value;
    }

}
