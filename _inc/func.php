<?php 

function take(&$var1, $defValue='') {
    return isset($var1) ? $var1 : $defValue;
}
function get($index, $defValue='') {
    return take($_GET[$index], $defValue);
}
function post($index, $defValue='') {
    return take($_POST[$index], $defValue);
}
function session($index, $setValue=NULL) {
    if($setValue == NULL) {
        return take($_SESSION[$index]);
    } else {
        $oldValue = take($_SESSION[$index], NULL);
        $_SESSION[$index] = $setValue;
        return $oldValue;
    }
}

?>