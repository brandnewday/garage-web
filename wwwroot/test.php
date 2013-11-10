<html>
<head>
<script type="text/javascript"><!--
 function floatMenu(){
  if(!document.getElementById) return;
  var menu = document.getElementById('menu');
  var distY = ((!document.all)?(window.pageYOffset):(document.documentElement)?document.documentElement.scrollTop:    document.body.scrollTop);
   if(distY < 600){ // 600 would be the height of your header area, you may need to change this
      distY = 600; // this is also the height of your header and will keep the menu here instead of at 0
   }
      menu.style.top = distY+"px";
 }
window.onscroll = floatMenu;
//--></script>
<style type="text/css"><!--
#menu {
 position:fixed;
 top: 0px;
 left:0px;
}
--></style>
</head>
<body>

<div id="menu">
item a b c
</div>

a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />
a<br />

</body>
</html>