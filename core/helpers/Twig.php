<?php

namespace Core\Helpers;

use \Twig_Loader_Filesystem;
use \Twig_Environment;
use Core\Helpers\Url;

class Twig {
    protected $twig;
    protected $flashbag;
    protected $url;

    function __construct($flashbag)
    {
        $this->flashbag = $flashbag;
        $this->initialize();
        $this->url = new Url();
    }
    
    protected function initialize()
    {
        $loader = new Twig_Loader_Filesystem('./app/views/');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => false,
            'debug' => ((getenv('APP_DEBUG') == 'true') ? true : false)
        ));
    
        $this->twig->addFunction(new \Twig_SimpleFunction('url', function ($url) {
            $uri = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
            return $this->url->getRoot().$url;
        }));
    
        if (getenv('APP_DEBUG') == 'true') {
            $this->twig->addExtension(new \Twig_Extension_Debug());
        }
    }

    public function render($template, $arguments = []) {
        $arguments['flashbag'] = $this->flashbag;
        echo $this->twig->render($template, $arguments);
        die();   
    }


}