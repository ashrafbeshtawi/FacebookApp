<?php
if(isset($_GET['hub_challenge'])) {
    print_r($_GET['hub_challenge']);
}else{
include("pages/sql.php");

$file = file_put_contents("nachricht.json",file_get_contents("php://input"));    
$nc=json_decode(file_get_contents("php://input"));

$page_id="";
$id="";
$text="";
if(isset($nc->entry[0]->id)){
    $page_id=$nc->entry[0]->id;
    if(isset($nc->entry[0]->messaging)){
        $id=$nc->entry[0]->messaging[0]->sender->id;
        $message=$nc->entry[0]->messaging[0]->message->text;
 
        
        $query="select response from message where id='$page_id' and message='$message'";
        $result=sql($query,"smartart");
        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            $text=$row['response'];

        }else{
            $query="select response from message where id='$page_id' and message='default'";
            $result=sql($query,"smartart");
            if($result->num_rows > 0){
                $row=$result->fetch_assoc();
                $text=$row['response'];

            }
        }  
             file_put_contents("nach.json","false");     
    }else if(isset($nc->entry[0]->changes) and $nc->entry[0]->changes[0]->value->item=="comment" and $nc->entry[0]->changes[0]->value->verb=="add"){
 
        file_put_contents("nach.json","true");  
        $id=$nc->entry[0]->changes[0]->value->from->id;
        $link=$nc->entry[0]->changes[0]->value->post_id;
        

        
        $query="select response from link where id='$page_id' and link='$link'";
        $result=sql($query,"smartart");
        if($result->num_rows > 0){
                $row=$result->fetch_assoc();
                $text=$row['response'];
                $file = file_put_contents("nachricht2.json",$page_id."   ".$id."  ".$text); 
        }
    }
}


if($text!=""){
  //Url to send the post requset to
    $query="select * from page where id='$page_id'";
    $sql_result=sql($query,"smartart");
    $row = $sql_result->fetch_assoc();
    $token=$row["access"]; 
    $url="https://graph.facebook.com/v3.2/me/messages?access_token=$token";


    //info to be sended
    $data=array('recipient'=>array('id'=>"$id"),'message'=>array('text'=>"$text"));
    $data_encoded=json_encode($data);
    //header
    $header=array('Content-Type: application/json');

    //Tell cURL that we want to send a POST request.

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
 
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_encoded);
 
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);


    //send the request
    $result = curl_exec($ch);
    
}



}

?>