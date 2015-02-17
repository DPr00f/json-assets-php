<?php
namespace Jocolopes\Assets;

use Jocolopes\Filefilter;


class Json {

    protected $loadedFiles;
    protected $assetsRootFolder;
    protected $types;

    function __construct($jsonFile, $assetsRootFolder, $typesClass = '\Jocolopes\Request\Types') {
        $this->loadedFiles      = array('head' => [], 'body' => []);
        $this->assetsRootFolder = $assetsRootFolder;
        $this->types            = new $typesClass();
    }
}