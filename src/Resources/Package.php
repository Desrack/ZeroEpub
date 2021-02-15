<?php
namespace Desrack\EpubGenerator\Resources;

use SimpleXMLElement;

/*
<?xml version="1.0" encoding="utf-8" standalone="no"?>
<package xmlns="http://www.idpf.org/2007/opf" xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:dcterms="http://purl.org/dc/terms/" version="3.0" xml:lang="en"
	unique-identifier="pub-identifier">
	<metadata>
		<dc:identifier id="pub-identifier">urn:isbn:9781449328030</dc:identifier>
		<dc:title id="pub-title">Accessible EPUB 3</dc:title>
		<dc:language id="pub-language">en</dc:language>
		<dc:date>2012-02-20</dc:date>
		<meta property="dcterms:modified">2012-10-24T15:30:00Z</meta>
		<dc:creator id="pub-creator12">Matt Garrish</dc:creator>
		<dc:contributor>O’Reilly Production Services</dc:contributor>
		<dc:contributor>David Futato</dc:contributor>
		<dc:contributor>Robert Romano</dc:contributor>
		<dc:contributor>Brian Sawyer</dc:contributor>
		<dc:contributor>Dan Fauxsmith</dc:contributor>
		<dc:contributor>Karen Montgomery</dc:contributor>
		<dc:publisher>O’Reilly Media, Inc.</dc:publisher>
		<dc:rights>Copyright © 2012 O’Reilly Media, Inc</dc:rights>
		<meta property="schema:accessMode">textual</meta>
		<meta property="schema:accessMode">visual</meta>
		<meta property="schema:accessModeSufficient">textual,visual</meta>
		<meta property="schema:accessModeSufficient">textual</meta>
		<meta property="schema:accessibilityHazard">none</meta>
		<meta property="schema:accessibilityFeature">tableOfContents</meta>
		<meta property="schema:accessibilityFeature">readingOrder</meta>
		<meta property="schema:accessibilityFeature">alternativeText</meta>
		<meta property="schema:accessibilitySummary">This EPUB Publication meets the requirements of the EPUB Accessibility specification with conformance to WCAG 2.0 Level AA. The publication is screen reader friendly.</meta>
		<link rel="dcterms:conformsTo" href="http://www.idpf.org/epub/a11y/accessibility-20170105.html#wcag-aa"/>
		<meta property="a11y:certifiedBy">Matt Garrish</meta>
	</metadata>
	<manifest>
		<item id="htmltoc" properties="nav" media-type="application/xhtml+xml" href="bk01-toc.xhtml"/>
		<item media-type="text/css" id="epub-css" href="css/epub.css"/>
		<item media-type="text/css" id="epub-tss-css" href="css/synth.css"/>
		<item id="cover" href="cover.xhtml" media-type="application/xhtml+xml"/>
		<item id="cover-image" properties="cover-image" href="covers/9781449328030_lrg.jpg" media-type="image/jpeg"/>
		<item id="id-id2442754" href="index.xhtml" media-type="application/xhtml+xml"/>
	</manifest>
	<spine>
		<itemref idref="cover" linear="no"/>
		<itemref idref="spi_ad"/>
		<itemref idref="id-id2442754"/>
		<itemref idref="htmltoc" linear="yes"/>
		<itemref idref="id-id2632344"/>
	</spine>
</package>
*/

class Package{
    public function __construct()
    {
        $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" standalone="no"?><package xmlns="http://www.idpf.org/2007/opf" xmlns:dcterms="http://purl.org/dc/terms/"/>');
        $this->metadata = $this->xml->addChild('metadata');
        $this->manifest = $this->xml->addChild('manifest');
        $this->spine = $this->xml->addChild('spine');
        $this->xml->addAttribute('version', '3.0');
        $this->setModified(new \DateTime());
        $this->nav = $this->nav = new Nav();
    }
    private $xml;
    private $metadata;
    private $modified;
    private $manifest;
    private $spine;

    public $identifier = [];
    public $uniqueIdentifier = "pub-identifier";
    public $contributor = [];
    public $language = 'en';
    public $chapters = [];
    public $nav;



    public function addLanguage(String $lang, $default = true){
        if($default)
            $this->updateAttr($this->xml,'lang',$lang,'xml');
        $this->metadata->addChild('dc:language', $lang, 'http://purl.org/dc/elements/1.1/');
    }

    public function addTitle(Title $title){
        $title->addToXml($this->metadata);
    }

    public function setModified(\DateTime $date){
        if($this->modified === null){
            $this->modified = $this->metadata->addChild('meta', $date->format('Y-m-d\TH:i:s\Z'));
            $this->modified->addAttribute('property','dcterms:modified');
        }else{
            $this->modified[0] = $date->format('Y-m-d\TH:i:s\Z');
        }
    }

    public function addCSS($path, $id){
        $item = $this->manifest->addChild('item');
        $item->addAttribute('id',   $id);
        $item->addAttribute('href', $path);
        $item->addAttribute('media-type','text/css');
    }


    public function toString(){
        $this->xml->addAttribute('unique-identifier', $this->uniqueIdentifier);
        foreach($this->identifier as $identifier){
            // <dc:identifier id="pub-identifier">urn:isbn:9781449328030</dc:identifier>
            $identifier->addToXml($this->metadata);
        }


        $navItem = $this->manifest->addChild('item');
        $navItem->addAttribute('id','htmltoc');
        $navItem->addAttribute('properties','nav');
        $navItem->addAttribute('href','nav.xhtml');
        $navItem->addAttribute('media-type','application/xhtml+xml');
        $navItemRef = $this->spine->addChild('itemref');
        $navItemRef->addAttribute('idref','htmltoc');
        $navItemRef->addAttribute('linear','yes');

        $chapterId = 0;
        foreach ($this->chapters as $chapter){
            $chapterId++;
            $item = $this->manifest->addChild('item');
            $item->addAttribute('id',$chapter->id == null ? 'c'.$chapterId : $chapter->id);
            $item->addAttribute('href', $chapter->path);
            $item->addAttribute('media-type','application/xhtml+xml');

            $itemref = $this->spine->addChild('itemref');
            $itemref->addAttribute('idref',$chapter->id == null ? 'c'.$chapterId : $chapter->id);

        }

        //$dom = dom_import_simplexml($this->xml)->ownerDocument;
        //$dom->formatOutput = true;
        //echo $dom->saveXML();
        //dd();
        return $this->xml->asXML();
    }

    private function updateAttr($xml, $attr, $value, $namespace = null){
        if(isset($xml->attributes($namespace)[$attr]))
            $xml->attributes($namespace)[$attr] = $value;
        else
            $xml->addAttribute($namespace == null ? $attr : $namespace.':'. $attr, $value,$namespace);
    }
}