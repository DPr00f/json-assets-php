<?php
namespace Jocolopes\Assets;

use Jocolopes\Filefilter;


class Json {

    protected $assets;
    protected $assetsRootFolder;
    protected $types;
    protected $jsonFile;
    protected $assetsJson;
    protected $fFilter;


    public function __construct($jsonFile, $assetsRootFolder, $typesClass = '\Jocolopes\Request\Types') {
        $this->jsonFile         = $jsonFile;
        $this->assetsRootFolder = $assetsRootFolder;
        $this->types            = new $typesClass();
        $this->types            = $this->types->getTypes();
        $this->fFilter          = new Filefilter();
    }

    public function setJsonFile($jsonFile){
        $this->jsonFile         = $jsonFile;
    }

    public function loadJsonFile(){
        return json_decode( file_get_contents($this->jsonFile) ); 
    }

    public function getAssets(){
        $this->assets = array('head' => [], 'body' => []);
        $this->assetsJson = $this->loadJsonFile();
        foreach($this->types as $type){
            $this->loadFiles($type, 'head');
            $this->loadFiles($type, 'body');
        }

        return $this->assets;
    }

    public function loadFiles($type, $position)
    {
        if(property_exists($this->assetsJson, $type) and property_exists($this->assetsJson->$type, $position)){
            $filesArray = $this->assetsJson->$type->$position;
            foreach($filesArray as $file){
                $fileWithRoot = $this->assetsRootFolder . $file;
                if(is_file($fileWithRoot)){
                    if(!in_array($file, $this->assets[$position])){
                        array_push($this->assets[$position], $file);
                    }
                }else{
                    if(strpos($fileWithRoot, '*') === FALSE){
                        throw new \Exception("File '$fileWithRoot' couldn't be found");
                    }
                    $files = $this->fFilter->scan($fileWithRoot);
                    foreach($files as $scannedFile){
                        $scannedFile = substr($scannedFile, strlen($this->assetsRootFolder));
                        if(!in_array($scannedFile, $this->assets[$position])){
                            array_push($this->assets[$position], $scannedFile);
                        }
                    }
                }

            }
        }
    }

    public function getAssetsWithTag(){
        $assets = $this->getAssets();
        $head = $assets['head'];
        $body = $assets['body'];
        $newHead = array();
        $newBody = array();
        foreach($head as $file){
            if(strpos( $file, '.js' ) !== FALSE){
                array_push($newHead, '<script type="text/javascript" src="'. $file .'"></script>');
            }else if(strpos( $file, '.css' ) !== FALSE){
                array_push($newHead, '<link rel="stylesheet" type="text/css" href="'. $file .'"/>');
            }
        }
        foreach($body as $file){
            if(strpos( $file, '.js' ) !== FALSE){
                array_push($newBody, '<script type="text/javascript" src="'. $file .'"></script>');
            }else if(strpos( $file, '.css' ) !== FALSE){
                array_push($newBody, '<link rel="stylesheet" type="text/css" href="'. $file .'"/>');
            }
        }

        return array('head' => $newHead, 'body' => $newBody);
    }
}