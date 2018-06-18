<?php

namespace Core\Helpers;

class Url {
    public function getRoot() {
        return (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http').'://'.$_SERVER['SERVER_NAME'].(isset($_SERVER['SERVER_PORT']) ? ':'.$_SERVER['SERVER_PORT'] : '').str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
    }

    public function redirect($route = "")
    {
        header('Location: '.$this->getRoot().$route);
        die();
    }
}