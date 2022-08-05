<?php
@session_start();
include '../include/settings.php';
$clientType = $_SESSION['cType'];
$clientid = $_SESSION['clientID'];
$typeid=$_GET['typeid'];
?>

<html>
	<title>
		bulk | VIEW CHANNELS
	</title>
	<input id="typeid" value="<?php echo $typeid; ?>" type="hidden" />	

        	
<script>
        $(document).ready( function () {
			var typeid = $('#typeid').val();
			if (typeid == 2){
           $('#view_channs').dataTable({   // <== Change the Id of the table 
                                      "bServerSide": true,
				      "iDisplayLength":30,
                                      "bJQueryUI": true,
                                      "bProcessing": true,
                                      "sPaginationType": "full_numbers",
                                      "aaSorting":[[0,"desc"]],
				      "oLanguage":{
				      "sEmptyTable":"No Channels to display",
                                      "sProcessing": "Fetching Channel Information",
				      "sZeroRecords":"No Channels Found"
									},
									"bAutoWidth":false,
                                    "sAjaxSource": "./j/view_channels_serverSide.php?typeid="+typeid, //Fetch Data [ServerSide Script Path]
                                    "aoColumns":[  // Displayed columns [Number of columns]
                                         {"bVisible":false},
                                         {},
                                         {"sClass":"highlight-column"},
                                         {"sClass":"highlight-column"},
                                         {"sClass":"highlight-column"},
                                         {"sClass":"highlight-column"},
                                       ]
                                    }
									
                                    ).makeEditable({
											sDeleteURL: "./j/channDeleteData.php?typeid="+typeid,
										   	sUpdateURL: "./j/channUpdateData.php?typeid="+typeid,  //Update Data [Update Data Server Side Script Path]
										   	sAddDeleteToolbarSelector: ".dataTables_length",  //Toggle the delete button on and off
		                                	"aoColumns":[ // Edittable Columns [Number of Columns] and display Options
																	null,
																	{	indicator: 'Renaming Channel...',
																		tooltip: 'Double Click to edit Channel Name',
																		type: 'text',
																		submit:'Rename'
																	},
																	null,
																	{	indicator: 'Updating Keywords...',
																		tooltip: 'Double Click to edit Keywords',
																		type: 'text',
																		submit:'Update Keywords'
																	},
																	{	indicator: 'Updating Response...',
																		tooltip: 'Double Click to edit Response',
																		type: 'textarea',
																		submit:'Update Response'
																	}
														],
                                  oDeleteRowButtonOptions: 
									{
									label: "Remove",
									icons: { primary: 'ui-icon-trash' }
									}								
								});
			}else{
				$('#view_channs').dataTable({   // <== Change the Id of the table 
                                      "bServerSide": true,
                                      "bJQueryUI": true,
                                      "bProcessing": true,
				      "oLanguage":{
					"sEmptyTable":"No Channels to display",
				        "sProcessing": "Fetching Channel Information",
					"sZeroRecords":"No Channels Found"
				      },
                                      "sPaginationType": "full_numbers",
                                      "sAjaxSource": "./j/view_channels_serverSide.php?typeid="+typeid, //Fetch Data [ServerSide Script Path]
                                      "aoColumns":[  // Displayed columns [Number of columns]
                                         {"bVisible":false},
                                         {},
                                         {"sClass":"highlight-column"},
                                         {"sClass":"highlight-column"},
                                         {"sClass":"highlight-column"},
                                         {"sClass":"highlight-column"},
                                       ]
                                    }
                                    ).makeEditable({
											sDeleteURL: "./j/channDeleteData.php?typeid="+typeid,
										   	sUpdateURL: "./j/channUpdateData.php?typeid="+typeid,  //Update Data [Update Data Server Side Script Path]
										   	sAddDeleteToolbarSelector: ".dataTables_length",  //Toggle the delete button on and off
		                                	"aoColumns":[ // Edittable Columns [Number of Columns] and display Options
																	null,
																	{	indicator: 'Renaming Channel...',
																		tooltip: 'Double Click to edit Channel Name',
																		type: 'text',
																		submit:'Rename'
																	},
																	null,
																	{	indicator: 'Updating Keywords...',
																		tooltip: 'Double Click to edit Keywords',
																		type: 'text',
																		submit:'Update Keywords'
																	},
																	{	indicator: 'Updating Response...',
																		tooltip: 'Double Click to edit Response',
																		type: 'textarea',
																		submit:'Update Response'
																	}
														],
                                  oDeleteRowButtonOptions: 
							{
							label: "Remove",
                            icons: { primary: 'ui-icon-trash' }
                            }								
										
			} );
		}
	});
	</script>

	
	<body>
<?php $credits=obtainCredits($clientid); if ($credits <= 0){ ?>
<span class="sys-alert"><blink>Your credits have run out. Responses to subscriptions won't be sent out. Please Top up.</blink></span>
<?php } ?>
<h4>View / Configure <?php if ($clientType==3 and $typeid == 3){echo "Games";} elseif($clientType==3 and $typeid == 4){echo "Opinion Poll";} elseif ($clientType==3 and $typeid == 1){ echo "Default Channel";} else { echo "Channels";} ?></h4>
	    <span class=header-note> <i>Double click the highlighted fields to edit</i></span>
<div class='fieldseti'>
<!-- Define Table Header with it's Id attribute <= (Important!!)' [<thead></thead>] and the table's header columns [<th></th>]'-->
			<table cellpadding="0" cellspacing="0" border="0" class="display" id="view_channs">
				<thead>
					<tr align="left" border = '1'>
						<th>ID</th>
						<th>Date Of Creation</th>
						<th><?php if ($typeid==2 or ($clientType == 3 and $typeid == 1)){echo "Channel";} elseif($typeid==3){echo "Game";} else {echo "Opinion Poll";} ?> Name</th>
						<th>Number Of <?php if ($typeid==2){echo "Subs";} elseif($typeid==3 or $typeid == 4 or ($typeid == 1 and $clientType == 3)){echo "Participants";} ?></th>
						<th><?php if ($typeid==2){echo "Keyword";} elseif($typeid==3){echo "Game Key Words";} else {echo " Poll Options";} ?> Name</th>
						<th>Response</th>
						
					</tr>
				</thead>
				<tbody>

				</tbody>
			</table>
	
</div>
			<div class="spacer"></div>
</body>
</html>
