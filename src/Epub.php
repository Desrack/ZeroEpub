<?php
namespace Desrack\EpubGenerator;

use Desrack\EpubGenerator\Resources\File;
use Desrack\EpubGenerator\Resources\IBooksDisplayOptions;
use Desrack\EpubGenerator\Resources\Nav;
use ZipArchive;

use Desrack\EpubGenerator\Resources\Container;
use Desrack\EpubGenerator\Resources\Package;
use Desrack\EpubGenerator\Resources\Title;
use Desrack\EpubGenerator\Resources\Identifier;
use Desrack\EpubGenerator\Resources\Chapter;

class Epub{
    
    function __construct() {
        $this->container = new Container();
        $this->package = new Package();
        $this->displayOptions = new IBooksDisplayOptions();
    }

    private $container;
    public $package;
    public $displayOptions;
    private $files = [];
    private $css;


    public function addIdentifier(Identifier $identifier){
        array_push($this->package->identifier, $identifier);
    }

    public function addChapter($title, $path, $content, $id = null)
    {
        $this->package->chapters[] = new Chapter($title, $path, $content, $id);
    }

    public function addCSSFile($path, $content, $id){
        $this->files[] = new File($path, $content);
        $this->package->addCSS($path, $id);
        $this->css .= '<link rel="stylesheet" type="text/css" href="'.$path.'" />'."\n";
    }

    public function write($file){
        $zip = new ZipArchive;
        $res = $zip->open($file, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
        if ($res === TRUE) {
            $zip->addFromString('mimetype', 'application/epub+zip');
            $zip->addFromString('META-INF/container.xml', $this->container->toString());
            $zip->addFromString('META-INF/com.apple.ibooks.display-options.xml', $this->displayOptions->generate());
            $zip->addFromString('EPUB/package.opf', $this->package->toString());
            $zip->addFromString('EPUB/nav.xhtml', $this->package->nav->generate($this->package->chapters));

            foreach ($this->package->chapters as $chapter){
                $zip->addFromString('EPUB/'.$chapter->path, $this->generateHtlm($chapter->title, $chapter->content));
            }

            foreach ($this->files as $file){
                $zip->addFromString('EPUB/'.$file->path, $file->content);
            }

            $zip->close();
        } else {
            echo 'failed: '. $res;
        }
    }
    private function generateHtlm($title, $content){
        return "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
        . "<!DOCTYPE html>\n"
        . "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
        . "<head>"
        . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
        . $this->css
        . "<title>".$title."</title>\n"
        . "</head>\n"
        . "<body>\n"
        . $content
        . "\n</body>\n</html>\n";
    }
}