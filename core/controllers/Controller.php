<?php

namespace Core\Controllers;


use \PicORM\PicORM;
use Core\Helpers\Flashbag;
use Core\Helpers\Twig;
use Core\Helpers\Url;

class Controller
{
    protected $twig;
    protected $url;
    protected $flashbag;
    protected $db = null;
    

    function __construct()
    {
        $this->url = new Url();
        $this->flashbag = new Flashbag();
        $this->twig = new Twig($this->flashbag);
        $this->setOrm();
    }

    private function setOrm() 
    {
        if ($this->db === null && getenv('MYSQL_DATABASE') != '') {
            $this->db = PicORM::configure(array(
                'datasource' => new \PDO('mysql:dbname='.getenv('MYSQL_DATABASE').';host='.getenv('MYSQL_HOST').'', getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'))
            ));
        }
    }

}