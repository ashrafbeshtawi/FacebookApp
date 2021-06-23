<?php
    $name=str_replace(array("'", '"', ";","OR","AND"), array("", "", ",","oder","und"), $_POST['name']);
    $email=str_replace(array("'", '"', ";","OR","AND"), array("", "", ",","oder","und"), $_POST['email']);
    $feed=str_replace(array("'", '"', ";","OR","AND"), array("", "", ",","oder","und"), $_POST['feed']);

    ############################################
    ############## sql query ##################
    $servername = "removed for security reasons";
    $username = "removed for security reasons";
    $password = "removed for security reasons";
    $dbname = "removed for security reasons";
    $query="INSERT INTO feed (name,email,feed)  VALUES ('$name','$email','$feed')";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql_result=$conn->query($query);
?>
<script type="text/javascript">
    window.location.replace("https://"+window.location.hostname);

</script>