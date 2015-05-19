<?php defined('ALT_PATH') or die('No direct script access.');

class Minerva_Problem_Solution {
    public $language = '';
    public $source = '';
    public $file = '';

    public function __construct($language, $source){
        if(!isset(Minerva::$language[$language])) throw new Alt_Exception("Language not supported", 9);

        $this->language = $language;
        $this->source = $source;
    }

    public function execute(){
        $this->save();
        $this->compile();
        $this->run();
        return $this->result();
    }

    public function save(){

    }

    public function compile(){
        switch($this->language){

            default:
                break;
        }
    }

    public function run(){
        switch($this->language){

            default:
                break;
        }
    }

    public function result(){
        return array(

        );
    }
}