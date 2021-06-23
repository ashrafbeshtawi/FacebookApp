<h3>Please choose the page which u want to use:</h3>

<?php
$short_access=$_POST['access'];

//converting the short access to token to long lived access token
$url="https://graph.facebook.com/v3.2/oauth/access_token?grant_type=fb_exchange_token&client_id=798660403812638&client_secret=838ca4dbe194b80bda5b881bc852d0c0&fb_exchange_token=$short_access";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result =json_decode(curl_exec($ch));
$long_access=$result->access_token;
//using the new access token to get the pages
$url="https://graph.facebook.com/v3.2/me/accounts?access_token=$long_access";
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result =curl_exec($ch);

$nc=json_decode($result);
if(isset($nc->error)){
    print("<script>alert('Error: you have chosen more than 3 Pages .. please try again');document.location.href =document.location.origin+'/login.php';</script>");
}
$nc=$nc->data;
$num=count((array) $nc);

for($i=0;$i<$num;$i++){
    $name=$nc[$i]->name;
    $id=$nc[$i]->id;
    $access=$nc[$i]->access_token;

    print('<form action="/subscribe.php"  method="post">');
    print('<input type="hidden"   name="name" value="'.$name.'">');
    print('<input type="hidden"  name="id" value="'.$id.'">');
    print('<input type="hidden"  name="access" value="'.$access.'">');
    print('<input type="submit" class="btn btn-info btn-lg" value="'.$name.'"></form></br>');


}


?>