<?php
include_once 'searchbyname.php';
?>
<head>
<script src="../js/jquery.min.js"></script>
<script src="../js/scrollpane/jquery.jscrollpane.min.js" type="text/javascript"></script>
<script src="../js/scrollpane/jquery.mousewheel.js"></script>
<script src="../js/scrollpane/mwheelIntent.js"></script>
<script type="text/javascript">
$(function()
{
	$('#scroll-pane').jScrollPane();
       // $('#scroll-pane').append('$search->searchbyname();');
});
   
</script>
</head>
<?php
$search=new SearchbynameClass();
$search->variable=$_REQUEST['propid'];  
echo '<div id="scroll-pane">';
echo $search->searchbyname();
echo '<div>';
?>

