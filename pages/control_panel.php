
<?php
//page name
$name="Unknown User";
//page access token
$access="no access";
//page access token
$id="";
//feed
$data=null;
$num=0;
//save messages responses
$saved_messages=null;
//save posts responses
$saved_posts=null;
//catch new reponse
$response_added=0;

if(isset($_POST["access"]) and isset($_POST["id"]) and isset($_POST["name"])){
    include("pages/sql.php");
    $id=$_POST["id"];
    $name=$_POST["name"];
    $access=$_POST["access"];

    $query="select access from page where id='$id';";
    $result=sql($query,"removed for security reasons");
    $row = $result->fetch_assoc();
    
    //if identity confirmed
    if($access==$row['access']){

        //detecting if any data was sended from the forms
        if(isset($_POST["response"])){
            $response=$_POST["response"];
            
            if(isset($_POST["message"])){
                $message=$_POST["message"];
                $query="select response from message where id='$id' and message='$message'";
                $sql_result=sql($query,"removed for security reasons");    
                if($sql_result->num_rows >0){
                    if($response!=""){
                        $query="update message set response='$response' where id='$id' and message='$message'";
                    }else{
                        $query="DELETE FROM message WHERE id='$id' and message='$message';";
                    }
                    $sql_result=sql($query,"removed for security reasons"); 
                    
                }else{
                    $query="INSERT INTO message (id, message, response) VALUES ('$id', '$message', '$response'); ";
                    sql($query,"removed for security reasons");
                }
                $response_added=1;
                
            
            }else if(isset($_POST["link"])){
                $link=$_POST["link"];
                
                $query="select response from link where id='$id' and link='$link'";
                $sql_result=sql($query,"removed for security reasons"); 
                if($sql_result->num_rows >0){
                    if($response!=""){
                        $query="update link set response='$response' where id='$id' and link='$link'";
                    }else{
                        $query="DELETE FROM link WHERE id='$id' and link='$link';";
                    }
                    $sql_result=sql($query,"removed for security reasons");   
                }else{
                    $query="INSERT INTO link (id, link, response) VALUES ('$id', '$link', '$response'); ";
                    $link=sql($query,"removed for security reasons");
                }
                $response_added=2;
                
            
            }

            
        }
            $url="https://graph.facebook.com/v3.2/$id/posts?fields=message,permalink_url&access_token=$access";
            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($handle);
            $data=json_decode($data)->data;
            $num=count($data);
            
            $query="select * from message where id='$id'";
            $saved_messages=sql($query,"removed for security reasons");
            $query="select * from link where id='$id'";
            $saved_posts=sql($query,"removed for security reasons");
    }else{
        print("<script>alert('User can not be identified please log in again');</script>");
    }
}else{
    print("<script>alert('User can not be identified please log in again');</script>");
}
?>
<h2 style="text-align: center;"><strong>Control Panel of <?php print($name); ?></strong></h2>

<div>


<table border="5" align="center">
   <tbody>
      <tr style='color: #ffffff'>
         <td>
            <form action="" method="post">
               Post id:<br /> <input id="post" name="link" type="text" value="" class="input-group-text"/>
               Response:<br /> <textarea name="response"  class="input-group-text"></textarea> <br />
            <input name="id" type="hidden" value="<?php print($id); ?>" />
            <input name="name" type="hidden" value="<?php print($name); ?>" />
            <input name="access" type="hidden" value="<?php print($access); ?>" />
               <input type="submit" value="Submit" class="btn btn-primary" />
            </form>
         </td>
         <td>
            <form action="" method="post">Message:<br />
            <input id="message" name="message" type="text" value="" class="input-group-text"/>
            Response:<br /> <textarea name="response"  class="input-group-text"></textarea>
            <input name="id" type="hidden" value="<?php print($id); ?>" />
            <input name="name" type="hidden" value="<?php print($name); ?>" />
            <input name="access" type="hidden" value="<?php print($access); ?>" />
 <br /> <input type="submit" value="Submit" class="btn btn-primary"/>
                 </form>
         </td>
         <td>
            <form action="" method="post">Default Response to messages:<br />
            <textarea name="response" class="input-group-text"></textarea>
            <br /> <input name="message" type="hidden" value="default" />
            <input name="id" type="hidden" value="<?php print($id); ?>" />
            <input name="name" type="hidden" value="<?php print($name); ?>" />
            <input name="access" type="hidden" value="<?php print($access); ?>" />
            <input type="submit" value="Submit" class="btn btn-primary"/>
            </form>
         </td>
      </tr>
   </tbody>
</table>
</div>
<table border="5" align="center">
<tbody>
<tr style='color: #ffffff'>
<td id="hints"></td>
</tr>
</tbody>
</table>
<?php
$text='Hints: No new Events';
if($response_added==1){
    $text='Hints: The response to the Message was Updated';
}else if($response_added==2){
    $text='Hints: The response to the Post was Updated';
}
print("<script>document.getElementById('hints').innerHTML='$text';</script>");
?>


</br>
<h3>List of events you have created</h3>
<table border="5" style="float: left;">
<tbody>
    <?php 
    print("<tr style='color: #ffffff'>");
        print("<td></td>");
        print("<td>Message</td>");
        print("<td>Response</td>");
        print("</tr>");
        if ($saved_messages->num_rows > 0) {
        // output data of each row
            while($row = $saved_messages->fetch_assoc()) {
                $m=$row['message'];
                $r=$row['response'];
                print("<tr style='color: #ffffff'>");
                
                print("<td><form method='post'>
                    <input name='message' type='hidden' value='$m' />
                    <input name='response' type='hidden' value='' />
                    <input name='id' type='hidden' value='$id' />
                    <input name='name' type='hidden' value='$name' />
                    <input name='access' type='hidden' value='$access' />
                    <input type='submit' value='X' class='btn btn-primary'/>
                    </form></td>");
                print("<td>$m</td>");
                print("<td>$r</td>");
                print("</tr>");
            }
        }
        

    ?>
</tbody>
</table>
<table border="5" >
<tbody>
    <?php 
    print("<tr style='color: #ffffff'>");
        print("<td></td>");
        print("<td>Post</td>");
        print("<td>Response</td>");
        print("</tr>");
        if ($saved_posts->num_rows > 0) {
        // output data of each row
            while($row = $saved_posts->fetch_assoc()) {
                $m=$row['link'];
                $r=$row['response'];

                print("<tr style='color: #ffffff'>");
                  print("<td><form method='post'>
                <input name='link' type='hidden' value='$m' />
                <input name='response' type='hidden' value='' />
                <input name='id' type='hidden' value='$id' />
                <input name='name' type='hidden' value='$name' />
                <input name='access' type='hidden' value='$access' />
                <input type='submit' value='X' class='btn btn-primary'/>
                </form></td>");
                print("<td style='text-decoration: underline;color: #8000ff;' onclick=fill_text_box('$m') > $m</td>");
                print("<td>$r</td>");
                print("</tr>");
            }
        }
        

    ?>
</tbody>
</table>
</br>

<h3>List of your newest Posts</h3>
<table border="5" >

<tbody>
    <?php 
    print("<tr style='color: #ffffff'>");
        print("<td>Post ID</td>");
        print("<td>Post description</td>");
        print("</tr>");
    for($i=0;$i<$num;$i++){
        $post_id=$data[$i]->id;
        $post_link=$data[$i]->permalink_url;
        $view_post="<a href='$post_link' target='_blank'>View the Post</a>";
        if(isset($data[$i]->message)){
            $post_text=$data[$i]->message;
            if(strlen($post_text)>50){
                $post_text=substr($post_text,0,50);
            }
        }else{
            $post_text="The Post has no Text";
        }
        
        
        print("<tr style='color: #ffffff'>");
        print("<td style='text-decoration: underline;color: #8000ff;' onclick=fill_text_box('$post_id')>$post_id</td>");
        print("<td>$post_text.$view_post</td>");
        print("</tr>");
        
        
    }
    ?>
</tbody>
</table>

<script>
    function fill_text_box(id){

        document.getElementById("post").value=id;
    }
    function fill_text_box_message(message){
        document.getElementById("message").value=message;
    }
</script>

