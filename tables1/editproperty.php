			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/demo_page.css">
			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/demo_table.css">
                        <link rel="stylesheet" href="../css/form.css">
			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/demo_table_jui.css">
			<link rel="stylesheet" href="<?php echo $baseurl;?>css/jquery-ui-git.css">
			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/themes/smoothness/jquery-ui-1.7.2.custom.css">
			
                        <style type="text/css" media="screen">/*
			 * Override styles needed due to the mix of three different CSS sources! For proper examples
			 * please see the themes example in the 'Examples' section of this site
			 */
			.dataTables_info { padding-top: 0; }
			.dataTables_paginate { padding-top: 0; }
			.css_right { float: right; }
			.example_wrapper .fg-toolbar { font-size: 0.8em }
			.theme_links span { float: left; padding: 2px 10px; }

		</style>

		<script type="text/javascript" src="<?php echo $baseurl;?>/tables/media/js/complete.js"></script>
		<script src="<?php echo $baseurl;?>js/jquery.min.js" type="text/javascript"></script>
                <script src="<?php echo $baseurl;?>tables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>tables/media/js/jquery.dataTables.editable.js"></script>
		<script src="<?php echo $baseurl;?>tables/media/js/jquery.jeditable.js" type="text/javascript"></script>
                <script src="<?php echo $baseurl;?>js/jquery-ui-git.js" type="text/javascript"></script>
                <script src="<?php echo $baseurl;?>tables/media/js/jquery.validate.js" type="text/javascript"></script>
        

     	        	
<script>
        $(document).ready(function () {
           myDT = $('#drafts').dataTable({   // <== Change the Id of the table 
                                      "bServerSide": true,
				      "iDisplayLength":13,
                                      "bJQueryUI": true,
                                      "bProcessing": true,
                                      "sPaginationType": "full_numbers",
                                      "aoColumnDefs": [
                                      { "sWidth": "10%", "aTargets": [ -1 ] }
                                                ],
				      "aaSorting":[[0,"desc"]],
					"oLanguage":{
					"sEmptyTable":"No Channels to display",
					"sProcessing": "Fetching Channel Information",
					"sZeroRecords":"No Channels Found"
							},
					"bAutoWidth":false,
                                      "sAjaxSource": "<?php echo $baseurl;?>/tables/editprop_ServerSide.php", //Fetch Data [ServerSide Script Path]
				      "aaSorting":[[0,"desc"]],
                                      "aoColumns":[  // Displayed columns [Number of columns]
                                         {"bVisible":false},
					 {},
					 {},
                                         {},
                                         {},
                                         {},
                                         {},      
                                         {},
                                         {}, 
                                         {},      
                                         {},
                                         {}, 
                                        
                                                                                                        
                                       ]
                                    }
                                    ).makeEditable({
			sDeleteURL: "<?php echo $baseurl;?>/tables/deleteData.php",
			sUpdateURL: "<?php echo $baseurl;?>/tables/UpdateData.php",  //Update Data [Update Data Server Side Script Path]
			sAddDeleteToolbarSelector: ".dataTables_length",
		        "aoColumns":[ // Edittable Columns [Number of Columns] and display Options
				{indicator: 'Edit Name...',tooltip: 'Click to change Name',type: 'text',submit:'Edit Name'},
                                {indicator: 'Edit Plot no...',tooltip: 'Click to plotno',type: 'text',submit:'Edit Plot'},	
                                {indicator: 'Edit Type...',tooltip: 'Click to change type',type: 'text',submit:'Edit Type'},
				{indicator: 'Edit Address...',tooltip: 'Click to change address',type: 'text',submit:'Edit Address'},
				{indicator: 'Edit Category...',tooltip: 'Click to change category',type: 'text',submit:'Edit category'},
				{indicator: 'Edit owner...',tooltip: 'Click to change property owner',type: 'text',submit:'Edit owner'},
                                {indicator: 'Editing Estate...',tooltip: 'Click to change Estate',type: 'text',submit:'Edit Estate'},
                                {indicator: 'Edit Water rate...',tooltip: 'Click to change water rate',type: 'text',submit:'Edit Rate'},
                                {},
                                {indicator: 'Edit acreage...',tooltip: 'Click to change acres',type: 'text',submit:'Edit acreage'},
                                {indicator: 'Editing Title...',tooltip: 'Click to change title',type: 'text',submit:'Edit Title Deed'},
                                
														],
                                  oDeleteRowButtonOptions: 
							{
							label: "Delete Record!",
                            icons: { primary: 'ui-icon-trash' }
                            }								
										});
			setInterval("myDT.fnDraw()",50000);
			});
			
	</script>
	
  	



<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="tabs" align="center">
<!-- Define Table Header with it's Id attribute <= (Important!!)' [<thead></thead>] and the table's header columns [<th></th>]'-->
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="drafts">
				<thead>
					<tr align="left" border = '1'>
                                                <th>P.ID</th>
						<th>Property.Name</th>
						<th>Plot.no</th>
						<th>Type</th>
						<th>Address</th>
						<th>Category</th>
						<th>Owner</th>
						<th>Estate</th>
						<th>Water Rate</th>
						<th>floors</th>
                                                <th>Acres</th>
                                                <th>Details</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
	
</div>



