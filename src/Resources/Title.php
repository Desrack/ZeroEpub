<?php
namespace Desrack\EpubGenerator\Resources;

class Title{

    function __construct($value, $id = null, $dir = null, $lang = null) {
        $this->id = $id;
        $this->value = $value;
        $this->dir = $dir;
        $this->lang = $lang;
    }

    public $id = null;
    public $value = null;
    public $dir = null;
    public $lang = null;


    public function addToXml($xml){
        $node = $xml->addChild('dc:title', $this->value, 'http://purl.org/dc/elements/1.1/');
        if($this->id !== null)
            $node->addAttribute("id", $this->id);
        if($this->dir !== null)
            $node->addAttribute("id", $this->dir);
        if($this->lang !== null)
            $node->addAttribute("xml:lang", $this->lang, 'xml');
    }
}