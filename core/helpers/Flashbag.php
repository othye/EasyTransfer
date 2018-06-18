<?php

namespace Core\Helpers;

class Flashbag {

    protected $flashbag = [];
    
    function __contruct() {
        session_start();
    }


    public function has($index) {
        $this->flashbag = (isset($_SESSION['flashbag']) ? $_SESSION['flashbag'] : []);
        return isset($this->flashbag[$index]);
    }

    public function set($index, $value) {
        $this->flashbag[$index] = $value;
        $_SESSION['flashbag'] = $this->flashbag;
    }

    public function get($index) {
        $this->flashbag = $_SESSION['flashbag'];
        if (isset($this->flashbag[$index])) {
            $toReturn = $this->flashbag[$index];
        }
        unset($this->flashbag[$index]);
        $_SESSION['flashbag'] = $this->flashbag;
        return $toReturn;
    }

}