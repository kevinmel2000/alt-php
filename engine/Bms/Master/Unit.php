<?php defined('ALT_PATH') or die('No direct script access.');

class Bms_Master_Unit extends Alt_Dbo {
    public $pkey                = "unitid";

    public $table_name          = "master_unit";
    public $table_fields        = array(
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
        "isdeleted"             => 0
    );
}