<?php

/**
 * Object for a response.
 *
 * Example response:
 *  "code"       => "failed_database",
 *  "message"    => "Unable to connect to the database."
 *  "type"       => "ERROR"
 */
class Response
{
    public $code;
    public $message;
    public $type;
    public $contents;
    
    public function __construct($__code, $__message, $__type)
    {
        $this->code = $__code;
        $this->message = $__message;
        $this->type = $__type;
        
        $this->contents = [];
    }
}

?>