<?php
error_reporting(0);
$pass=$_GET['password'];
$post=$_GET['post'];
$link=$_GET['link'];
$response=$_GET['response'];
if($pass=='smartart'){
    echo "<body background='bg.jpg'>";
    echo '<h1 style="text-align: center;"><span style="text-decoration: underline; color: #3366ff;"><strong>#Bot Admin</strong></span> <span style="text-decoration: underline;"><strong><img src="https://html-online.com/editor/tinymce4_6_5/plugins/emoticons/img/smiley-cool.gif" alt="cool" /><img src="https://html-online.com/editor/tinymce4_6_5/plugins/emoticons/img/smiley-cool.gif" alt="cool" /></strong></span></h1>
<p style="text-align: right;"><span style="text-decoration: underline; color: #333399;"><strong>تم التأكد من الهوية وتسجيل الدخول كمدير </strong></span></p>
<p style="text-align: right;"><span style="text-decoration: underline;"><strong><span style="color: #333399; text-decoration: underline;">ادناه تظهر روابط المنشورات المتوفرة ,لاضافة رد تلقائي لاحدى المنشورات انسخ رابط المنشور مع الرد المناسب</span><br /></strong></span></p>';
    $servername = "localhost";
    $username = "id8300997_nerd";
    $password = "smartart";
    $dbname = "id8300997_bot";
    $links="select * from posts";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql_result=$conn->query($links);
    if($sql_result->num_rows>0){
        print('<table border="5" >');
        print("<tr>"); 
        print("<td style='color: #ffffff'>");
        print("Post urls:");
        print("</td>");  
        print("</tr>"); 
        while($row=$sql_result->fetch_assoc()){
            $url=$row['post_url'];
            print("<tr>"); 
            print("<td>");
            print("<a style='color: #ffffff' href='$url' target='_blank'>");
            print($url);
            print("</a>");
            print("</td>");
            print("</tr>"); 
        } 
        print("</table>"); 
    }
        print('<table border="5" >');
        print("<tr>"); 
        print("<td >");
    print(" <form action='/admin.php' method='get'>
  <span style='color: #ffffff' >رابط البوست:</span><br>
  <input type='text' name='post' value=''><br>
  <span style='color: #ffffff' >الرد على البوست:</span><br>
 <textarea rows='4' cols='50' name='response' >0</textarea><br>
   <span style='color: #ffffff' >الرابط المطلوب ارساله:</span><br>
  <input type='text' name='link' value=''><br>
   <span style='color: #ffffff' >password:</span><br>
  <input type='text' name='password' value='smartart'><br><br>
  <input type='submit' value='Submit'>
</form> ");
      print("</td>");  
        print("</tr>"); 
             print("</table>");

print('<table border="5" >');
print("<tr>"); 
print("<td >");
print(" <span style='color: #ffffff' >قسم الاشعارات:</span><br>");
if(isset($link) and isset($post) and isset($response) and $link!="" and $post!=""){
    if($response==""){
        $response="0";
    }
    ############################################
    ############## sql query ##################
    $servername = "localhost";
    $username = "id8300997_nerd";
    $password = "smartart";
    $dbname = "id8300997_bot";
    $add_response="INSERT INTO links (post,text,link)  VALUES ('$post','$response','$link')";
    $conn->query($add_response);
    print('تمت اضافة رد للبوست $post');
}else{
     print('لا يوجد تنبيهات');
}
print("</td>");  
print("</tr>"); 
print("</table>");
}
?>