<?php
    $id=$_POST['id'];
    $name=$_POST['name'];
    $access=$_POST['access'];

    include("pages/sql.php");
    $query="select * from page where id=$id";
    $sql_result=sql($query,"smartart");
    if($sql_result->num_rows>0){
        $query="UPDATE page SET access='$access' WHERE id='$id';";
        $sql_result=sql($query,"smartart");
        auto($id,$access,$name);
    }else{
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://graph.facebook.com/v3.2/$id/subscribed_apps");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,"access_token=$access&subscribed_fields=feed,messages");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $nc= curl_exec($ch);
        $x=$nc;
        $nc=json_decode($nc);
        file_put_contents("nachricht.json",$x); 
        
        if(isset($nc->error)){
            $error=$nc->error->code;
            print("Error while subscribing to Our App</br>Error code:$error</br>Page Name:$name</br>Please log in again and make sure to give our app the permission it needs.</br>");
            print("For the purpose of testing this App to get the mange_page permission from Facebook we have added for now a direct link to the control panel of the users</br>");
            $form="<form id='pass' action='control_panel.php' method='post'><input type='hidden' name='id' value='$id'><input type='hidden' name='access' value='$access'><input type='hidden' name='name' value='$name'><input type='submit' class='btn btn-info btn-lg' value='To control Panel'></form>";
            print($form);
        }else if(isset($nc->success)){
        $query="INSERT INTO page (id,access,service) VALUES ('$id', '$access','massege,feed'); ";
        $sql_result=sql($query,"smartart");
        auto($id,$access,$name);
        }

        
    }
    
function auto($id,$access,$name){
    $form="<form id='pass' action='control_panel.php' method='post'><input type='hidden' name='id' value='$id'><input type='hidden' name='access' value='$access'><input type='hidden' name='name' value='$name'></form>";
    $auto_direct='<script> document.getElementById("pass").submit();</script>';
    print($form.$auto_direct);



}

?>

