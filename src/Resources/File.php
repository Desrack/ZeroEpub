<?php

namespace Desrack\EpubGenerator\Resources;


class File
{
    function __construct($path, $content) {
        $this->path = $path;
        $this->content = $content;
    }
    public $path;
    public $content;
}