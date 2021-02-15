<?php
namespace Desrack\EpubGenerator\Resources;



class Chapter{

    function __construct($title, $path, $content, $id = null) {
        $this->id = $id;
        $this->content = $content;
        $this->title = $title;
        $this->path = $path;
    }

    public $id = null;
    public $content = null;
    public $title = null;
    public $path = null;
}