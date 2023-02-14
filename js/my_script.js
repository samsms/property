$("button#submit").click(function() {
 
  if( $("#username").val() == "" || $("#password").val() == "" )
    $("div#ack").html("<b>Please enter both username and password</b>");
  else
    $.post( $("#myForm").attr("action"),
	        $("#myForm :input").serializeArray(),
			function(data) {
			  $("div#ack").html(data);
		
                    });

	$("#myForm").submit( function() {
	   return false;	
	});
 
});

