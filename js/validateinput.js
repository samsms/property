$("button#submit").click( function() {
 
  if($("#proptype").val() == "" || $("#pname").val() == "" )   {
//$("div#ack").html("<center><b><font color=red>Please enter required fields</font></b></center>");
 alert('please enter required fields');
$('#commentForm')[0].reset();}

  else{
    $.post( $("#commentForm").attr("action"),
	        $("#commentForm :input").serializeArray(),
			function(data) {
			  alert(data);
                          $('#commentForm')[0].reset();
                          //$("div#ack").html(data);
		
                    });

	}
 
});


