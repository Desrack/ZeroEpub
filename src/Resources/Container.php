<?php
namespace Desrack\EpubGenerator\Resources;

use SimpleXMLElement;

class Container{

    public function toString(){
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" standalone="no"?><container/>');
        $xml->addAttribute('version', '1.0');
        $xml->addAttribute('xmlns', 'urn:oasis:names:tc:opendocument:xmlns:container');
        $rootfiles = $xml->addChild('rootfiles');
        $rootfile = $rootfiles->addChild('rootfile');
        $rootfile->addAttribute('full-path', 'EPUB/package.opf');
        $rootfile->addAttribute('media-type', 'application/oebps-package+xml');

        return $xml->asXML();
    }

}