<p id = "intrare"  onclick = "moveRight()">INTRARE</p>
<img src="one.gif" id="soare" style="position:relative;top:10px;left:-200px; "><br>
<script type="text/javascript">

var userWidth = window.screen.width;
function moveRight() {
var pp = document.getElementById("soare");
var lft = parseInt(pp.style.left);
var tim = setTimeout("moveRight()",50);  // 50 controls the speed
lft = lft+50;  // move by 50 pixels
pp.style.left = lft+"px";
if (lft > userWidth + 439) {  // left edge of image past the right edge of screen
pp.style.left = -878;  // back to the left
clearTimeout(tim);
}
}

</script>