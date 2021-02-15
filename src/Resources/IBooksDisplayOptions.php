<?php

namespace Desrack\EpubGenerator\Resources;


class IBooksDisplayOptions
{
    public function generate(){
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<display_options>\n    <platform name=\"*\">\n        <option name=\"fixed-layout\">true</option>\n        <option name=\"interactive\">true</option>\n        <option name=\"specified-fonts\">true</option>\n    </platform>\n</display_options>";
    }
}