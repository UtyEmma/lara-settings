<?php

namespace Utyemma\LaraSetting;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Collection;
use Utyemma\LaraSetting\Models\Setting;

abstract class Settings implements Arrayable, Jsonable{

    protected Setting $model;

    protected $options = [];
    protected $labels = [];
    
    protected $casts = [];

    protected $attributes = [];

    private Collection | null $items = null;

    function __construct(){
        $this->model = new Setting;
        $this->attributes = $this->attributes();
        $this->labels = $this->labels();
        $this->load(); 
    }

    function __isset($name){
        return property_exists($this, $name) || in_array($name, $this->options);
    }

    public function __get($name){
        if(!$this->items) $this->load();
        return $this->items->firstWhere('key', $name);    
    }

    function __set($name, $value) {
        $this->save($name, $value);
    }

    function attributes(){
        return $this->attributes;
    }

    function labels(){
        return $this->labels;
    }

    function load() {
        $this->items = $this->get();
    }

    private function get(){
        return $this->model::where('group', $this::class)->get();
    }

    public function first($key): mixed{
        return $this->model->withCasts($this->casts($key))->whereGroup($this::class)->where('key', $key)->first();
    }

    function casts($key){
        return isset($this->casts[$key]) ? ['value' => $this->casts[$key]] : [];
    }

    private function save($key, $value){
        $data = [
            'key' => $key,
            'group' => $this::class,
        ];
        
        $setting = $this->model::where($data)->first( ) ?? new Setting($data);

        if(count($this->casts)) {
            $setting->withCasts($this->casts($key));
        }

        $setting->label = array_key_exists($key, $this->labels) ? $this->labels[$key] : null;
        $setting->value = $value;
        $setting->save();
    }

    function toArray(): array {
        return $this->get()->pluck('key', 'value')->toArray();
    }

    function toJson($options = 0){
        return $this->get(); 
    }

    function seed(): void {
        foreach($this->options as $value) {
            $item = null;
            if(isset($this->attributes[$value])) $item = $this->attributes[$value];
            $this->save($value, $item);
        }
    }

}