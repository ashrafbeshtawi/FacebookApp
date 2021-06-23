<?php
    $name=str_replace(array("'", '"', ";","OR","AND"), array("", "", ",","oder","und"), $_POST['name']);
    $email=str_replace(array("'", '"', ";","OR","AND"), array("", "", ",","oder","und"), $_POST['email']);
    $feed=str_replace(array("'", '"', ";","OR","AND"), array("", "", ",","oder","und"), $_POST['feed']);

    ############################################
    ############## sql query ##################
    $servername = "localhost";
    $username = "id8300997_nerd";
    $password = "smartart";
    $dbname = "id8300997_bot";
    $query="INSERT INTO feed (name,email,feed)  VALUES ('$name','$email','$feed')";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql_result=$conn->query($query);
?>
<script type="text/javascript">
    window.location.replace("https://"+window.location.hostname);

</script>