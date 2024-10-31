<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../partials-css/sidebar.css">
</head>
<body>

<div id="mySideBar" class="side-bar">
  <a href="javascript:void(0)" class="close-btn" onclick="closeNav()">×</a>
  <a href="#">Profile</a>
  <a href="userhistory.php">My Posts</a>
  <a href="#">Pet Groomers</a>
  <a href="#">Pet Sitters</a>
</div>

<div id="main">
  <button class="open-btn" onclick="openNav()">☰ More </button>
</div>

<script>
function openNav() {
  document.getElementById("mySideBar").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
  document.getElementById("mySideBar").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
</script>
   
</body>
</html> 
