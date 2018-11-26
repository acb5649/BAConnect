<?php
class Report{
    public $title;
    public $msg;
    public $nextModal;
    public $success;
    public $inputs; //associative array of all the users' inputs, so you
                        //can reset them when the modal re-opens.

    function __construct($name, $message, $next, $worked){
        $this->title = $name;
        $this->msg = $message;
        if(strpos($next, 'Modal') === false){
            $next = $next . "Modal";
        }
        $this->nextModal = $next;
        $this->success = $worked;
        $this->inputs = null;
    }
}

if(session_id() == '') {
    session_start();
}

if (isset($_SESSION['type'])) {
    $type = $_SESSION['type'];
} else {
    $type=0;
}
