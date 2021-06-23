<html>
    <body>
    <li><iframe id="frame" src="req.php?name=test" width="1000" height="500" scrolling="no" style="overflow:hidden; margin-top:-4px; margin-left:-4px; border:none;"></iframe></li>

    <input type="text"  id="attr">  
    <button onclick="req()">send</button>    
<script>
var frame=document.getElementById("frame");
var attr=document.getElementById("attr").value;
function req(){
    url="req.php?name="+attr;
    frame.setAttribute("src", url); 
alert(url)
}

</script>
</body>

</html>
