<?php
$var="'text'";
print($var);
$on_click='onclick=x(';
$close=')';
print("<p onclick=x($var) >jhljlkj</p>");


?>
<script>
    function x(text){
        alert(text);
    }
</script>