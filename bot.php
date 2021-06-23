<?php
error_reporting(0);
//getting the request and saving it to a file
file_put_contents("nachricht.json",file_get_contents("php://input"));

//reading the request from the file
$nc=file_get_contents("nachricht.json");
//decoding it
$nc=json_decode($nc);

//answer te be sended
$default_text="شكرا لتواصلك معنا لقد تم العثور على الرابط المطلوب .. نرجو دعمنا باللايك والشير";
$text="شكرا لتواصلك معنا سيتم الرد على استفسارك في اقرب وقت";

$id="";
$message="";
$post_id="";
$main_array=(array) $nc->entry[0];

if (array_key_exists('messaging', $main_array)) {
    $id=read_from_nachricht($nc)[0];
    $message=read_from_nachricht($nc)[1];

} else if(array_key_exists('changes', $main_array)) {
    $id=read_from_comment($nc)[0];
    $post_url=read_from_comment($nc)[1];

    ############################################
    ############## sql query ##################
    $servername = "removed for security reasons";
    $username = "removed for security reasons";
    $password = "removed for security reasons";
    $dbname = "removed for security reasons";
    $query="select text,link from links where post='$post_url'";
    $update_links="select * from posts where post_url='$post_url'";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql_result=$conn->query($query);
    $update_links_result=$conn->query($update_links);
    file_put_contents("query.json",$sql_result);
    file_put_contents("log.txt",$post_url);
    if($sql_result->num_rows>0){
        $row=$sql_result->fetch_assoc();
        $response_link=$row["link"];
        $response_text=$row["text"];
        if($response_text=="0"){
            $response_text=$default_text;  
        }
        $text="$response_text $response_link";
        
    }else{
        $id="";
    }
    if($update_links_result->num_rows==0){
        $add_url="INSERT INTO posts (post_url)  VALUES ('$post_url')";
        $conn->query($add_url);
    }

}


//saving the user_id & his message in variabels

if($id!=""){

//Url to send the post requset to
$token="EAALWYJ2qWR4BANDa0rwx62pckM3GN69vEosuH0hBo3h19EZBEXoZA2ubaiJJuWhDdwN5Qo1ktZARNkqA1By7fqT2ZCIpFU5NycfTtnxZCodWh3PHurw2xBngVpFTBZBZB6mzqB95dt6trGJObglBDJZARgPzWnIiXhZASFQyDZBQZAF4wZDZD";
$url="https://graph.facebook.com/v2.6/me/messages?access_token=$token";

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


##cleaning the json file
//getting the request and saving it to a file
//file_put_contents("nachricht.json","");



############functions section###################

#read the id and text from message
function read_from_nachricht($nc){
    $id=$nc->entry[0]->messaging[0]->sender->id;
    $message=$nc->entry[0]->messaging[0]->message->text;
    return array(0=>$id,1=>$message);
}
#read the id from comment
function read_from_comment($nc){
    $item=$nc->entry[0]->changes[0]->value->item;
    $verb=$nc->entry[0]->changes[0]->value->verb;
    $ruckgabe=array(0=>"",1=>"");
    if($item=="comment" and $verb=="add"){
    $ruckgabe[0]=$nc->entry[0]->changes[0]->value->from->id;
    $ruckgabe[1]=$nc->entry[0]->changes[0]->value->post->permalink_url;
    }
    return $ruckgabe;
}
?>