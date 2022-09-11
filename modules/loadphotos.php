<html lang="en">
<head>
<meta charset="utf-8">
<title>Load photos</title>
<link rel="stylesheet" type="text/css" href="/property1/css/photos.css" media="screen" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
</head>
<body>
    <script>
    $(document).ready(function() {
 $.ajaxSetup({
    cache: false
});
 $('#images').append('<img src="photos/image1.jpg"</img>');
  $('#images1').append('<img src="photos/image1.jpg"</img>');
   /*var refreshId = setInterval(function() {
       $('#images').append('<img src="photos/image1.jpg"</img>');
   }, 10000);*/
});

</script>
<div id="images"></div>
<div id="images1"></div>

</body>
</html>