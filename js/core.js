$(document).ready(function() {
$(function() {
	
	$('#print').click(function() {
		var container = $(this).attr('rel');
		$('#' + container).printArea();
		return false;
	});
	
});

$(function() {
	
	$('#pdf').click(function() {
            var html=$('#printable').html()+'</body></html>';
            window.open("../tcpdf/pdf.php?html="+html);
        

	});
	
});
                
   $(function() {
	
	$('#printnow').click(function() {
		var container = $(this).attr('rel');
		$('#' + container).printArea();
		return false;
	});
	
});     
 $(function() {
	
$("#tbtoexcel").click(function(e){
	var x = $(".exportlist").clone();
$(x).find("tr td a").replaceWith(function(){
  return $.text([this]);
});
x.find('.noExl').remove();
   x.table2excel({
					//exclude: ".noExl",
					name: "Exported File",
					filename: "Tenant List"
				});
                                
	});
	
});
        $(function() {
          
       $('#prntinvoice').click(function(e) {
           e.preventDefault();
           
		var container = $(this).attr('rel');
		$('.' + container).printArea();
                return false;
	});
    }); 
         $(function() {
          
       $('#closenow').click(function(e) {
           e.preventDefault();
           window.close();
	});
    }); 
	
	
});//DOM