<?php
namespace Jocolopes\Request;

use Jenssegers\Agent\Agent;

class Types {

    protected $types;
    protected $agent;

    public function __construct(){
        $this->agent = new Agent();
    }

    private function grabTypes() {
        $this->types = array('universal');

        if($this->agent->isMobile()){
            $this->addType('mobile');
        }else if($this->agent->isTablet()){
            $this->addType('tablet');
        }else{
            $this->addType('desktop');
        }

        $this->addType($this->agent->platform());
        $this->addType($this->agent->device());
        $this->addType($this->agent->browser());

        if($this->agent->isRobot()){
            $this->addType('robot');
        }
    }

    private function addType($type){
        $type = strtolower(str_replace(' ', '', $type));
        if(!empty($type)){
            array_push($this->types, $type);
        }
    }

    public function getTypes(){
        $this->grabTypes();
        return $this->types;
    }

    public function setUserAgent($ua) {
        $this->agent->setUserAgent($ua);
    }
}