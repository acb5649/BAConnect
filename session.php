<?php


if(session_id() == '') {
    session_start();
}

if (isset($_SESSION['type'])) {
    $type = $_SESSION['type'];
} else {
    $type=0;
}
