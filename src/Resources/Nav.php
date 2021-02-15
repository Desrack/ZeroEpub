<?php

namespace Desrack\EpubGenerator\Resources;


class Nav
{
    private $templateStart = '<?xml version="1.0" encoding="utf-8"?>
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" xmlns:epub="http://www.idpf.org/2007/ops" xml:lang="en" lang="en">        
        <head>
          <title>Navigation</title>
          <meta charset="utf-8" />
        </head>
        <body>
          <nav epub:type="toc" role="doc-toc" id="toc">
            <h1 class="title">Table of Contents</h1>
            <ol>';

    private $templateEnd = '</ol></nav>
        </body>
        </html>';

    public function generate($chapters){
        $html =  $this->templateStart;
        $chapterId = 0;
        foreach ($chapters as $chapter) {
            $chapterId++;
            $html .= '<li id="' . ($chapter->id == null ? 'c'.$chapterId : $chapter->id) . '"><a href="'. $chapter->path.'">'.$chapter->title.'</a></li>';
        }
        $html .= $this->templateEnd;

        return $html;
    }
}