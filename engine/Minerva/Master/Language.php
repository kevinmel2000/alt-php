<?php defined('ALT_PATH') or die('No direct script access.');

class Minerva_Master_Language extends Alt_Dbo {

    public function __construct(){
        parent::__construct();

        $this->pkey                 = "languageid";
        $this->table_name           = "master_language";
        $this->table_fields         = array(
            "languageid"            => "",
            "code"                  => "",
            "name"                  => "",
            "version"               => "",
            "description"           => "",
            "extension"             => "",
            "compile"               => "",
            "run"                   => "",
            "entrytime"             => 0,
            "entryuser"             => "",
            "modifiedtime"          => 0,
            "modifieduser"          => "",
            "deletedtime"           => 0,
            "deleteduser"           => "",
            "isdeleted"             => 0,
        );
    }
}