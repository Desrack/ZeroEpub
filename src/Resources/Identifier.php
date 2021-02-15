<?php
namespace Desrack\EpubGenerator\Resources;

use SimpleXMLElement;

class Identifier{

    function __construct($value, $id = null, $default = true) {
        $this->id = $id;
        $this->value = $value;
        $this->default = $default;
    }

    public $id;
    public $value;
    public $default;

    public function addToXml($xml){
        $id = $xml->addChild('dc:identifier', $this->value, 'http://purl.org/dc/elements/1.1/');
        if($this->id !== null)
            $id->addAttribute("id", $this->id);
    }
}