<?php

namespace Utyemma\LaraSetting;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Utyemma\LaraSetting\Models\Setting;

abstract class Settings implements Arrayable, Jsonable{

    protected Setting $model;

    protected $options = [];
    protected $labels = [];
    
    protected $casts = [];

    protected $attributes = [];

    protected $strict = true;

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
        return $this->first($name);
    }

    function __set($name, $value) {
        $this->save($name, $value);
    }

    function attributes(){
        return $this->attributes;
    }

    function fetchFromCache($name){
        if(!$this->items) $this->load();
        return $this->items->firstWhere('key', $name); 
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

    public function first($key): mixed {
        return $this->items->firstWhere('key', $key)?->mergeCasts($this->casts($key));
    }

    function casts($key){
        return isset($this->casts[$key]) ? ['value' => $this->casts[$key]] : [];
    }

    function queryAttribute($key){
        return [
            'key' => $key,
            'group' => $this::class,
        ];
    }

    private function save($key, $value){
        if($this->strict) $this->validateOptions([$key]);

        $data = $this->queryAttribute($key);
        $setting = $this->model->where($data)->first() ?? new Setting($data, $this->casts);

        if(!empty($this->casts)) {
            $setting->withCasts($this->casts($key));
        }

        $setting->label = array_key_exists($key, $this->labels) ? $this->labels[$key] : null;
        $setting->value = $value;
        $setting->save();
        // Refresh the model items
        $this->load();
    }

    function validateOptions($options){
        if($unique = collect($options)->diff($this->options)->implode(',')) {
            throw new \Exception("Setting options '{$unique}' are not defined in the options array. Add them or set 'strict' property to false to dynamically set undefined options.");
        }
    }

    function update($options = []) {
        if($this->strict) $this->validateOptions($options);

        collect($options)->each(function($value, $key) {
            if(isset($this->options[$key])) $this->save($key, $value);
        });

        return true; 
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