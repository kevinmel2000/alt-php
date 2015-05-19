<?php defined('ALT_PATH') or die('No direct script access.');

class Minerva_Master_Unit extends Alt_Dbo {

    public function __construct(){
        parent::__construct();

        $this->pkey                 = "unitid";
        $this->table_name           = "master_unit";
        $this->table_fields         = array(
            "unitid"                => "",
            "code"                  => "",
            "name"                  => "",
            "netsize"               => "",
            "semigrosssize"         => "",
            "description"           => "",
            "ownership"             => "",
            "isrentable"            => 0,
            "ismice"                => 0,
            "status"                => "AVL",
            "unittypeid"            => 0,
            "floorid"               => 0,
            "entrytime"             => 0,
            "entryuser"             => "",
            "modifiedtime"          => 0,
            "modifieduser"          => "",
            "deletedtime"           => 0,
            "deleteduser"           => "",
            "isdeleted"             => 0,
        );

        $this->view_name            = "view_master_unit";
        $this->view_fields          = array_merge($this->table_fields, array(
            "abc"                   => "",
        ));
    }
}