<?php defined('ALT_PATH') OR die('No direct access allowed.');

class Alt_Exception extends Exception {

    public $code;
    public $message;

    public function __construct($message, $code = null) {
        $this->message = $message;
        $this->code = $code ?: Alt::STATUS_ERROR;
    }

    public function __toString() {
        return "$this->message [Code: $this->code]";
    }
}