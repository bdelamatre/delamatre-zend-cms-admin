<?php

namespace DelamatreZendCmsAdmin\Form;
use Doctrine\ORM\EntityManager;

/**
 * This is generally considered bad practice, but stuff needs to get done.
 *
 * Class ConfigIntegration
 * @package DelamatreZendCmsAdmin\Form
 */
trait ConfigIntegration{

    public $config;

    public function setConfig($config){
        $this->config = $config;
    }

    /**
     * @return EntityManager
     * @throws \Exception
     */
    public function getConfig(){

        if(!$this->config){
            throw new \Exception("Config is not set on this form");
        }

        return $this->config;
    }

    public function hasConfig(){
        if($this->config){
            return true;
        }else{
            return false;
        }
    }

}