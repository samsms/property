<?php
session_start();
//die(print_r($_SESSION));
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'display.php';
include '../modules/functions.php';
///die(getPeriodByDate(date('d/m/Y')));
//die("dd");
checkIfLoggedInProperty();
$property =  getSettings();
echo  $htmlheaders;
echo '<head><title>' . $property['company_name'] . '| Jamar Properties</title>';
echo $meta;
echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $baseurl . 'css/overall2.css" />';
echo '<link rel="stylesheet" href="../css/jquery-ui.css">';
echo $jquery;
//later set variable to session
$admin = '' . $_SESSION['username'] . '';
date_default_timezone_set('Africa/Nairobi');

$user =  getUserById($_SESSION['userid']);

?>

<script>
    $(function() {
        $("#menu").menu();
    });
</script>
<link href="../css/overall2.css" rel="stylesheet" media="print" type="text/css" />
<script src="../js/logout.js"></script>

<script src="../js/ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="../js/jquery.PrintArea.js"></script>
<script type="text/javascript" src="../js/core.js"></script>

<script>
    $(document).ready(function() {

        //$('.chargeitem').keyup(function(){
        $(document).on("change", ".chargeitem", function() {
            var total = 0;
            $('.chargeitem').each(function() {
                //total += parseFloat($(this).val());
                if ($(this).val() == "") {
                    total = total + parseFloat(0);
                } else {
                    total = total + parseFloat($(this).val());
                }

            });
            $('#amount').val(total);
        });

        $("#newinvoice").css("display", "none"); //hide window on load
        $("#reverseinvoice").css("display", "none"); //hide window on load
        $("#printinvoice").css("display", "none");
        $(".printable").css("display", "none");
        $("#invoicelist").css("display", "none");
        $("#batchinvoicing").css("display", "none");
        $("#newclient").css("display", "none");
        $("#newreceipt").css("display", "none");
        $("#paymentslist").css("display", "none");
        $("#chequedate").css("display", "none");
        $("#cheqno").css("display", "none");
        $("#chequedetails").css("display", "none");
        $("#chequedaterecp").css("display", "none");
        $("#chequenorecp").css("display", "none");
        $("#chequedetailsrecp").css("display", "none");
        $("#printreceipt").css("display", "none");

        $('#accountsreceivable').change(function(e) {
            if ($("#accountsreceivable :selected").attr("id") == "invoicenew") {
                $("#newinvoice").show('body');
            }

        });
        var consump;
        var lastreading;
        var totalwater;
        var aptid;
        var rentamount;
        var itemsarray = [];
        var oldcount = [];
        var sum = 0;
        var total1 = 0;

        //on change of tenant name 
        $("#tenantnameinvoice").change(function(e) {
            $("#pendinginvoices").load("../modules/accountsprocess.php?pendinginvoices=true&tenantid=" + $("#tenantnameinvoice :selected").val());
            var totalvat = 0;
            var housevat = 0;
            var total = 0;


            tenantid = $("#tenantnameinvoice :selected").val();
            $("#balancebroughtforward").load("../modules/accountsprocess.php?getbalance=true&tenantid=" + tenantid);
            rentamount = $("#tenantnameinvoice :selected").attr("id");
            //using the title attribute of element,appaend the rent amount to appropriate input box
            $("#lastreading").val($("#tenantnameinvoice :selected").attr("title")); //set last reading to title attribute of option selected
            aptid = $("#tenantnameinvoice :selected").attr("class"); //selected option value
            //get the rent value of selected apartnment
            rent = $("#tenantnameinvoice :selected").attr("id");
            //assign to vat amount
            housevat = $("#housevat").val();
            if (isNaN(housevat)) {
                housevat = 0;
                totalvat = 0;
            } else {
                totalvat = parseInt((rent * housevat) / 100);
            }
            $("#vatamount").val(totalvat);

            $("#waterconsump").change(function(e) {
                consump = $('#waterconsump').val() - $("#lastreading").val(); //calculate months consumption
                totalwater = parseFloat(consump) * parseFloat($("#chargeperunit").val());
                //$("#waterconsump").attr("readonly",true);
                $("input[title='WATER']").val(totalwater); //assign to water input
                $("input[title='Water']").val(totalwater); //assign to water input
                $("input[title='water']").val(totalwater); //assign to water input
                //$("input[title='WATER']").attr("readonly",true);
                oldcount.push(totalwater); //push tototal amount array
                $('.chargeitem').each(function(e) {

                    if ($(this).val() != '') {
                        total1 = total1 + (parseFloat($(this).val()) || 0);
                    }
                });
                $('#amount').val(total1);

            });

            $('#amount').keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            }); //allow numeric values only

            //load chargeable items
            $("#chargeitems").load("../modules/accountsprocess.php?chargeitems=true" + "&propid=" + <?php echo $_SESSION['propertyid'] ?>, function(data) {
                $("input[title='RENT']").val(rentamount); //append rent amount
                $("input[title='Rent']").val(rentamount); //append rent amount
                $("input[title='rent']").val(rentamount); //append rent amount
                var id = $("input[id='chargeitemsarray']").val();
                var itemid;
                itemsarray = id.split("&");
                //for (i=0;i<(itemsarray.length)-1;i++){
                $(".chargeitem").change(function(e) {

                    var count = parseInt($(this).val());
                    $(this).attr("readonly", true);
                    $(this).attr("style", "border:1px solid green");
                    oldcount.push(count);
                    sum = oldcount.reduce(function(previousValue, currentValue) {
                        return currentValue + previousValue;
                    });
                    var total = parseInt(sum) + parseInt(rentamount) + parseInt(totalvat);
                    $("#amount").val(total);

                });
                //}    

            }); //for
        }); //tenant invoice change ftn
        var counter1 = 0;
        var k = 1;
        var chargeamountarray = [];
        var accountarray = [];
        var total = 0;
        $("#btnenewinvoice").click(function(e) {
            e.preventDefault();

            $('.chargeitem').each(function(e) {
                if ($(this).val() != '') {
                    total = total + parseInt($(this).val());
                }
                $('#amount').val(total);
            });
            //check if chargeable items contain value and push to array
            if ($("#vatamount").val() != 0) {
                accountarray.push('VAT');
                chargeamountarray.push($("#vatamount").val());
            }
            //for (i=0;i<(itemsarray.length)-1;i++){
            //  if($("input[id='chargeitem"+itemsarray[i]+"']").val()!=''){
            //    var value=$("input[id='chargeitem"+itemsarray[i]+"']").val();
            //   var accname=$("input[id='chargeitem"+itemsarray[i]+"']").attr("title");
            //add vat amount to chargeable if present

            //  accountarray.push(accname);chargeamountarray.push(value);
            //   counter1=k++; //keep counter of items in array
            // }}

            $('.chargeitem').each(function(e) {
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var accname = $(this).attr("title");
                    accountarray.push(accname);
                    chargeamountarray.push(value);
                    counter1 = k++; //keep counter of items in array
                }
            });
            chargeamnt = JSON.stringify(chargeamountarray); //alert(chargeamountarray);
            chargename = JSON.stringify(accountarray); //alert(accountarray);
            if ($('#tenantnameinvoice').val() == "" || $("#waterconsump").val() == "" || $('#invoicedate').val() == "" || $("#fperiod :selected").val() == "" || $('#incomeacct').val() == "" || total == "" || $("#invoiceform input[type='radio']:checked").val() == "") {
                $(".validateTips2").html("<font size='2' color='red'><center>All fields required!</center></font>");
                accountarray.length = 0;
                accname.length = 0;
                value.length = 0;
                chargeamountarray.length = 0;
                total = 0;
                return;
            } else {
                $("#btnenewinvoice").attr("disabled", "disabled");
                $("#btnenewinvoice").val('Processing...');
                $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "../modules/accountsprocess.php?newinvoice=true&idno=" + $("#tenantnameinvoice").val() + "&invoicedate=" + $("#invoicedate").val() + "&incomeaccount=" + $("#incomeacct").val() + "&billing=" + $("#invoiceform input[type='radio']:checked").val() + "&amount=" + total + "&remarks=" + $("#remarks").val() + "&chargenames=" + chargename + "&chargeamounts=" + chargeamnt + "&counter=" + counter1 + "&currentreading=" + $("#waterconsump").val() + "&fperiod=" + $("#fperiod :selected").val() + "&aptid=" + aptid + "&items=" + itemsarray + "&invoiceno=" + $("#invoiceno").val() + "&invoicebbf=" + $("#invoicebbf").val(),
                        data: "{}",
                        dataType: "json"
                    })

                    //var jqxhrpost = $.post( "../modules/accountsprocess.php?newinvoice=true&idno="+$("#tenantnameinvoice").val()+"&invoicedate="+$("#invoicedate").val()+"&incomeaccount="+$("#incomeacct").val()+"&billing="+$("#invoiceform input[type='radio']:checked").val()+"&amount="+total+"&remarks="+$("#remarks").val()+"&chargenames="+chargename+"&chargeamounts="+chargeamnt+"&counter="+counter1+"&currentreading="+$("#waterconsump").val()+"&fperiod="+$("#fperiod :selected").val()+"&aptid="+aptid+"&items="+itemsarray, function() {
                    //
                    //})
                    .done(function(data) {
                        alert('Invoice' + data.invoiceno + 'Created');
                        $("#btnenewinvoice").prop("disabled", false);
                        $("#btnenewinvoice").val("Create Invoice");
                        $(".validateTips2").html("<font size='2' color='green'><center>Successfully Created</center></font>");
                        $("#newinvoice").fadeOut("slow", function() {
                            $('#invoiceform')[0].reset();
                            $("input[type='text']").attr("style", "border:1px solid orange"); //reset border color
                            accountarray.length = 0;
                            chargeamountarray.length = 0;
                            oldcount.length = 0; //remove all elements from array
                            counter1 = 0;
                            k = 1;
                            $("input[type='text']").attr("readonly", false);
                        });
                        $("#waterconsump").attr("readonly", false); //remove readonly attribute from meter reading
                        window.open("../modules/defaultreports.php?report=printhillsinvoice&invoiceno=" + data.invoiceno);
                    })
                    .fail(function() {
                        $(".validateTips2").html("<font size='2' color='red'><center>Error in creating invoice</center></font>");
                        accountarray.length = 0;
                        chargeamountarray.length = 0;
                        oldcount.length = 0; //remove all elements from array
                        counter1 = 0;
                        k = 1;
                    });
            }
        });

        $("#btnenextinvoice").click(function(e) {
            e.preventDefault();

            $('.chargeitem').each(function(e) {
                if ($(this).val() != '') {
                    total = total + parseInt($(this).val());
                }
                $('#amount').val(total);
            });
            //check if chargeable items contain value and push to array
            for (i = 0; i < (itemsarray.length) - 1; i++) {
                if ($("input[id='chargeitem" + itemsarray[i] + "']").val() != '') {
                    var value = $("input[id='chargeitem" + itemsarray[i] + "']").val();
                    var accname = $("input[id='chargeitem" + itemsarray[i] + "']").attr("title");
                    accountarray.push(accname);
                    chargeamountarray.push(value);
                    counter1 = k++; //keep counter of items in array
                }
            }
            chargeamnt = JSON.stringify(chargeamountarray); //alert(chargeamountarray);
            chargename = JSON.stringify(accountarray); //alert(accountarray);
            if ($('#tenantnameinvoice').val() == "" || $("#waterconsump").val() == "" || $('#invoicedate').val() == "" || $("#fperiod :selected").val() == "" || $('#incomeacct').val() == "" || total == "" || $("#invoiceform input[type='radio']:checked").val() == "") {
                $(".validateTips2").html("<font size='2' color='red'><center>All fields required!</center></font>");
                accountarray.length = 0;
                accname.length = 0;
                value.length = 0;
                chargeamountarray.length = 0;
                total = 0;
                return;
                //clear fields here
            } else {
                $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "../modules/accountsprocess.php?newinvoice=true&idno=" + $("#tenantnameinvoice").val() + "&invoicedate=" + $("#invoicedate").val() + "&incomeaccount=" + $("#incomeacct").val() + "&billing=" + $("#invoiceform input[type='radio']:checked").val() + "&amount=" + total + "&remarks=" + $("#remarks").val() + "&chargenames=" + chargename + "&chargeamounts=" + chargeamnt + "&counter=" + counter1 + "&currentreading=" + $("#waterconsump").val() + "&fperiod=" + $("#fperiod :selected").val() + "&aptid=" + aptid + "&items=" + itemsarray + "&invoiceno=" + $("#invoiceno").val() + "&invoicebbf=" + $("#invoicebbf").val(),
                        data: "{}",
                        dataType: "json"
                    })

                    //var jqxhrpost = $.post( "../modules/accountsprocess.php?newinvoice=true&idno="+$("#tenantnameinvoice").val()+"&invoicedate="+$("#invoicedate").val()+"&incomeaccount="+$("#incomeacct").val()+"&billing="+$("#invoiceform input[type='radio']:checked").val()+"&amount="+total+"&remarks="+$("#remarks").val()+"&chargenames="+chargename+"&chargeamounts="+chargeamnt+"&counter="+counter1+"&currentreading="+$("#waterconsump").val()+"&fperiod="+$("#fperiod :selected").val()+"&aptid="+aptid+"&items="+itemsarray, function() {
                    // 
                    //})
                    .done(function(data) {
                        alert(data.status);
                        $(".validateTips2").html("<font size='2' color='green'><center>Successfully Created</center></font>");
                        $("#newinvoice").fadeOut("slow", function() {
                            $('#invoiceform')[0].reset();
                            $("input[type='text']").attr("style", "border:1px solid orange"); //reset border color
                            accountarray.length = 0;
                            chargeamountarray.length = 0;
                            oldcount.length = 0; //remove all elements from array
                            counter1 = 0;
                            k = 1;
                            $("input[type='text']").attr("readonly", false);
                        });
                        $("#waterconsump").attr("readonly", false); //remove readonly attribute from meter reading
                    })
                    .fail(function() {
                        $(".validateTips2").html("<font size='2' color='red'><center>Error in creating invoice</center></font>");
                        accountarray.length = 0;
                        chargeamountarray.length = 0;
                        oldcount.length = 0; //remove all elements from array
                        counter1 = 0;
                        k = 1;
                    });
            }
        });



        $("#closeinvoice").click(function(e) {
            e.preventDefault();
            $('#invoiceform')[0].reset();
            $("input[type='text']").attr("style", "border:1px solid orange"); //reset border color
            //accountarray.length=0;chargeamountarray.length=0;oldcount.length=0;//remove all elements from array
            counter1 = 0;
            k = 1;
            $("input[type='text']").attr("readonly", false);
            $("#waterconsump").attr("readonly", false);
            $("#newinvoice").css("display", "none");
            $("#accountsreceivable").val('');
        });

        $(function() {
            $("#invoicedate").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd/mm/yy'
            });
        });
        $(function() {
            $("#cheqdate").datepicker({
                changeMonth: true,
                changeYear: true,
            });
        });



        //delete invoice
        $('#accountsreceivable').change(function(e) {
            if ($("#accountsreceivable :selected").attr("id") == "invoicereverse") {
                e.preventDefault();
                $("#reverseinvoice").show('body');
            }

        });

        $("#closeinvoicerev").click(function() {

            $("#reverseinvoice").css("display", "none");
            $('#reverseinvoiceform')[0].reset();
            $('#divrevinvoice').empty();
            $("#accountsreceivable").val('');
        });
        $("#btnreverseinvoice").click(function(e) {
            e.preventDefault();
            $("#divrevinvoice").load("../modules/accountsprocess.php?reverseinvoice=true&invoiceno=" + $("#searchinvoice").val());

        });
        <?php if ($_SESSION['usergroup'] == 1) { ?>
            $('#divrevinvoice').on("click", "a[id='delinv']", function(event) {
                event.preventDefault();

                $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "../modules/accountsprocess.php?deleteinvoice=true&invoiceid=" + $("#delinv").attr("title"),
                        data: "{}",
                        dataType: "json"
                    })

                    .done(function() {
                        $(".validateTips3").html("<font size='2' color='green'><center>Successfully Deleted</center></font>");
                        $("#reverseinvoice").fadeOut("slow", function() {
                            $('#reverseinvoiceform')[0].reset();
                            $('#divrevinvoice').empty();
                        });

                    })
                    .fail(function() {
                        $(".validateTips3").html("<font size='2' color='red'><center>Error in deleting invoice</center></font>");
                    });

            });
        <?php } ?>
        //print and email invoice
        $('#accountsreceivable').change(function(e) {
            if ($("#accountsreceivable :selected").attr("id") == "printinv") {
                e.preventDefault();
                $("#printinvoice").show('body');
                // $("#addreason").show('body');
            }
        });
        $("#closeinvoiceprint").click(function(e) {
            e.preventDefault();
            $(".printable").css("display", "none");
            $("#printinvoice").fadeOut("slow", function() {
                $('#printinvoiceform')[0].reset();
                $("#printinvoice").css("display", "none");
                $("#accountsreceivable").val('');

            });

        });
        $("#btnprintinvoice").click(function(e) {
            e.preventDefault();
            $("#reverseinvoice").css("display", "none");
            $(".printable").show('body');
            window.open("../modules/defaultreports.php?report=printhillsinvoice&invoiceno=" + $("#searchinvoiceprint").val());

        });

        //invoicelist
        $('#accountsreceivable').change(function(e) {
            if ($("#accountsreceivable :selected").attr("id") == "invlist") {
                e.preventDefault();
                $("#invoicelist").show('body');
            }
        });
        $("#closeinvoicelist").click(function(e) {
            e.preventDefault();
            $("#invoicelist").fadeOut("slow", function() {
                $('#invoicelistform')[0].reset();
                $("#accountsreceivable").val('');
            });

        });
        $(function() {
            $("#startdatelist").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })
        $(function() {
            $("#enddatelist").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })

        $("#btninvoicelist").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=invoicelist&startdate=" + $("#startdatelist").val() + "&enddate=" + $("#enddatelist").val() + "&accid=" + $("#accnamelist").val() + "&accname=" + $('#accnamelist option:selected').text() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&status=" + $("#invoicestatus").val() + "&allpropertiesflag=" + $("#invoicelistform input[name='single']:checked").val());

        });
        //batchinvoicing
        $('#accountsreceivable').change(function(e) {
            if ($("#accountsreceivable :selected").attr("id") == "batch") {
                e.preventDefault();
                $("#batchinvoicing").show('body');
                $('#batchincomeacct').change(function() {
                    if ($('#batchincomeacct option:selected').val() == "all") {
                        $('#batchamount').css("display", "none");
                    }
                });
            }
        });

        $("#closebatch").click(function() {

            $("#batchinvoicing").css("display", "none");
            $("#batchinvoiceform")[0].reset();
            $("#accountsreceivable").val('');
        });
        /*$("#batchinvoicedate").datepicker({
        changeMonth: false,
        changeYear:false
        }); */
        //number
        $('#amountbatch').keyup(function() {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        });
        //change period on date change
        $("#invoicedate").change(function(e) {
            $("#getperioddiv").empty();
            $("#getperioddiv").load("../modules/accountsprocess.php?getfinancialperiod=true&date=" + $("#invoicedate").val());
        });

        //change period on date change
        $("#batchinvoicedate").change(function(e) {
            $("#getbatchperioddiv").empty();
            $("#getbatchperioddiv").load("../modules/accountsprocess.php?getfinancialperiodbatch=true&date=" + $("#batchinvoicedate").val());
        });
        //change period on expense date change
        $("#billdate").change(function(e) {
            $("#expenseperioddiv").empty();
            $("#expenseperioddiv").load("../modules/accountsprocess.php?getfinancialperiodexpense=true&date=" + $("#billdate").val());
        });
        //change paydate periods
        $("#paydate").change(function(e) {
            $("#payperioddiv").empty();
            $("#payperioddiv").load("../modules/accountsprocess.php?getfinancialperiodpay=true&date=" + $("#paydate").val());
        });

        $("#btnbatchinvoice").click(function(e) {
            e.preventDefault();
            if ($("#fperiodbatch option:selected").val() == "") {
                alert("Financial period Invalid");
                return;
            }
            if ($('#batchinvoicedate').val() == "" || $("#fperiodbatch option:selected").val() == "" || $('#batchincomeacct').val() == "" || $("#batchinvoiceform input[type='radio']:checked").val() == "") {
                $(".validateTips4").html("<font size='2' color='red'><center>All fields required!</center></font>");
            } else {
                $('.loaderimage').show('slow');
                $(":submit").attr("disabled", true);
                $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "../modules/accountsprocess.php?batch_all=" + $("#batch_all option:selected").val() + "&batchinvoice=true&invoicedate=" + $("#batchinvoicedate").val() + "&incomeaccount=" + $("#batchincomeacct").val() + "&billing=" + $("#batchinvoiceform input[type='radio']:checked").val() + "&amount=" + $('#batchamount').val() + "&remarks=" + $("#batchremarks").val() + "&fperiod=" + $("#fperiodbatch option:selected").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>,
                        data: "{}",
                        dataType: "json"
                    })

                    .done(function() {
                        $(".validateTips5").html("<font size='2' color='green'><center>Successfully Created</center></font>");
                        $('#batchinvoiceform')[0].reset();
                        $("#batchinvoicing").fadeOut("slow", function() {});
                        $(":submit").removeAttr("disabled");
                        $('.loaderimage').hide();

                    })
                    .fail(function() {
                        //$(".validateTips5").html("<font size='2' color='red'><center>Error in creating invoice</center></font>");
                        $(".validateTips5").html("<font size='2' color='green'><center>Successfully Created</center></font>");
                        $('.loaderimage').hide();
                    });

            }
        });

        //new client
        $("#newclienth").click(function(e) {
            $("#newclient").show('body');
            $("#validateclient").html("<font size='2' color='grey'><center>All fields required </center></font>");
            $(".radio1").prop('disabled', false);
            $(".radio2").prop('disabled', false);
            $("#cnametd").html('<input id="clientname" name="clientname"  style="width:245px; "/>')
        });
        //on close return default html properties
        $("#closeclient").click(function(e) {
            $("#newclient").css("display", "none");

            $('#newclientform')[0].reset();
        });
        $("#closeplotperformance").click(function(e) {
            $("#plotperformance").css("display", "none");

            $('#plotperformance')[0].reset();
        });
        $("#closefilterbypercentage").click(function(e) {
            $("#filterbypercentage").css("display", "none");

            $('#filterbypercentage')[0].reset();
        });

        $("#balancebypercentage").click(function(e) {
            $("#plotperformance").css("display", "none");

            $('#plotperformance')[0].reset();
        });

        $("#btncreateclient").click(function(e) {
            e.preventDefault();
            if ($('#clientname').val() === "" || $('#caddress').val() === "" || $('#ccity').val() === "" || $('#cphone').val() === "" || $("#newclientform input[type='radio']:checked").val() === "") {

            } else {
                if ($("#newclientform input[type='radio']:checked").val() === "0") {

                    $.ajax({
                            type: "POST",
                            contentType: "application/json; charset=utf-8",
                            url: "../modules/accountsprocess.php?createclient=true&clientname=" + $('#clientname').val() + "&address=" + $("#caddress").val() + "&email=" + $("#cemail").val() + "&city=" + $("#ccity").val() + "&clientphone=" + $("#cphone").val() + "&user=" + <?php echo $_SESSION['userid']; ?>,
                            data: "{}",
                            dataType: "json"
                        })



                        .done(function(data) {
                            $("#validateclient").html("<font size='2' color='green'><center>" + data.status + "</center></font>");

                            $("#newclient").fadeOut("slow", function() {
                                $('#newclientform')[0].reset();

                            });;
                        })
                        .fail(function() {
                            $("validateclient").html("<font size='2' color='red'><center>Error adding client</center></font>");
                        });
                } else {

                    $.ajax({
                            type: "POST",
                            contentType: "application/json; charset=utf-8",
                            url: "../modules/accountsprocess.php?editclient=true&clientid=" + $('#clientname1').val() + "&clientname=" + $('#clientname1 option:selected').text() + "&address=" + $("#caddress").val() + "&email=" + $("#cemail").val() + "&city=" + $("#ccity").val() + "&clientphone=" + $("#cphone").val(),
                            data: "{}",
                            dataType: "json"
                        })


                        .done(function(data) {
                            $("#validateclient").html("<font size='2' color='green'><center>" + data.status + "</center></font>");
                            $('#newclientform')[0].reset();
                            $("#newclient").fadeOut("slow", function() {});
                        })
                        .fail(function() {
                            $("validateclient").html("<font size='2' color='red'><center>Error saving client</center></font>");
                        });

                }
            }
        });
        //add edit client
        $("#newclientform input[type='radio']").click(function(e) {
            if ($("#newclientform input[type='radio']:checked").val() === "1") {
                $(".radio1").prop('disabled', true);
                $("#cnametd").html('<select id="clientname1"  name="clientname1" style="width:250px;">' + '<option selected="selected" value="">---</option>' + '<?php echo fetchallclients() ?>');
            } else {
                $(".radio2").prop('disabled', true);
            }
        });
        //load client details onchange
        $('#newclientform').on("change", "select[id='clientname1']", function(event) {
            var jqxhrpost = $.get("../modules/accountsprocess.php?clientdetails=true&id=" + $("#clientname1").val(), function() {

                })
                .done(function(data) {
                    $("#caddress").val(data.address);
                    $("#cemail").val(data.email);
                    $("#ccity").val(data.city);
                    $("#cphone").val(data.phone);
                })
                .fail(function() {
                    $(".validateTips4").html("<font size='2' color='red'><center>Error in fetching details</center></font>");
                });
        });

        //new receipt
        $("#receiptingactions").change(function(e) {
            if ($("#receiptingactions :selected").attr("id") == "receiptnew") {

                $("#newreceipt").show('body');
                $("#validaterecp").html("<center>Receive Payment</center>");
            } else if ($("#receiptingactions :selected").attr("id") == "printrecp") {
                $("#printreceipt").show('body');
            } else if ($("#receiptingactions :selected").attr("id") == "recpreverse") {
                $("#reversereceipt").show('body');
            } else if ($("#receiptingactions :selected").attr("id") == "recplist") {
                $("#receiptlist").show('body');
            } else if ($("#receiptingactions :selected").attr("id") == "cashmovement") {
                window.open("../modules/defaultreports.php?report=dailycash&date=" + $("#dailycashdate").val());
            }

        });
        $("#closereceipt").click(function(e) {
            $("#newreceipt").css("display", "none");
            $("#recepdetails").empty();
            $("#receiptingactions").val('');
            $('#newreceiptform')[0].reset();
        });

        $("#closereceipt").click(function(e) {
            $("#newreceipt").css("display", "none");
            $("#chequedate").css("display", "none");
            $("#cheqno").css("display", "none");
            $("#chequedetails").css("display", "none");
            $("#chequedaterecp").css("display", "none");
            $("#chequenorecp").css("display", "none");
            $("#chequedetailsrecp").css("display", "none");
            $('#newreceiptform')[0].reset();
        });

        //$("#recpdate").datepicker({ changeMonth: false,changeYear:false,dateFormat: 'dd/mm/yy' });                         

        $("#chequedaterecp").datepicker({
            changeMonth: true,
            changeYear: true
        });
        $('#recpamount').keyup(function() {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        });
        //number format on input box //expects '#handle'
        function number_format(handle) {
            $(handle).keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            });
        }
        var a = [];
        $('#tenantnamereceipt').change(function(e) {
            $("#loader").html("<img src='../images/loading.gif'/>");

            //penalty checked
            var penalize = 1;
            //apply penalty if not checked,default is not apply penalty
            <?php $penaltygl = getPenaltyGl(); ?>
            //  $("#accountspenalty").html("<select id='incomeacctpenalty'  name='incomeacctpenalty'  style='width:250px;'>"+ "<?php
                                                                                                                                echo "<option value='" . $penaltygl['glcode'] . "' >" . htmlspecialchars($penaltygl['acname']) . "</option>"; ?>//"+"</select>");
            //
            //    <?php $penaltygl = getPenaltyGl(); ?>
            //
            //   echo "<option value='".$penaltygl['glcode']."' >" . htmlspecialchars($penaltygl['acname']) . "</option>";?>//"+"</select>");



            //clear previously receipted amount
            $("#recpamount").val('');
            $("#recepdetails").load("../modules/accountsprocess.php?recpdetails=true&tenantid=" + $("#tenantnamereceipt").val(), function(data) {
                var id = $("input[id='invoicenos']").val();


                a = id.split("&");
                var count;
                var oldcount = [];
                var sum;
                for (i = 0; i < (a.length) - 1; i++) {
                    $("input[id='clientcheck" + a[i] + "']").click(function(e) {
                        count = parseInt($(this).attr("class"));

                        if ($(this).is(":checked")) {
                            $("input[id='payamount" + $(this).attr("title") + "']").val(count); //fill in partpayment inputs
                            $("input[id='payamount" + $(this).attr("title") + "']").attr("readonly", true); //make corresponding part payment input readonly

                            $(this).remove(); //remove from DOM
                            oldcount.push(count);
                            sum = oldcount.reduce(function(previousValue, currentValue) {
                                return currentValue + previousValue;
                            });
                        } else {
                            $("input[id='payamount" + $(this).attr("title") + "']").val('');
                            oldcount.pop(); //remove last element
                            if (oldcount.length === 0) {
                                sum = 0;
                            } else {
                                sum = oldcount.reduce(function(previousValue, currentValue) {
                                    return currentValue + previousValue;
                                });
                            }
                        }
                        $("#recpamount").val(sum);
                    });
                } //for     
                var oldcount1 = [];
                for (i = 0; i < (a.length) - 1; i++) {
                    $("input[id='payamount" + a[i] + "']").change(function(e) {
                        count = parseInt($(this).val());
                        //make partpayment read only
                        $(this).attr("readonly", true);
                        //automatically append a message to remarks
                        $("#remarksrecp").val("Part Payment for ");
                        oldcount1.push(count);
                        sum = oldcount1.reduce(function(previousValue, currentValue) {
                            return currentValue + previousValue;
                        });
                        $("#recpamount").val(sum);
                    });

                } //for    
                var amountarray = [];
                var invoicesarray = [];
                $("input[id='btnreceipt']").click(function(e) {
                    e.preventDefault();
                    if ($('#recpamount').val() === "" || $('#invoicenorecp').val() === "" || $('#clientnamerecp').val() === "" || $('#idnorecp').val() === "" || $('#recpdate').val() === "" || $('#paymoderecp').val() === "" || $("#paymode").val() === "" || $("#incomeacctrecp").val() === "" || $("#remarksrecp").val() === "") {
                        $("#validaterecp").html("<font size='2' color='red'><center>All fields required!</center></font>");
                    } else {
                        //loop through payamounts
                        var counter;
                        var k = 1;
                        for (i = 0; i < (a.length) - 1; i++) {
                            if ($("input[id='payamount" + a[i] + "']").val() > 0) {
                                value = $("input[id='payamount" + a[i] + "']").val();
                                amountarray.push(value);
                                invoiceno = $("input[id='payamount" + a[i] + "']").attr("title");
                                invoicesarray.push(invoiceno);
                                counter = k++; //keep counter of items in array
                            }
                        }
                        inv = JSON.stringify(invoicesarray);
                        amt = JSON.stringify(amountarray); //change to php array
                        //alert('counter array contains: '+invoicesarray+" counter is "+counter);//now post all variables and invoices paid as array,run update_invoice according to counter,
                        var penalize = 1;
                        //apply penalty if not checked,default is not apply penalty
                        if ($("#penalize:checkbox:checked").length > 0) {
                            penalize = 0;
                        }
                        $.ajax({
                                type: "POST",
                                contentType: "application/json; charset=utf-8",
                                url: "../modules/accountsprocess.php?receiptclient=true&invoicenosarray=" + inv + "&counter=" + counter + "&recpamountarray=" + amt + "&tenantid=" + $('#tenantnamereceipt').val() + "&receiptdate=" + $('#recpdate').val() + "&paymode=" + $('#paymoderecp').val() + "&cashacct=" + $("#incomeacctrecp").val() + "&chequeacct=" + $("#incomeacctrecp").val() + "&chequedate=" + $("#chequedaterecp").val() + "&chequeno=" + $("#chequenorecp").val() + "&chequedetails=" + $("#chequedetailsrecp").val() + "&remarks=" + $("#remarksrecp").val() + "&applypenalty=" + penalize + "&penaltygl=" + $('#incomeacctpenalty option:selected').val() + "&fperiod=" + $('#fperiodrecp option:selected').val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&bankdeposit=" + $("#bankdeposit").val() + "&reference=" + $("#transactionreference").val(),
                                data: "{}",
                                dataType: "json"
                            })

                            // var post = $.get( "../modules/accountsprocess.php?receiptclient=true&invoicenosarray="+inv+"&counter="+counter+"&recpamountarray="+amt+"&tenantid="+$('#tenantnamereceipt').val()+"&receiptdate="+$('#recpdate').val()+"&paymode="+$('#paymoderecp').val()+"&cashacct="+$("#incomeacctrecp").val()+"&chequeacct="+$("#incomeacctrecp").val() +"&chequedate="+$("#chequedaterecp").val()+"&chequeno="+$("#chequenorecp").val()+"&chequedetails="+$("#chequedetailsrecp").val()+"&remarks="+$("#remarksrecp").val()+"&applypenalty="+penalize+"&fperiod="+$('#fperiodrecp option:selected').val()+"&propid="+<?php echo $_SESSION['propertyid'] ?>, function() {})

                            .done(function(data) {
                                alert('Receipt ' + data.status + ' generated');
                                window.open("../modules/defaultreports.php?report=printreceipt&receiptno=" + data.status);
                                $('#newreceiptform')[0].reset();
                                $("#recepdetails").empty();

                            })
                            .fail(function() {
                                $(".validateTips4").html("<font size='2' color='red'><center>Error in fetching details</center></font>");
                            });
                        $('#newreceiptform')[0].reset();
                        $("#recepdetails").empty();
                        $("#newreceipt").css("display", "none");
                    }
                });

            });

        });
        //payment modes in receipt
        $("#paymoderecp").change(function() {
            $value = $("input[name='receivemoney']:checked").val();
            //if receiving money from other customer
            <?php $incomegls = getAgentIncomeGls();  ?>
            if ($value == "other") {
                if ($("#paymoderecp").val() === "1" || $("#paymoderecp").val() === "2") {
                    $("#paymode option[value='1']").attr("selected", "selected");
                    $("#paymode option[value='0']").attr("disabled", "disabled"); //cheque enabled
                    $("#chequedate").css("display", "block");
                    $("#cheqno").css("display", "block");
                    $("#chequedetails").css("display", "block");
                    $("#chequedaterecp").css("display", "block");
                    $("#chequenorecp").css("display", "block");
                    $("#chequedetailsrecp").css("display", "block");
                    $("#accountsrecp").html("<select id='incomeacctrecp'  name='incomeacctrecp'  style='width:250px;'>" +
                        "<?php foreach ($incomegls as $value) {
                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                            } ?>" +
                        "</select>");
                }
                //if cash
                else if ($("#paymoderecp").val() === "0") {
                    $("#banks").css("display", "none");
                    $("#chequedate").css("display", "none");
                    $("#cheqno").css("display", "none");
                    $("#chequedetails").css("display", "none");
                    $("#chequedaterecp").css("display", "none");
                    $("#chequenorecp").css("display", "none");
                    $("#chequedetailsrecp").css("display", "none");
                    $("#paymode option[value='0']").attr("selected", "selected");
                    $("#paymode option[value='1']").attr("disabled", "disabled"); //autoselect payment mode
                    $("#accountsrecp").html("<select id='incomeacctrecp'  name='incomeacctrecp'  style='width:250px;'>" +
                        "<?php foreach ($incomegls as $value) {
                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                            } ?>" +
                        "</select>");
                }
                //if bank deposit
                else if ($("#paymoderecp").val() === "3") {
                    <?php $allbanks =  getBanks();  ?>
                    $("#chequedate").css("display", "none");
                    $("#cheqno").css("display", "none");
                    $("#chequedetails").css("display", "none");
                    $("#chequedaterecp").css("display", "none");
                    $("#chequenorecp").css("display", "none");
                    $("#chequedetailsrecp").css("display", "none");
                    $("#paymode option[value='0']").attr("selected", "selected");
                    $("#paymode option[value='1']").attr("disabled", "disabled"); //autoselect payment mode
                    $("#banks").html("<select id='bankdeposit'  name='bankdeposit'  style='width:250px;'>" + "<?php foreach ($allbanks as $value) {
                                                                                                                    echo "<option value='" . $value['id'] . "' >" . htmlspecialchars($value['bank_name']) . "</option>";
                                                                                                                } ?>" + "</select>");
                    $("#accountsrecp").html("<select id='incomeacctrecp'  name='incomeacctrecp'  style='width:250px;'>" + "<?php foreach ($incomegls as $value) {
                                                                                                                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                                                                                                                            } ?>" +
                        "</select>");
                } else {
                    $("#chequedate").css("display", "none");
                    $("#cheqno").css("display", "none");
                    $("#chequedetails").css("display", "none");
                    $("#chequedaterecp").css("display", "none");
                    $("#chequenorecp").css("display", "none");
                    $("#chequedetailsrecp").css("display", "none")
                }
            } else {
                //if payment mode is cheque/credit card
                <?php $gls = getLandlordGls($_SESSION['propertyid']);  ?>
                <?php $bankgls = getBanksGls();  ?>
                if ($("#paymoderecp").val() === "1" || $("#paymoderecp").val() === "2") {
                    $("#banks").css("display", "none");
                    $("#paymode option[value='1']").attr("selected", "selected");
                    $("#paymode option[value='0']").attr("disabled", "disabled"); //cheque enabled
                    $("#chequedate").css("display", "block");
                    $("#cheqno").css("display", "block");
                    $("#chequedetails").css("display", "block");
                    $("#chequedaterecp").css("display", "block");
                    $("#chequenorecp").css("display", "block");
                    $("#chequedetailsrecp").css("display", "block");
                    $("#accountsrecp").html("<select id='incomeacctrecp'  name='incomeacctrecp'  style='width:250px;'>" + "<?php foreach ($gls as $value) {
                                                                                                                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                                                                                                                            } ?>" +
                        "<?php foreach ($bankgls as $value) {
                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                            } ?>" +
                        "</select>");
                }
                //if cash
                else if ($("#paymoderecp").val() === "0") {
                    $("#banks").css("display", "none");
                    $("#chequedate").css("display", "none");
                    $("#cheqno").css("display", "none");
                    $("#chequedetails").css("display", "none");
                    $("#chequedaterecp").css("display", "none");
                    $("#chequenorecp").css("display", "none");
                    $("#chequedetailsrecp").css("display", "none");
                    $("#paymode option[value='0']").attr("selected", "selected");
                    $("#paymode option[value='1']").attr("disabled", "disabled"); //autoselect payment mode
                    $("#accountsrecp").html("<select id='incomeacctrecp'  name='incomeacctrecp'  style='width:250px;'>" + "<?php foreach ($gls as $value) {
                                                                                                                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                                                                                                                            } ?>" +
                        "<?php foreach ($bankgls as $value) {
                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                            } ?>" +
                        "</select>");
                }
                //if bank deposit
                else if ($("#paymoderecp").val() === "3") {
                    <?php $allbanks =  getBanks();  ?>
                    $("#banks").css("display", "block");
                    $("#chequedate").css("display", "none");
                    $("#cheqno").css("display", "none");
                    $("#chequedetails").css("display", "none");
                    $("#chequedaterecp").css("display", "none");
                    $("#chequenorecp").css("display", "none");
                    $("#chequedetailsrecp").css("display", "none");
                    $("#paymode option[value='0']").attr("selected", "selected");
                    $("#paymode option[value='1']").attr("disabled", "disabled"); //autoselect payment mode

                    $("#banks").html("<select id='bankdeposit'  name='bankdeposit'  style='width:250px;'>" + "<?php foreach ($allbanks as $value) {
                                                                                                                    echo "<option value='" . $value['id'] . "' >" . htmlspecialchars($value['bank_name']) . " : " . $value['property_name'] . "</option>";
                                                                                                                } ?>" + "</select>");

                    $("#accountsrecp").html("<select id='incomeacctrecp'  name='incomeacctrecp'  style='width:250px;'>" + "<?php foreach ($gls as $value) {
                                                                                                                                echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                                                                                                                            } ?>" + "<?php foreach ($bankgls as $value) {
                                                                                                                                            echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                                                                                                                                        } ?>" +
                        "</select>");
                } else {
                    $("#chequedate").css("display", "none");
                    $("#cheqno").css("display", "none");
                    $("#chequedetails").css("display", "none");
                    $("#chequedaterecp").css("display", "none");
                    $("#chequenorecp").css("display", "none");
                    $("#chequedetailsrecp").css("display", "none");
                    $("#banks").css("display", "none");
                }
            }
        });
        //print and email receipt

        $("#closereceiptprint").click(function(e) {
            e.preventDefault();
            $(".printable").css("display", "none");
            $("#printreceipt").fadeOut("slow", function() {
                $('#printreceiptform')[0].reset();
                $("#printreceipt").css("display", "none");
                $("#receiptingactions").val('');

            });

        });
        $("#btnprintreceipt").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=printreceipt&receiptno=" + $("#searchreceiptprint").val());

        });

        $("#btnprintreceiptother").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=printreceiptcustomer&receiptno=" + $("#searchreceiptprint").val());

        });

        //delete receipt
        $("#reversereceipt").css("display", "none"); //on DOM hide div

        $("#closereceiptrev").click(function() {

            $("#reversereceipt").css("display", "none");
            $('#reversereceiptform')[0].reset();
            $('#divrevreceipt').empty();
            $("#receiptingactions").val('');
        });
        $("#btnreversereceipt").click(function(e) {
            e.preventDefault();
            $("#divrevreceipt").load("../modules/accountsprocess.php?reversereceipt=true&receipno=" + $("#searchrecp").val());

        });

        $("#btnreverseotherreceipt").click(function(e) {
            e.preventDefault();
            $("#divrevreceipt").load("../modules/accountsprocess.php?reverseotherreceipt=true&receipno=" + $("#searchrecp").val());

        });

        $('#divrevreceipt').on("click", "a[id='delreceipt']", function(event) {
            event.preventDefault();
            $("#reverserecp").html("<center>deleting..<img src='../images/loading.gif'/></center>");
            $.ajax({
                    type: "POST",
                    contentType: "application/json; charset=utf-8",
                    url: "../modules/accountsprocess.php?deletereceiptaction=true&receiptid=" + $("#delreceipt").attr("title") + "&type=" + $("#delreceipt").attr("tabindex"),
                    data: "{}",
                    dataType: "json"
                })

                .done(function(data) {
                    $("#reverserecp").html("<font size='2' color='green'><center>" + data.status + "</center></font>");
                    $("#reversereceipt").fadeOut("slow", function() {
                        $('#reverseinvoiceform')[0].reset();
                        $('#divrevreceipt').empty();
                    });

                })
                .fail(function() {
                    $("#reverserecp").html("<font size='2' color='red'><center>Error in deleting receipt</center></font>");
                });

        });

        //receiptlist
        $("#receiptlist").css("display", "none");

        $("#closerecplist").click(function(e) {
            e.preventDefault();
            $("#receiptlist").fadeOut("slow", function() {
                $('#recplistform')[0].reset();
                $("#receiptingactions").val('');
            });

        });
        $(function() {
            $("#startdaterecp").datepicker({
                changeMonth: true,
                changeYear: true
            });
        });
        $(function() {
            $("#enddaterecp").datepicker({
                changeMonth: true,
                changeYear: true
            });
        });

        $("#btnrecplist").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=receiptlist&startdate=" + $("#startdaterecp").val() + "&enddate=" + $("#enddaterecp").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&allpropertiesflag=" + $("#recplistform input[name='receiptproperties']:checked").val() + "&receiptype=" + $("#recplistform input[name='receiptsradio']:checked").val() + "&tenant=" + $("#tenantfilter").val());

        });
        //deposit list

        $("#btndepositlist").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=tenantdeposits&startdate=" + $("#startdatedeposit").val() + "&enddate=" + $("#enddatedeposit").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&allpropertiesflag=" + $("#depositlistform input[name='receiptproperties']:checked").val());

        });

        $('#closedeposits').click(function(e) {
            $('#depositlist').css("display", "none");
        });






        //hide div on DOM load
        $('#tstmnt').css("display", "none");
        $('#closestatement').click(function(e) {
            $('#tstmnt').css("display", "none");
        });
        $('#closeprepayment').click(function(e) {
            $('#prepayment-report').css("display", "none");
        });
        $("#statements").change(function(e) {
            if ($("#statements :selected").attr("id") == "tenantstatement") {
                $('#tstmnt').show('body');
            } else if ($("#statements :selected").attr("id") == "prepayment") {
                $('#prepayment-report').show('body');
            } else if ($("#statements :selected").attr("id") == "incomestatement") {
                $('#incomestmnt').show('body');
            } else if ($("#statements :selected").attr("id") == "deplist") {
                $("#depositlist").toggle();
            } else if ($("#statements :selected").attr("id") == "landlordstatement") {
                $('#landlordstatementdiv').toggle();
            } else if ($("#addreason :selected").attr("id") == "addreason") {
                $('#addreason').toggle();
            } else if ($("#statements :selected").attr("id") == "agentstatement") {
                $('#agentstatementdiv').toggle();
            } else if ($("#statements :selected").attr("id") == "commissionsrep") {
                $("#commissionswindow").toggle();
            } else if ($("#statements :selected").attr("id") == "arrearsprepayments") {
                $('#arrearsprep').toggle();
            } else if ($("#statements :selected").attr("id") == "performance") {
                // alert("perform");
                $('#plotperformance').toggle();
            } else if ($("#statements :selected").attr("id") == "filterbypercentageopt") {
                // alert("perform");
                $('#filterbypercentage').toggle();
            } else if ($("#statements :selected").attr("id") == "penalties") {
                $('#penaltiesdiv').toggle();
            }
        });
        $("#fromdate").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $("#todate").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $(function() {
            $(".datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd/mm/yy'
            });
        });
        $("#btnfetchstatement").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=fetchstatement&startdate=" + $("#fromdate").val() + "&enddate=" + $("#todate").val() + "&clientid=" + $("#clientnamestmnt :selected").val() + "&count=" + $("#newstatementform input[type='radio']:radio:checked").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);

        });
        $("#btnreportprepayment").click(function(e) {

        // console.log($("#prepayment-select option:selected").text());
        //     return false
            var jqxhrpost = $.get("../modules/accountsprocess.php?report_prepayment=true&id=" + $("#prepayment-select option:selected").text(), function() {

                })
                .done(function(data) {
                  alert(data.msg);
                });


return false;





        });
        //income statement
        $('#incomestmnt').css("display", "none");
        $('#closeincomestatement').click(function(e) {
            $('#incomestmnt').css("display", "none");
        });

        $("#fromdateinc").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $("#fromdateperformance").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $("#fromdatefilterbypercentage").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $("#todatefilterbypercentage").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $("#todateperformance").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $("#todateinc").datepicker({
            changeMonth: true,
            changeYear: true,
        });
        $("#btnfetchincstatement").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=incomestatement&startdate=" + $("#fromdateinc").val() + "&enddate=" + $("#todateinc").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);

        });
        //hide div on DOM load
        $('#acctlist').css("display", "none");
        $('#closeacctlist').click(function(e) {
            $('#acctlist').css("display", "none");
        });
        $("#chartofaccountss").change(function(e) {
            if ($("#chartofaccountss :selected").attr("id") == "accountslist1") {

                $('#acctlist').show('body');
                $('#bkaccts').load("../modules/accountsprocess.php?accountdetails=true", function(e) {
                    //view statement
                    $("#viewstatement").click(function(e) {
                        e.preventDefault();
                        window.open("../modules/defaultreports.php?report=fetchaccountstatement&fromdate=" + $("#fromdatestatement").val() + "&todate=" + $("#todatestatement").val() + "&account=" + $("#statementform input[name='statementradio']:radio:checked").val() + "&acctype=" + $("#statementform input[name='statementradio']:radio:checked").attr("title"));

                    });
                });

            } else if ($("#chartofaccountss :selected").attr("id") == "expenseaccounts")
                window.location.replace("../views/template.php?page=expenseaccounts");
        });
        //hide div on load
        $('#newsupplier').css("display", "none");
        $('#supplier').click(function(e) {
            $('#newsupplier').show('body');
        });
        //add edit supplier
        $("#newsupplierform input[name='addeditsupplier']").click(function(e) {
            if ($("#newsupplierform input[name='addeditsupplier']:checked").val() === "1") {
                $(".radio1").prop('disabled', true);
                $("#snametd").html('<select id="suppliername1"  name="suppliername1" style="width:250px;">' + '<option selected="selected" value="">---</option>' + '<?php echo fetchallsuppliers($_SESSION['propertyid']); ?>');
            } else {
                $(".radio2").prop('disabled', true);
            }
        });
        //load supplier details onchange
        $('#newsupplierform').on("change", "select[id='suppliername1']", function(event) {
            var jqxhrpost = $.get("../modules/accountsprocess.php?supplierdetails=true&id=" + $("#suppliername1").val(), function() {

                })
                .done(function(data) {
                    $("#suppitems").val(data.items);
                    $("#saddress").val(data.address);
                    $("#semail").val(data.email);
                    $("#scity").val(data.city);
                    $("#sphone").val(data.phone);
                })
                .fail(function() {
                    $(".validateTips4").html("<font size='2' color='red'><center>Error in fetching details</center></font>");
                });
        });
        //on close return default html properties
        $("#closesupplier").click(function(e) {
            $("#newsupplier").css("display", "none");
            $('#newsupplierform')[0].reset();
            $('#newsupplierform input[type="radio"]').prop("checked", false);
        });
        //save supplier
        $("#btncreatesupplier").click(function(e) {
            e.preventDefault();
            if ($('#suppliername').val() === "" || $('#saddress').val() === "" || $('#scity').val() === "" || $('#sphone').val() === "" || $("#newsupplierform input[type='radio']:checked").val() === "") {

            } else {
                if ($("#newsupplierform input[type='radio']:checked").val() === "0") {

                    $.ajax({
                            type: "POST",
                            contentType: "application/json; charset=utf-8",
                            url: "../modules/accountsprocess.php?createsupplier=true&suppliername=" + $('#suppliername').val() + "&items=" + $('#suppitems').val() + "&address=" + $("#saddress").val() + "&email=" + $("#semail").val() + "&city=" + $("#scity").val() + "&supplierphone=" + $("#sphone").val() + "&user=" + <?php echo $_SESSION['userid']; ?> + "&property_id=" + <?php echo $_SESSION['propertyid']; ?>,
                            data: "{}",
                            dataType: "json"
                        })


                        .done(function(data) {
                            $("#validatesupplier").html("<font size='2' color='green'><center>" + data.status + "</center></font>");

                            $("#newsupplier").fadeOut("slow", function() {
                                $('#newsupplierform')[0].reset();
                                location.reload(true); //reload window
                            });;
                        })
                        .fail(function() {
                            $("validatesupplier").html("<font size='2' color='red'><center>Error adding supplier</center></font>");
                        });
                } else {
                    $.ajax({
                            type: "POST",
                            contentType: "application/json; charset=utf-8",
                            url: "../modules/accountsprocess.php?editsupplier=true&supplierid=" + $('#suppliername1').val() + "&suppliername=" + $('#suppliername1 option:selected').text() + "&items=" + $('#suppitems').val() + "&address=" + $("#saddress").val() + "&email=" + $("#semail").val() + "&city=" + $("#scity").val() + "&supplierphone=" + $("#sphone").val(),
                            data: "{}",
                            dataType: "json"
                        })


                        .done(function(data) {
                            $("#validatesupplier").html("<font size='2' color='green'><center>" + data.status + "</center></font>");
                            $('#newsupplierform')[0].reset();
                            $("#newsupplier").fadeOut("slow", function() {});
                            location.reload(true); //reload page
                        })
                        .fail(function() {
                            $("validatesupplier").html("<font size='2' color='red'><center>Error saving supplier</center></font>");
                        });

                }
            }
        });
        //hide bill
        $('#newsbill').css("display", "none");
        $('#closebill').click(function(e) {
            $('#newsbill').css("display", "none");
            $('#newsbillform')[0].reset();
            $("#accountspayable").val('');
        });
        number_format('#owedamount'); //format input owed amount to accept numbers only
        $('#accountspayable').change(function(e) {
            if ($("#accountspayable :selected").attr("id") == "suppbill") {
                $('#newsbill').show();
                $("#validatebill").html("All fields required");
            }
        });

        //office or landlord expense
        $(".expensetype").change(function(e) {
            if ($(".expensetype:checked").val() == 0) {
                $(".expenseentry").load("../modules/accountsprocess.php?getofficeexpense=true");
            } else {
                $(".expenseentry").load("../modules/accountsprocess.php?getpropertyexpense=true");
            }
        });

        //on change of supplied items
        $("#supplieditems").change(function() {
            $("#billremarks").text($("#supplieditems").val());
        })

        //default message on supplieditems
        var defaultMessage = "Start typing..";
        $("#supplieditems").focus(function() {
            if ($(this).val() === defaultMessage) {
                $(this).val("");
            }
        }).blur(function() {
            if ($(this).val() === "") {
                $(this).val(defaultMessage);
            }
        }).val(defaultMessage);
        //receivebill
        //if item is being charged vat
        var totalvalue;
        $("#owedamount").keyup(function(e) {
            value = $(this).val();
            //if selected expense entry is charged vat
            if ($("#supplliername :selected").attr("tab-index") == 1) {
                totalvalue = parseInt(value) + parseInt(0.16 * value);
                $("#totalcharges").val(totalvalue);
            } else {
                totalvalue = value;
                $("#totalcharges").val(value);
            }

        })

        $("#feepercent").keyup(function(e) {
            value = $(this).val();
            $("#totalcharges").val(parseInt(totalvalue + (value / 100) * totalvalue));


        })


        $("#btnreceivebill").click(function(e) {
            e.preventDefault();
            //suppliername,billdate,supplieditems,owedamount,btnreceivebill 
            if ($("#supplliername :selected").val() == "" || $('#supplieditems').val() == "" || $('#billdate').val() == "" || $('#owedamount').val() == "") {
                $("#validatebill").html("<font size='2' color='red'><center>All fields required!</center></font>");
            } else {
                $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "../modules/accountsprocess.php?newbill=true&suppid=" + $("#supplliername :selected").text() + "&billdate=" + $("#billdate").val() + "&billitems=" + $("#supplieditems").val() + "&owedamount=" + $("#owedamount").val() + "&billamount=" + $("#totalcharges").val() + "&feepercent=" + $("#feepercent").val() + "&agentincome=" + $("#agentlandlordexpenseincome :selected").val() + "&remarks=" + $("#billremarks").val() + "&glcode=" + $("#supplliername :selected").val() + "&fperiod=" + $("#expenseperiod :selected").val() + "&propid=" + $("#supplliername :selected").attr("title"),
                        data: "{}",
                        dataType: "json"
                    })

                    .done(function(data) {
                        $("#validatebill").html("<font size='2' color='green'><center>Bill no <u>" + data.status + "</u> Created</center></font>");
                        alert('Bill No ' + data.status + ' created')
                        $('#newsbillform')[0].reset();

                    })
                    .fail(function() {
                        $("#validatebill").html("<font size='2' color='red'><center>Error in creating bill</center></font>");
                    });
            }

        });
        //hide on DOM load
        $('#paybill').css("display", "none");
        $('#closepbill').click(function(e) {
            $('#paybill').css("display", "none");
            $('#newpbillform')[0].reset();
            $("#pendingbills").empty(); //empty div
            $('#billnos').val('');
            $("#accountspayable").val('');
        });
        $('#accountspayable').change(function(e) {
            if ($("#accountspayable :selected").attr("id") == "paysbill") {

                $('#paybill').show('body');
                $("#validatebill").html("All fields required");
            }
        });
        $("#chequedetailsbill").css("display", "none");
        $(function() {
            $("#chequedatebill").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });
        });
        //payment modes in supplier pay bill
        $("#paymodebill").change(function() {
            //cash or cheque
            if ($("#paymodebill").val() === "1" || $("#paymodebill").val() === "2") {
                $("#paymode option[value='1']").attr("selected", "selected");
                $("#paymode option[value='0']").attr("disabled", "disabled"); //cheque enabled
                $("#chequedate").css("display", "block");
                $("#cheqnobill").css("display", "block");
                $("#chequedetails").css("display", "block");
                $("#chequedetailsbill").css("display", "block");
                $("#chequedetailslabel").css("display", "block");
                $("#chequenotd").css("display", "block");
                $("#accountsbill").html("<select id='expenseacctbill'  name='expenseacctbill'  style='width:250px;'>" + "<?php echo getexpenseaccount() ?></select>");
            }
            //if payment mode is cash or bank deposit
            else if ($("#paymodebill").val() === "0" || $("#paymodebill").val() === "3") {
                <?php $bankspay =  getBanks(1); ?>
                $("#paybillbanks").html(
                    "<select id='bankdepositpay'  name='bankdepositpay'  style='width:250px;'><option value='0' selected='selected'>Select Cash Account</option>" + "<?php foreach ($bankspay as $value) {
                                                                                                                                                                            echo "<option value='" . $value['id'] . "' >" . htmlspecialchars($value['bank_name']) . "</option>";
                                                                                                                                                                        } ?>" + "</select>");
                $("#chequedate").css("display", "none");
                $("#cheqnobill").css("display", "none");
                $("#chequedetails").css("display", "none");
                $("#chequedatebill").css("display", "none");
                $("#chequenobill").css("display", "none");
                $("#chequedetailsbill").css("display", "none");
                $("#chequedetailslabel").css("display", "none");
                $("#chequenotd").css("display", "none");
                $("#paymode option[value='0']").attr("selected", "selected");
                $("#paymode option[value='1']").attr("disabled", "disabled"); //autoselect payment mode
                $("#accountsbill").html("<select id='expenseacctbill'  name='expenseacctbill'  style='width:250px;'>" + "<?php echo getexpenseaccount() ?></select>");
            } else {
                $("#chequedate").css("display", "none");
                $("#cheqnobill").css("display", "none");
                $("#chequedetails").css("display", "none");
                $("#chequedaterecp").css("display", "none");
                $("#chequenobill").css("display", "none");
                $("#chequedetailsbill").css("display", "none");
                $("#chequedetailslabel").css("display", "none");
                $("#chequenotd").css("display", "none");
            }
        });
        //load pending bills from supplier
        var count;
        var amount = 0;
        var billnum = '';
        var sequencepay = '';
        $('#suppliernameselect').change(function() {
            $("#payamountbill").val(''); //emptyamount field
            $("#billnumbers").val('');
            $("#sequencepayments").val(''); //empty fields
            amount = 0;
            billnum = '';
            sequencepay = '';
            $("#pendingbills").load("../modules/accountsprocess.php?billdetails=true&suppid=" + $("#suppliernameselect :selected").val(), function(data) {
                countstring = $("#billcount").val();
                count = countstring.split('&');
                for (i = 0; i <= count.length; i++) {
                    $("#pendingbills input[id='billcheck" + count[i] + "']").click(function() {
                        if ($(this).is(":checked")) {
                            $(this).css("display", "none"); //hide checkboxes after payment to prevent hydrating amount field again
                            amount = amount + parseFloat($(this).attr("class"));
                            sequencepay = sequencepay + $(this).attr("class") + '*'; //get the sequential paid amounts
                            billnum = billnum + $(this).attr("title") + '*';
                            $("#sequencepayments").val(sequencepay);
                            $("#billnos").val(billnum);
                            $("#payamountbill").val(amount);
                        }
                    });
                } //for
            }); //load fn
        });
        $("#btnpaybill").click(function(e) {
            e.preventDefault();
            if ($('#suppliernameselect').val() === "" || $('#paymodebill').val() === "" || $('#payamountbill').val() === "" || $('#billnoss').val() === "" || $('#sequencepayments').val() === "" || $('#remarksbill').val() === "") {
                $(".validateTips3").html("<span style='font size:2; color:red'>All fields required!</span>");
                if ($("#payperiod :selected").val() == "") {
                    alert('Select valid financial period');
                }
            } else {
                var post = $.get("../modules/accountsprocess.php?paybill=true&suppid=" + $("#suppliernameselect :selected").val() + "&billnos=" + $('#billnos').val() + "&payamounts=" + $('#sequencepayments').val() + "&paymode=" + $('#paymodebill :selected').val() + "&billdate=" + $('#paydate').val() + "&remarks=" + $('#remarksbill').val() + "&expenseacct=" + $("#expenseacctbill :selected").val() + "&chequeno=" + $("#cheqnobill").val() + "&chequedate=" + $("#chequedatebill").val() + "&chequedetails=" + $("#chequedetailsbill").val() + "&user=" + <?php echo $_SESSION['userid']; ?> + "&propid=" + $("#suppliernameselect :selected").attr("title") + "&fperiod=" + $("#payperiod :selected").val() + "&cashacct=" + $("#bankdepositpay").val(), function() {})

                    .done(function(data) {
                        alert('voucher(s) generated');
                        if (data.count <= 1) {
                            window.open("../modules/defaultreports.php?report=printvoucher&voucherno=" + data.status + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&user=" + <?php echo $_SESSION['userid'] ?>);
                        } else {
                            /*open window for payment voucher list*/
                        }
                        $('#newpbillform')[0].reset();
                        amount = 0;
                        billnum = '';
                        sequencepay = '';
                        $("#pendingbills").empty();
                    })
                    .fail(function() {
                        $(".validateTips3").html("<font size='2' color='red'><center>Error in fetching details</center></font>");
                    });
            }
        });
        //payments list
        $('#accountspayable').change(function(e) {
            if ($("#accountspayable :selected").attr("id") == "paylist") {
                $("#paymentslist").show('slow');
            }

            //expense reversal
            else if ($("#accountspayable :selected").attr("id") == "expensereverse") {

                $("#reversepayment").css("display", "block");

                $("#btnreversepayment").click(function(e) {
                    e.preventDefault();
                    $.ajax({
                            type: "POST",
                            contentType: "application/json; charset=utf-8",
                            url: "../modules/accountsprocess.php?reverseexpense=true&billno=" + $("#searchpay").val(),
                            data: "{}",
                            dataType: "json"
                        })

                        .done(function(data) {
                            var confirmation = confirm("Do you want to reverse voucher and payment for " + data.bill_no + " of amount " + data.bill_amount);
                            if (confirmation) {
                                $.ajax({
                                        type: "POST",
                                        contentType: "application/json; charset=utf-8",
                                        url: "../modules/accountsprocess.php?finalizereverseexpense=true&billno=" + $("#searchpay").val(),
                                        data: "{}",
                                        dataType: "json"
                                    })
                                    .done(function(data) {

                                        alert("Bill No " + $("#searchpay").val() + " and associated payments reversed");
                                        $("#reversepayment").css("display", "none");

                                    })
                                    .fail(function() {
                                        $(".validateTips3").html("<font size='2' color='red'><center>Error in reversing,try again</center></font>");
                                    })
                            }
                            //end of confirmation
                            $('#reversepaymentform')[0].reset();
                            $("#searchpay").empty();

                        })
                        .fail(function() {
                            $(".validateTips3").html("<font size='2' color='red'><center>Error in fetching details,try again</center></font>");

                            $('#reversepaymentform')[0].reset();
                            $("#searchpay").empty();
                            //$("#reversepayment").css("display","none");
                        });

                }); //end button
            } else if ($("#accountspayable :selected").attr("id") == "expensepaymentreverse") {

                $("#reversepaymentexpense").css("display", "block");

                $("#btnreversepaymentexpense").click(function(e) {
                    e.preventDefault();
                    $.ajax({
                            type: "POST",
                            contentType: "application/json; charset=utf-8",
                            url: "../modules/accountsprocess.php?reverseexpensepayment=true&payno=" + $("#searchpayno").val(),
                            data: "{}",
                            dataType: "json"
                        })

                        .done(function(data) {
                            var confirmation = confirm("Do you want to reverse payment for " + $("#searchpayno").val());
                            if (confirmation) {
                                $.ajax({
                                        type: "POST",
                                        contentType: "application/json; charset=utf-8",
                                        url: "../modules/accountsprocess.php?finalizereverseexpense=true&payno=" + $("#searchpayno").val(),
                                        data: "{}",
                                        dataType: "json"
                                    })
                                    .done(function(data) {

                                        alert("Payment Reversed");
                                        $("#reversepaymentexpense").css("display", "none");

                                    })
                                    .fail(function() {
                                        $(".validateTips3").html("<font size='2' color='red'><center>Error in reversing,try again</center></font>");
                                    })
                            }
                            //end of confirmation
                            $('#reversepaymentexpenseform')[0].reset();
                            $("#searchpay").empty();

                        })
                        .fail(function(data) {
                            $(".validateTips3").html("<font size='2' color='red'><center>" + data + "</center></font>");

                            $('#reversepaymentexpenseform')[0].reset();
                            $("#searchpayno").empty();
                            //$("#reversepayment").css("display","none");
                        });

                }); //end button
            }
        }); //select function

        //close payment window
        $("#closereversalexp").click(function() {

            $("#reversepaymentexpense").css("display", "none");
            $('#reversepaymentexpenseform')[0].reset();
            $('#divrevpaymentexpense').empty();
            $("#accountspayable").val('');
        });

        $("#closereversal").click(function() {

            $("#reversepayment").css("display", "none");
            $('#reversepaymentform')[0].reset();
            $("#accountspayable").val('');
        });



        $('#closepaylist').click(function(e) {
            $('#paymentslist').css("display", "none");
        });
        $("#fromdatepay").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });
        $("#todatepay").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'

        });
        //payments list
        $('.expenselist').click(function(e) {

            var optionselected = $(".expenselist:checked").val()

            if (optionselected == 2) {
                $('#expenseaccountsupplier').css("display", "none");
            } else {
                $('#expenseaccountsupplier').css("display", "block");
            }
        });
        $("#btnpaymentlist").click(function(e) {
            e.preventDefault();

            if ($("#usefperiodspay").is(":checked")) {
                window.open("../modules/defaultreports.php?report=paymentslistcloseperiod&closeperiod=" + $("#closeperiodpay :selected").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&officeexpenseflag=" + $(".expenselist:checked").val() + "&filterexpense=" + $("#expenseaccountsupplier :selected").val());
            } else {
                window.open("../modules/defaultreports.php?report=paymentslist&startdate=" + $("#fromdatepay").val() + "&enddate=" + $("#todatepay").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&user=" + <?php echo $_SESSION['userid'] ?> + "&officeexpenseflag=" + $(".expenselist:checked").val() + "&filterexpense=" + $("#expenseaccountsupplier :selected").val());
            }
        });
        //daily cash movement


        //commissions
        //close parentt window

        $('.closewindow').on("click", function() {
            $("#commissionswindow").css("display", "none");
        });
        //commissions list
        $('#commissionslist').on("click", function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=commissionlist&startdate=" + $("#fromdatecommission").val() + "&enddate=" + $("#todatecommission").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&user=" + <?php echo $_SESSION['userid'] ?>);
        })
        //arrears prepayments

        $("#closearrears").click(function(e) {
            $('#arrearsprep').toggle();
        });
        $("#btnfetcharrearsprep").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=fetcharrearsprepayments&fromdate=" + $("#fromdatearrears").val() + "&enddate=" + $("#todatearrears").val() + "&clientid=" + $("#clientnamearrears :selected").val() + "&count=" + $("#newarrearsform input[name='clients1']:radio:checked").val() + "&flag=" + $("#newarrearsform input[name='arrearsorprepayment']:radio:checked").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);

        });
        $("#btnfetchplotperformance").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=fetchplotperformance&fromdate=" + $("#fromdateperformance").val() + "&enddate=" + $("#todateperformance").val() + "&flag=" + $("#newperformanceform input[name='allplots']:radio:checked").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);

        });
        $("#btnfilterbypercentage").click(function(e) {

            e.preventDefault();
            window.open("../modules/defaultreports.php?report=fetchbypercentage&fromdate=" + $("#fromdatefilterbypercentage").val() + "&enddate=" + $("#todatefilterbypercentage").val() + "&flag=" + $("#newfilterbypercentageform input[name='allplots']:radio:checked").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&percentage=" + $("#myRange").val() + "&percentageto=" + $("#myRangeto").val());

        });

        //arrears prepayments


        $("#closepenalty").click(function(e) {
            $('#penaltiesdiv').toggle();
        });
        $("#btnfetchpenalty").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=penalties&enddate=" + $("#todatepenalty").val() + "&clientid=" + $("#clientnamepenalty :selected").val() + "&count=" + $("#newpenaltiesform input[name='penaltyradio']:radio:checked").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);

        });
        //arrears prepayments


        $("#closelandlordstatementdiv").click(function(e) {
            $('#landlordstatementdiv').toggle();
        });
        $("#btnlandlordstatement").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=landlordstatement&fromdate=" + $("#fromdatelandlord").val() + "&enddate=" + $("#todatelandlord").val() + "&clientid=" + $("#clientnamepenalty :selected").val() + "&count=" + $("#newpenaltiesform input[name='penaltyradio']:radio:checked").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);

        });

        $("#paylandlord").click(function(e) {
            location.href = "template.php?page=paylandlord";
        });

        $('#accountspayable').change(function(e) {
            if ($("#accountspayable :selected").attr("id") == "paylandlordbtn") {

                $("#paylandlorddiv").show('slow');
            }
        });
        //close

        $("#closelandlordpaydiv").click(function(e) {
            $('#paylandlorddiv').toggle();
            $("#accountspayable").val('');
        });

        $("#financialyearpay").change(function(e) {
            $("#closeperiodpayments").load("../modules/accountsprocess.php?getcloseperiodpayments=true&fy=" + $("#financialyearpay :selected").val());
        });
        $("#usefperiodspay").click(function(e) {
            $("#fromtopay").toggle();
        });
        //agent statement

        $("#closeagentstatementdiv").click(function(e) {
            $('#agentstatementdiv').toggle();
        });
        $("#btnagentstatement").click(function(e) {
            e.preventDefault();
            window.open("../modules/defaultreports.php?report=agentstatement&fromdate=" + $("#fromdateagent").val() + "&enddate=" + $("#todateagent").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);

        });
        $("input[name='billing']").click(function() {
            if ($(this).val() === '1') {
                $('#crdinv').show();
                $('#crdnt').show();
                $('#invnt').hide();
            } else if ($(this).val() === '0') {
                $('#crdinv').hide();
                $('#crdnt').hide();
                $('#invnt').show();
            }
        });
        //deposit refund
        $('#accountspayable').change(function(e) {
            if ($("#accountspayable :selected").attr("id") == "depositrefundlink") {

                $("#depositrefund").show('slow');
            }
        });
        $("#closedepositrefund").click(function(e) {
            $("#depositrefund").css("display", "none");
            $("#accountspayable").val('');
        });
        $("#btnrefunddeposit").click(function(e) {
            e.preventDefault();
            //suppliername,billdate,supplieditems,owedamount,btnreceivebill 
            if ($("#tenantnamedeposit :selected").val() == "" || $("#tenantdeposits :selected").val() == "" || $('#depositrefunddate').val() == "" || $('#paymoderefund').val() == "") {
                $("#validatebill").html("<font size='2' color='red'><center>All fields required!</center></font>");
            } else {
                $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "../modules/accountsprocess.php?depositrefund=true&tenant_id=" + $("#tenantnamedeposit :selected").val() + "&recpno=" + $("#tenantdeposits :selected").attr("title") + "&refunddate=" + $('#depositrefunddate').val() + "&paymode=" + $("#paymoderefund :selected").val() + "&amount=" + $("#tenantdeposits :selected").val() + "&deposititem=" + $("#tenantdeposits :selected").text() + "&chequedate=" + $("#chequedatedeposits").val() + "&chequeno=" + $("#chequenorefund").val() + "&remarks=" + $("#refundremarks").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>,
                        data: "{}",
                        dataType: "json"
                    })

                    .done(function(data) {
                        $("#validatebill").html("<font size='2' color='green'><center>Voucher<u>" + data.status + "</u> Created</center></font>");
                        alert("Voucher " + data.status + " Created");
                        if (data.count <= 1) {
                            window.open("../modules/defaultreports.php?report=printdepositvoucher&voucherno=" + data.status + "&tenant=" + data.tenant + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&user=" + <?php echo $_SESSION['userid'] ?>);
                        }
                        $('#depositrefundform')[0].reset();

                    })
                    .fail(function() {
                        $("#validatebill").html("<font size='2' color='red'><center>Error in creating bill</center></font>");
                    });
            }

        });
        $("#tenantnamedeposit").change(function(e) {
            $("#tenantdepositdiv").load("../modules/accountsprocess.php?tenantdeposits=true&tenant_id=" + $("#tenantnamedeposit :selected").val());
        });

        /***deposit refund list***/
        $('#accountspayable').change(function(e) {
            if ($("#accountspayable :selected").attr("id") == "depositrefundlist") {

                $("#depositrefunddiv").show('slow');
            }
        });
        $("#closedepositrefunddiv").click(function(e) {
            $("#depositrefunddiv").css("display", "none");
        });
        $("#btndepositrefund").click(function(e) {
            window.open("../modules/defaultreports.php?report=depositrefundlist&fromdate=" + $("#fromdatedeposits").val() + "&enddate=" + $("#todatedeposits").val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?>);
        })

        //other receipts
        $("input[name='receivemoney']").click(function(e) {
            var value = $(this).val();
            if (value === "other") {
                $("#customername").show('slow');
                $("#accountsrecp").empty();
                $('#recpamount').attr("readonly", false);
                $("#tenantnamereceipt").hide();
                $("#recepdetails").empty();
                $("#recepdetails").html('<center><input id="btnreceiptmoney" class="text ui-widget-content ui-corner-aEll" value="RECEIVE PAYMENT" style="width:200px;font-weight:bold;" type="submit"></center>');
                $("#btnreceiptmoney").click(function(e) {
                    e.preventDefault();
                    $value = $("input[name='receivemoney']:checked").val();
                    if ($('#recpamount').val() === "" || $('#clientnamerecp').val() === "" || $('#idnorecp').val() === "" || $('#recpdate').val() === "" || $('#paymoderecp').val() === "" || $("#paymode").val() === "" || $("#incomeacctrecp").val() === "" || $("#remarksrecp").val() === "") {
                        $("#validaterecp").html("<font size='2' color='red'><center>All fields required!</center></font>");
                    } else {
                        $.ajax({
                                type: "POST",
                                contentType: "application/json; charset=utf-8",
                                url: "../modules/accountsprocess.php?receiptother=true&invoicenosarray=0&counter=0&recpamountarray=" + $("#recpamount").val() + "&customer=" + $('#customername').val() + "&receiptdate=" + $('#recpdate').val() + "&paymode=" + $('#paymoderecp').val() + "&cashacct=" + $("#incomeacctrecp").val() + "&chequeacct=" + $("#incomeacctrecp").val() + "&chequedate=" + $("#chequedaterecp").val() + "&chequeno=" + $("#chequenorecp").val() + "&chequedetails=" + $("#chequedetailsrecp").val() + "&remarks=" + $("#remarksrecp").val() + "&applypenalty=0&penaltygl=" + $('#incomeacctpenalty option:selected').val() + "&fperiod=" + $('#fperiodrecp option:selected').val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&bankdeposit=" + $("#bankdeposit").val() + "&reference=" + $("#transactionreference").val(),
                                data: "{}",
                                dataType: "json"
                            })

                            // var post = $.get( "../modules/accountsprocess.php?receiptclient=true&invoicenosarray="+inv+"&counter="+counter+"&recpamountarray="+amt+"&tenantid="+$('#tenantnamereceipt').val()+"&receiptdate="+$('#recpdate').val()+"&paymode="+$('#paymoderecp').val()+"&cashacct="+$("#incomeacctrecp").val()+"&chequeacct="+$("#incomeacctrecp").val() +"&chequedate="+$("#chequedaterecp").val()+"&chequeno="+$("#chequenorecp").val()+"&chequedetails="+$("#chequedetailsrecp").val()+"&remarks="+$("#remarksrecp").val()+"&applypenalty="+penalize+"&fperiod="+$('#fperiodrecp option:selected').val()+"&propid="+<?php echo $_SESSION['propertyid'] ?>, function() {})

                            .done(function(data) {
                                alert('Receipt ' + data.status + ' generated');
                                window.open("../modules/defaultreports.php?report=printreceiptcustomer&receiptno=" + data.status);
                                $('#newreceiptform')[0].reset();
                                $("#recepdetails").empty();

                            })
                            .fail(function() {
                                $(".validateTips4").html("<font size='2' color='red'><center>Error in fetching details</center></font>");
                            });
                        $('#newreceiptform')[0].reset();
                        $("#recepdetails").empty();
                        $("#newreceipt").css("display", "none");
                    }
                })
            } else if (value = "tenant") {
                $("#customername").hide();
                $("#tenantnamereceipt").show('slow');
            }

        })

        //tenant and other receipts
        $("input[name='receiptsradio']").click(function(e) {
            $val = $(this).val();
            if ($val == "otherreceipts") {
                $("input[name='receiptproperties']").css("display", "none");
            } else {
                $("input[name='receiptproperties']").css("display", "block");
            }
        })
        //paylandlord

        $("#btnreceiptmoney").click(function(e) {
            e.preventDefault();
            $value = $("input[name='receivemoney']:checked").val();
            if ($('#recpamount').val() === "" || $('#clientnamerecp').val() === "" || $('#idnorecp').val() === "" || $('#recpdate').val() === "" || $('#paymoderecp').val() === "" || $("#paymode").val() === "" || $("#incomeacctrecp").val() === "" || $("#remarksrecp").val() === "") {
                $("#validaterecp").html("<font size='2' color='red'><center>All fields required!</center></font>");
            } else {
                $.ajax({
                        type: "POST",
                        contentType: "application/json; charset=utf-8",
                        url: "../modules/accountsprocess.php?receiptother=true&invoicenosarray=0&counter=0&recpamountarray=" + $("#recpamount").val() + "&customer=" + $('#customername').val() + "&receiptdate=" + $('#recpdate').val() + "&paymode=" + $('#paymoderecp').val() + "&cashacct=" + $("#incomeacctrecp").val() + "&chequeacct=" + $("#incomeacctrecp").val() + "&chequedate=" + $("#chequedaterecp").val() + "&chequeno=" + $("#chequenorecp").val() + "&chequedetails=" + $("#chequedetailsrecp").val() + "&remarks=" + $("#remarksrecp").val() + "&applypenalty=0&penaltygl=" + $('#incomeacctpenalty option:selected').val() + "&fperiod=" + $('#fperiodrecp option:selected').val() + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&reference=" + $("#transactionreference").val(),
                        data: "{}",
                        dataType: "json"
                    })

                    // var post = $.get( "../modules/accountsprocess.php?receiptclient=true&invoicenosarray="+inv+"&counter="+counter+"&recpamountarray="+amt+"&tenantid="+$('#tenantnamereceipt').val()+"&receiptdate="+$('#recpdate').val()+"&paymode="+$('#paymoderecp').val()+"&cashacct="+$("#incomeacctrecp").val()+"&chequeacct="+$("#incomeacctrecp").val() +"&chequedate="+$("#chequedaterecp").val()+"&chequeno="+$("#chequenorecp").val()+"&chequedetails="+$("#chequedetailsrecp").val()+"&remarks="+$("#remarksrecp").val()+"&applypenalty="+penalize+"&fperiod="+$('#fperiodrecp option:selected').val()+"&propid="+<?php echo $_SESSION['propertyid'] ?>, function() {})

                    .done(function(data) {
                        alert('Receipt ' + data.status + ' generated');
                        window.open("../modules/defaultreports.php?report=printreceiptcustomer&receiptno=" + data.status);
                        $('#newreceiptform')[0].reset();
                        $("#recepdetails").empty();

                    })
                    .fail(function() {
                        $(".validateTips4").html("<font size='2' color='red'><center>Error in fetching details</center></font>");
                    });
                $('#newreceiptform')[0].reset();
                $("#recepdetails").empty();
                $("#newreceipt").css("display", "none");
            }
        })
        //pay landlord
        $("#btnpaylandlord").click(function(e) {
            e.preventDefault();

            if ($('#propertytopay').val() === "" || $('#amountpaylandlord').val() === "" || $('#banktopayfrom').val() === "" || $('#cheqnopay').val() === "" || $('#chequedetailspay').val() === "") {
                alert("please fill all required details");
                $(".validateTips3").html("<span style='font size:2; color:red'>All fields required!</span>");
                if ($("#payperiod :selected").val() == "") {
                    alert('Select valid financial period');
                }
            } else {
                var post = $.get("../modules/accountsprocess.php?paylandlord=true&property=" + $('#propertytopay :selected').val() + "&amount=" + $('#amountpaylandlord').val() + "&bank=" + $('#banktopayfrom').val() + "&paydate=" + $("#paymentdatelandlord").val() + "&chequedate=" + $("#paymentdatecheque").val() + "&paymode=cheque" + "&chequeno=" + $('#cheqnopay').val() + "&chequedetails=" + $('#chequedetailspay').val() + "&reason=" + $('#pay-reason').val(), function() {})

                    .done(function(data) {
                        alert('voucher(s) generated');
                        if (data.count <= 1) {
                            window.open("../modules/defaultreports.php?report=printlandlordvoucher&voucherno=" + data.status + "&propid=" + <?php echo $_SESSION['propertyid'] ?> + "&user=" + <?php echo $_SESSION['userid'] ?>);
                        } else {
                            /*open window for payment voucher list*/
                        }
                        $('#newpbillform')[0].reset();
                        amount = 0;
                        billnum = '';
                        sequencepay = '';
                        $("#pendingbills").empty();
                    })
                    .fail(function() {
                        $(".validateTips3").html("<font size='2' color='red'><center>Error in fetching details</center></font>");
                    });
            }
        });



    }); //document ready
</script>

<style>
    .ui-menu {
        width: 150px;
    }
</style>
<?php

echo '</head><body>';
echo '<div id="form1">';
// echo '<h2>'.$wheat.$spacer.$property['company_name'].'| Property Manager |
// <span style="color:black"><img src="../images/cursors/agent.png"> 
// '.findpropertybyid($_SESSION['propertyid']).'</span><div id="loggedin">'
// .$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</h2>' .'<hr/>'; 
echo '<div id="header">' . $spacer . $logopath . '<div id="loggedin">' . $loggedin . ' ' . $admin . ' | ' . $clock . ' ' . $time . '</div>';

$agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
if (!$agentid) {
    header('Location: logout.php');
}

echo "</div>" . $wheat . '&nbsp;&nbsp;' . $endwheat;
//echo $wheat.'&nbsp;&nbsp;'.$endwheat;
echo '<table id ="menulayout"><tr><td valign="top">';
echo $sidebar;
echo '</td><td>&nbsp;&nbsp;</td><td style="width:100%">';
?>
<?php
// $agentid=get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
// if(!$agentid){  header('Location: logout.php');}
?>
<!-- <center><select class="propertyid" name="propertyid"> -->
<!-- <option value=" ">Select Property</option> -->
<?php
// foreach (get_agent_property($agentid) as  $value) {
//   $individualresult=explode('#', $value);
//    echo '<option value="' .$individualresult[0] . '"' . ($individualresult[0] == $_REQUEST["propertyid"] ? ' selected="selected"' : '') . '>' .strtoupper($individualresult[1]) . '</option>';
// echo "<option value='$individualresult[0]' title='$individualresult[2]'>".$individualresult[1]."</option>"; -->
// }
?>
</select></center>
<?php
echo '<fieldset class="fieldsettenants">';
echo '<legend id="financials"><b>FINANCIALS AND ACCOUNTING</b></legend>';
if ($_SESSION['usergroup'] != 3) {
?>
    <fieldset class="fieldsetsuppliers">
        <legend id="suppliers">
            <h3><span style="color:red">ACCOUNTS PAYABLE |</span>&nbsp;Expenses/Suppliers | Other Clients</h3>
        </legend>

        <select class="width50" id="accountspayable">
            <option id=" ">Select Menu Action</option>
            <!--<option id="newclienth">Add/Edit Client</option> 
    <option id="supplier">Add/Edit Expense,Supplier</option> --->

            <option id="suppbill">Expense/Supplier Bill</option>
            <?php if ($user["group"] == 1) { ?>
                <option id="expensereverse">Supplier/Expense C/Note</option>
                <option id="expensepaymentreverse">Reverse Payment</option>
            <?php } ?>
            <option id="paysbill">Pay Bill</option>
            <option id="paylandlordbtn">Pay Landlord</option>
            <option id="depositrefundlink">Deposit Refund</option>
            <option id="depositrefundlist">Deposit Refund List</option>
            <option id="paylist">Payments List</option>

            <option id="suppsummary">Supplier Account</option>

        </select>

    </fieldset>
<?php } ?>
<fieldset class="fieldsetcustomers">
    <legend id="customers">
        <h3>Tenants | Invoicing</h3>
    </legend>
    <select class="width50" id="accountsreceivable">
        <option id=" ">Select Invoicing Action</option>
        <?php if ($user["group"] != 3) { ?>

            <option id="invoicenew">New Invoice</option>
            <option id="printinv">Print Invoice</option>
            <?php if ($user["group"] == 1) { ?>
                <option id="invoicereverse">Reverse Invoice</option>
                <option id="batch">Batch Invoicing</option>
            <?php } ?>
            <option id="invlist">Invoicing List</option>

        <?php } else { ?>
            <option id="invlist">Invoicing List</option>
        <?php } ?>
    </select>
</fieldset>

<fieldset class="fieldsetcustomers">
    <legend id="customers">
        <h3>Tenants | Receipting |Cash Movement</h3>
    </legend>
    <select class="width50" id="receiptingactions">
        <option id=" " selected>Select Receipting Action</option>
        <?php if ($user["group"] != 3) { ?>
            <option id="receiptnew">New Receipt</option>
            <option id="printrecp">Print Receipt</option>
            <?php if ($user["group"] == 1) { ?>
                <option id="recpreverse">Receipt Reversal</option>
            <?php } ?>
            <option id="recplist">Receipt List</option>

            <!--<option id="paylandlord">Landlord Payments</option> -->
            <option id="cashmovement">Daily Cash Movement</option>
        <?php } else { ?>
            <option id="recplist">Receipt List</option><?php } ?>
    </select>
    <input id="dailycashdate" type="hidden" value="<?php echo date("Y-m-d")  ?>" />
</fieldset>
<?php

if ($_SESSION['usergroup'] != 3) {
?>
    <fieldset class="fieldsetchart">
        <legend id="chartofaccounts">
            <h3>Chart of Accounts</h3>
        </legend>
        <select class="width50" id="chartofaccountss">
            <option id=" ">Select Menu Action</option>
            <option id="accountslist1">Manage Accounts</option>
            <option id="expenseaccounts">Expense/Bank Accounts</option>

        </select>
    </fieldset>
<?php } ?>
<fieldset class="fieldsetchart">
    <legend id="chartofaccounts">
        <h3>Statements</h3>
    </legend>
    <select class="width50" id="statements">
        <option id=" ">Select Menu Action</option>
        <option id="prepayment"> Report Prepayment</option>
        <option id="tenantstatement">Tenant Statement</option>
        <option id="incomestatement">Income Statement</option>
        <option id="deplist">Deposit List</option>
        <option id="landlordstatement">Landlord Statement</option>
        <option id="agentstatement">Agent Statement</option>
        <option id="commissionsrep">Commissions Report</option>
        <option id="arrearsprepayments">Arrears Report</option>

        <option id="performance">Performance</option>
        <option id="filterbypercentageopt">Report By Percentage</option>
        <!-- <option id="penaltie">Performance By Agents</option> -->
        <option id="penalties">Penalties Report</option>
    </select>
</fieldset>;
<?php
echo '<br/>';
echo '</fieldset>';
echo '</body>';
?>

<!-- new invoice-->
<div id="newinvoice" class="internalwindow" title="New Invoice">
    <p class="titletr">New Invoice <a href="#" id="closeinvoice" style="float:right;">Close [X]</a></p>
    <p class="validateTips2">All form fields are required.</p>

    <form id="invoiceform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="date">Billing &nbsp;</label></td>
                    <td style="color:black;"><input type="radio" checked="checked" name="billing" id="crnotebilling" value="0">Invoice
                        <input type="radio" name="billing" id="crnotebilling" value="1">Credit Note
                    </td>
                </tr>
                <tr>
                    <td><label for="usergroup">Tenant/Client Name &nbsp;</label></td>
                    <td><select id='tenantnameinvoice' name='tenantnameinvoice' style="width:100%;">
                            <option selected="selected" value="">---</option>
                            <?php findtenantbypropertyid($_SESSION['propertyid']) ?>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="date">Entry Date &nbsp;</label></td>
                    <td><input id="invoicedate" name='invoicedate' readonly value="<?php echo date('d/m/Y'); ?>" style="width:350px; ">
                    </td>
                </tr>
                <tr>
                    <td><label for="usergroup">Financial Period &nbsp;</label></td>
                    <td id="getperioddiv">
                        <select id='fperiod' name='fperiod' style="width:100%;">
                            <?php $period = getPeriodByDate(date('d/m/Y'));
                            if (is_array($period)) {

                            ?>

                                <option selected="selected" value="<?php echo $period['idclose_periods'] ?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])) . ' to ' . date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>
                            <?php } else { ?>
                                <option selected="selected" value="">Financial Period Not Created Yet!</option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><label for="date">Water Consumption &nbsp;</label></td>
                    <td><b>Current.reading</b><input id="waterconsump" value="0" name='waterconsump' style="width:50px;"><b>last.reading</b><input id="lastreading" name='lastreading' readonly style="width:50px; "><b> Rate/Unit </b><input id="chargeperunit" name='chargeperunit' value="<?php echo get_water_rate($_SESSION['propertyid']); ?>" readonly style="width:50px; ">
                    </td>
                </tr>

                <tr>
                    <td><label for="incomeacct">Chargeable Items&nbsp;</label></td>
                    <td>
                        <div id="balancebroughtforward" class="blackfont"></div>
                        <div id="chargeitems"></div>
                    </td>
                </tr>
                <?php
                //show VAT where applicable
                $vat =  checkPropertyVAT($_SESSION['propertyid']);
                $vatamount =  getVAT('housevat');
                if ($vat) { ?>
                    <input id="housevat" type="hidden" value="<?php echo $vatamount ?>">
                    <tr>
                        <td><label for="vat">VAT&nbsp;</label></td>

                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input id="vatamount" type="text" name='vatamount' value="0">
                        </td>
                    </tr>
                <?php }

                ?>
                <tr>
                    <td><label for="amount">Amount </label></td>
                    <td><input id="amount" type="text" name='amount' style="width:350px;">
                    </td>
                </tr>

                <tr>
                    <td><label for="remarks">Remarks &nbsp;</label></td>
                    <td><textarea id="remarks" name='remarks' style="width:350px; height:100px; text-wrap:normal;"></textarea>
                        <br /><br />
                    </td>
                </tr>
                <tr hidden id="crdinv">
                    <td><label for="invoiceno">Invoice No: &nbsp;</label></td>
                    <td><input id="invoiceno" name='invoiceno' type="text" style="width:250px;">
                        <br /><br />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="pendinginvoices"></div>
                    </td>
                </tr>
                <tr id="invnt">
                    <td><input type="submit" id="btnenewinvoice" class="text ui-widget-content ui-corner-aEll" value="CREATE INVOICE" style="width:150px;font-weight:bold;" /></td>
                    <td></td>
                    <td><input type="submit" id="btnenextinvoice" class="text ui-widget-content ui-corner-aEll" value="NEXT" style="width:50px;font-weight:bold;" /></td>
                </tr>
                <tr hidden id="crdnt">
                    <td><input type="submit" id="btnenewinvoice" class="text ui-widget-content ui-corner-aEll" value="CREATE CREDIT NOTE" style="width:150px;font-weight:bold;" /></td>
                    <td></td>
                    <td><input type="submit" id="btnenextinvoice" class="text ui-widget-content ui-corner-aEll" value="NEXT" style="width:50px;font-weight:bold;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!--s-->

<!-- reverse invoice-->
<div id="reverseinvoice" title="">
    <p class="titletr">Reverse Invoice <a href="#" style="float:right" id="closeinvoicerev">Close [X]</a></p>
    <p class="validateTips3">All form fields are required.</p>

    <form id="reverseinvoiceform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="amount">Invoice No. &nbsp;</label></td>
                    <td><input id="searchinvoice" type="text" name='searchinvoice' style="width:70px; ">
                    </td>
                    <td><input type="submit" id="btnreverseinvoice" class="text ui-widget-content ui-corner-aEll" value="SEARCH INVOICE" style="width:150px;font-weight:bold;" /></td>
                </tr>

            </table>
            <div id="divrevinvoice"></div>
        </fieldset>
    </form>
</div>
<!--s-->
<script>
    function submitReason() {
        //    if($("#reason").val()==""){

        //        return false;
        //    }
        $.ajax({
            type: "POST",
            contentType: "application/json; charset=utf-8",
            url: "../modules/accountsprocess.php?addpaymentreason=true&reason=" + $("#reason").val(),
            data: "{}",
            dataType: "json"
        }).done(function(data) {
            if (data == "1") {
                alert("added successfully")
                location.reload();
            } else {
                alert("Failed to add");
            }
        })
        return false;
    }
</script>
<div id="addreason" style="display:none" title="LandLord Payment Reason">
    <p class="titletr">LandLord Payment Reason <a href="#" style="float:right" onclick="closepayment()">Close [X]</a></p>

    <fieldset id="addreasons">
        <form id="paymentReason" onsubmit="return submitReason()" method="post" enctype="multipart/form-data">
            <center>
                <table>

                    <tr>
                        <td><label>Reason &nbsp;</label></td>
                        <td>

                            <input id="reason" type="text" name='reason' style="width:250px; " required>
                        </td>
                    <tr>
                        <td></td>
                        <td><input type="submit" id="add-payment-reason" class="text ui-widget-content ui-corner-aEll" value="SAVE" style="width:250px;font-weight:bold;" /></td>
                    </tr>
                    </tr>
                </table>
            </center>
        </form>
    </fieldset>

</div>
<!--s-->
<!--print invoice-->
<div id="printinvoice" class="internalwindow" title="">
    <p class="titletr">Print | Email Invoice <a href="#" style="float:right" id="closeinvoiceprint">Close [X]</a></p>
    <p class="validateTips4">All form fields are required.</p>


    <fieldset id="printinv">
        <form id="printinvoiceform" method="post" enctype="multipart/form-data">
            <center>
                <table>
                    <tr>
                        <td><label for="inv">Invoice No. &nbsp;</label></td>
                        <td><input id="searchinvoiceprint" type="text" name='searchinvoiceprint' style="width:100px; ">
                        </td>
                        <td><input type="submit" id="btnprintinvoice" class="text ui-widget-content ui-corner-aEll" value="PRINT INVOICE" style="width:150px;font-weight:bold;" /></td>
                    </tr>
                </table>
            </center>
        </form>
    </fieldset>

</div>
<!--s-->
<!-- invoice list -->
<div id="invoicelist" class="internalwindow" title="">
    <p class="titletr">Invoice List <a href="#" style="float:right" id="closeinvoicelist">Close [X]</a></p>
    <p class="validateTips3">All fields are required</p>

    <form id="invoicelistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label>Start Date </label></td>
                    <td><label>End Date </label></td>
                    <td><label>Invoice For</label></td>
                    <td><label>Invoice Status</label></td>
                </tr>
                <tr>
                    <td><input id="startdatelist" type="text" name='startdatelist' style="width:170px;"></td>
                    <td><input id="enddatelist" type="text" name='enddatelist' style="width:170px;"></td>
                    <td><select id='accnamelist' name='accnamelist' style="width:170px;">
                            <option selected="selected" value="0">ALL</option><?php echo getincomeaccount() ?>
                        </select> </td>
                    <td><select id='invoicestatus' name='invoicestatus' style="width:170px;">
                            <option selected="selected" value="%">ALL</option>
                            <option value="1">PAID</option>
                            <option value="0">NOT PAID</option>
                        </select> </td>
                </tr>
                <tr>
                    <td><input type="radio" name="single" value="0" checked="checked">Single Property</td>
                    <td><input type="radio" name="single" value="1">All Properties</td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btninvoicelist" class="text ui-widget-content ui-corner-aEll" value="INVOICE LIST" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!--s-->
<!-- invoice list -->
<div id="batchinvoicing" class="internalwindow displaynone" title="">

    <p class="titletr">Batch Invoicing <a href="#" style="float:right" id="closebatch">Close [X]</a></p>
    <p class="validateTips5">All fields are required</p>

    <form id="batchinvoiceform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="pname"> Select&nbsp;</label></td>
                    <script>
                        function batchchange(option) {
                            if (option == "2") {
                                document.getElementById("propname").style.display = "table-row";
                            } else {
                                document.getElementById("propname").style.display = "none";
                            }
                        }
                    </script>
                    <td>
                        <select onchange="batchchange(this.value)" id="batch_all">
                            <option value="1">All Properties</option>
                            <option value="2">One Property</option>
                        </select>
                    </td>
                </tr>
                <tr style="display:none" id="propname">
                    <td><label for="pname">Property Name. &nbsp;</label></td>

                    <td><input id="propertyname" type="text" name='propertyname' value="<?php echo findpropertybyid($_SESSION['propertyid']) ?>" readonly style="width:245px;background-color:lightyellow"></td>
                </tr>
                <tr>
                    <td><label for="date">Billing &nbsp;</label></td>
                    <td style="color:black;"><input type="radio" name="billingbatch" checked="checked" value="0">Invoice
                        <input type="radio" name="billingbatch" value="1">Credit Note
                    </td>
                </tr>

                <tr>
                    <td><label for="datebatch">Entry Date &nbsp;</label></td>
                    <td><input id="batchinvoicedate" name='invoicedate' value="<?php echo date('d/m/Y') ?>" readonly="readonly" style="width:245px; ">
                    </td>
                </tr>
                <tr>
                    <td><label for="usergroup">Financial Period &nbsp;</label></td>

                    <td id="getbatchperioddiv">
                        <select id='fperiodrecp' name='fperiodbatch' style="width:100%;">
                            <?php $period = getPeriodByDate(date('d/m/Y'));
                            if (is_array($period) > 0) {
                            ?>
                                <option selected="selected" value="<?php echo $period['idclose_periods'] ?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])) . ' to ' . date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>
                            <?php } else { ?>
                                <option selected="selected" value="">Financial Period Not Created Yet!</option>
                            <?php }

                            ?>
                        </select>

                    </td>
                </tr>
                <tr>
                    <td><label for="incomeacct"> Agent Income Account &nbsp;</label></td>
                    <td><select id='batchincomeacct' name='incomeacct' style="width:250px;">
                            <?php echo getincomeaccount($_SESSION['propertyid']) ?>
                            <option selected="selected" value="all">All</option>
                        </select> </td>
                </tr>

                <tr>
                    <td><label for="amount">Amount &nbsp;</label></td>
                    <td><input id="batchamount" type="text" name='amount' style="width:245px; ">
                    </td>
                </tr>

                <tr>
                    <td><label for="remarks">Remarks &nbsp;</label></td>
                    <td><textarea id="batchremarks" name='remarks' style="width:245px; height:100px; text-wrap:normal;"></textarea>
                        <br /><br />
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <center><input type="submit" id="btnbatchinvoice" class="text ui-widget-content ui-corner-aEll" value="INVOICE BATCH" style="width:150px;font-weight:bold;" /></center>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!---->
<!-- New client -->
<div id="newclient" class="internalwindow displaynone" title="">
    <p class="titletr">Add/Edit Client <a href="#" style="float:right" id="closeclient">Close [X]</a></p>
    <p id="validateclient" class="validateTips3">All fields are required</p>

    <form id="newclientform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="addedit">Add/edit &nbsp;</label></td>
                    <td style="color:black;"><input type="radio" id="addeditclient" class="radio4" value="0" />Add
                        <input type="radio" id="addeditclient" class="radio4" value="1" />Edit
                    </td>
                </tr>
                <tr>
                    <td><label id="cnamelabel" for="cname">Client Name &nbsp;</label></td>
                    <td id="cnametd"><input id="clientname" name='clientname' style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">Physical Address &nbsp;</label></td>
                    <td><input id="caddress" name='caddress' style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">Email Address &nbsp;</label></td>
                    <td><input id="cemail" name='cemail' style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">City &nbsp;</label></td>
                    <td><input id="ccity" name='ccity' style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">Client Phone &nbsp;</label></td>
                    <td><input id="cphone" name='cphone' style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;&nbsp;&nbsp;<input type="submit" id="btncreateclient" class="text ui-widget-content ui-corner-aEll" value="SAVE CLIENT" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!--s -->
<div id="newsupplier" class="internalwindow displaynone" title="">
    <p class="titletr">Add/Edit Expense/Supplier<a href="#" id="closesupplier" style="float:right">Close [X]</a></p>
    <p id="validatesupplier" class="validateTips3">All fields are required</p>

    <form id="newsupplierform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="addedit">Add/edit &nbsp;</label></td>
                    <td style="color:black;"><input type="radio" name="addeditsupplier" id="addeditsupplier" class="radio1" value="0" />Add
                        <input type="radio" name="addeditsupplier" id="addeditsupplier" class="radio2" value="1" />Edit
                    </td>
                </tr>
                <tr>
                    <td><label id="cnamelabel" for="cname">Expense/Supplier Name &nbsp;</label></td>
                    <td id="snametd"><input id="suppliername" name='suppliername' style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">Expense/Supplied Items &nbsp;</label></td>
                    <td><input id="suppitems" name='suppitems' style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">Physical Address &nbsp;</label></td>
                    <td><input id="saddress" name='saddress' value="n/a" style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">Email Address &nbsp;</label></td>
                    <td><input id="semail" name='semail' value="n/a" style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname">City &nbsp;</label></td>
                    <td><input id="scity" name='scity' value="n/a" style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td><label for="cname"> Phone No&nbsp;</label></td>
                    <td><input id="sphone" name='sphone' value="n/a" style="width:245px; " />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;&nbsp;&nbsp;<input type="submit" id="btncreatesupplier" class="text ui-widget-content ui-corner-aEll" value="SAVE EXPENSE" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>

<div class="loaderimage displaynone" style="z-index:999;position:absolute;top:50%;right:50%;left:50%"><img src="../images/ld.gif" /></div>

<!-- New receipt -->
<div id="newreceipt" class="internalwindow displaynone" title="">
    <p class="titletr">New receipt <a href="#" style="float:right" id="closereceipt">Close [X]</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="newreceiptform" method="post" enctype="multipart/form-data">
        <fieldset>
            <center>
                <table>

                    <tr>
                        <td><label for="usergroup"><input id="tenantradio" type="radio" name="receivemoney" value="tenant" checked="checked">Tenant &nbsp;</label><label for="usergroup"><input id="othermoneyradio" type="radio" name="receivemoney" value="other">Receive Money &nbsp;</label></td>
                        <td><label for="cname">Receipt Date &nbsp;</label></td>
                        <td><label>&nbsp;&nbsp;No Penalty?</label></td>
                    </tr>
                    <tr>
                        <td><select id='tenantnamereceipt' name='tenantnamereceipt' style="width:250px;">
                                <option selected="selected" value="">---</option>
                                <?php findtenantbypropertyid($_SESSION['propertyid']) ?>
                            </select>
                            <!-- <select id='customername'  class="displaynone" name='customername'  style="width:250px;"><option selected="selected" value="">---</option>  
//<?php findtenantbypropertyid($_SESSION['propertyid']) ?>
       </select>-->
                            <input type='text' id='customername' class="displaynone" name='customername'>

                        </td>
                        <td><input id="recpdate" readonly="readonly" value="<?php echo date("d/m/Y"); ?>" style="width:245px; height:20px;" />
                        </td>
                        <td>&nbsp;<input type="checkbox" id="penalize" value="1" checked="checked"></td>
                    </tr>
                    <tr>
                        <td><label for="usergroup">Financial Period &nbsp;</label></td>
                        <td><select id='fperiodrecp' name='fperiodrecp' style="width:100%;">
                                <?php $period = getPeriodByDate(date('d/m/Y'));
                                if (is_array($period) > 0) {
                                ?>
                                    <option selected="selected" value="<?php echo $period['idclose_periods'] ?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])) . ' to ' . date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>
                                <?php } else { ?>
                                    <option selected="selected" value="">Financial Period Not Created Yet!</option>
                                <?php }

                                ?>
                            </select></td>
                    </tr>

                    <tr>
                        <td><label for="cname">Payment Mode &nbsp;</label></td>
                        <td><label for="cname">Amount</label></td>
                    </tr>
                    <tr>
                        <td><select id="paymoderecp" style="width:245px; height:20px; ">
                                <option selected="selected" value="">---</option>
                                <option value="0">CASH</option>
                                <option value="1">CHEQUE</option>
                                <option value="2">CREDIT CARD</option>
                                <option value="3">BANK DEPOSIT</option>
                                <option value="4">MPESA</option>
                            </select>
                        </td>
                        <td><input id="recpamount" name="recpamount" readonly style="width:245px; height:20px; border:1px solid green " /></td>
                    </tr>
                    <tr>
                        <td><label id="chequedate" style="display:none;">Cheque Date &nbsp;</label></td>
                        <td><label id="cheqno" style="display:none;">Cheque No &nbsp;</label></td>
                    </tr>
                    <tr>
                        <td><input id="chequedaterecp" style="width:245px; height:20px; display:none;" />
                        </td>
                        <td><input id="chequenorecp" style="width:245px; height:20px; display:none;" />
                        </td>
                    </tr>
                    <tr>
                        <td><label id="chequedetails" style="display:none;">Cheque Details &nbsp;</label></td>
                        <td><label for="remarks">Remarks &nbsp;</label></td>
                    </tr>
                    <tr>
                        <td><textarea id="chequedetailsrecp" style="width:245px; height:60px; text-wrap:normal;"></textarea>
                        </td>
                        <td><textarea id="remarksrecp" style="width:245px; height:60px; text-wrap:normal;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="banks">Bank&nbsp;</label></td>
                        <td id="banks"></td>
                    </tr>
                    <tr>
                        <td><label for="cname">To Account &nbsp;</label></td>
                        <td id="accountsrecp"></td>
                    </tr>
                    <tr>
                        <td><label for="cname">Penalty Account&nbsp;</label></td>
                        <td id="accountspenalty"></td>
                    </tr>
                    <tr>
                        <td><label for="reference">Transaction Reference&nbsp;</label></td>
                        <td><input type="text" class="required" id="transactionreference" placeholder="MPESA,BANK REF"></td>
                    </tr>
                </table>
            </center>
            <div id="recepdetails"></div>
        </fieldset>
        </fieldset>
    </form>
</div>
<!--s-->
<!--print receipt-->
<div id="printreceipt" class="internalwindow displaynone" title="">
    <p class="titletr">Print | Email Receipt<a href="#" style="float:right" id="closereceiptprint">Close [X]</a></p>
    <p id="validatereceiptprint" class="validateTips4">All form fields are required.</p>


    <fieldset id="printinv">
        <form id="printreceiptform" method="post" enctype="multipart/form-data">
            <center>
                <table>
                    <tr>
                        <td><label for="inv">Receipt No. &nbsp;</label></td>
                        <td><input id="searchreceiptprint" type="text" style="width:100px; " />
                        </td>
                        <td><input type="submit" id="btnprintreceipt" class="text ui-widget-content ui-corner-aEll" value="PRINT RECEIPT" style="width:150px;font-weight:bold;" />
                            <input type="submit" id="btnprintreceiptother" class="text ui-widget-content ui-corner-aEll" value="MISC RECEIPT" style="width:150px;font-weight:bold;" />
                        </td>
                    </tr>
                </table>
            </center>
        </form>
    </fieldset>

</div>
<!--s-->


<div id="reversepayment " class="displaynone internalwindow ">
    <p class="titletr">Reverse Expense/Payment<a href="#" class="linkright" id="closereversal">Close [X]</a></p>
    <p id="reverserecp" class="validateTips3">All form fields are required.</p>

    <form id="reversepaymentform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="recno">Bill No. &nbsp;</label></td>
                    <td><input id="searchpay" type="text" style="width:70px; ">
                    </td>
                    <td><input type="submit" id="btnreversepayment" class="text ui-widget-content ui-corner-aEll" value="REVERSE BILL" style="width:150px;font-weight:bold;" /></td>
                </tr>

            </table>
            <div id="divrevpayment"></div>
        </fieldset>
    </form>
</div>

<div id="reversepaymentexpense" class="displaynone " style="position:absolute;top:20px;left:350px;right:auto;z-index:9999 !important;background-color:white;width:30%">
    <p class="titletr">Reverse Expense/Voucher<a href="#" class="linkright" id="closereversalexp">Close [X]</a></p>
    <p id="reverserecp" class="validateTips3">All form fields are required.</p>

    <form id="reversepaymentexpenseform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="recno">Voucher No. &nbsp;</label></td>
                    <td><input id="searchpayno" type="text" style="width:70px; ">
                    </td>
                    <td><input type="submit" id="btnreversepaymentexpense" class="text ui-widget-content ui-corner-aEll" value="REVERSE PAYMENT" style="width:150px;font-weight:bold;" /></td>
                </tr>

            </table>
            <div id="divrevpayment"></div>
        </fieldset>
    </form>
</div>


<!-- reverse receipt-->
<div id="reversereceipt" class="displaynone" title="">
    <p class="titletr">Reverse Receipt<a href="#" class="linkright" id="closereceiptrev">Close [X]</a></p>
    <p id="reverserecp" class="validateTips3">All form fields are required.</p>

    <form id="reversereceiptform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><label for="recno">Receipt No. &nbsp;</label></td>
                    <td><input id="searchrecp" type="text" style="width:70px; ">
                    </td>
                    <td><input type="submit" id="btnreversereceipt" class="text ui-widget-content ui-corner-aEll" value="REVERSE RECEIPT" style="width:150px;font-weight:bold;" /><input type="submit" id="btnreverseotherreceipt" class="text ui-widget-content ui-corner-aEll" value="OTHER RECEIPT" style="width:150px;font-weight:bold;" /></td>
                </tr>

            </table>
            <div id="divrevreceipt"></div>
        </fieldset>
    </form>
</div>
<!--s-->
<!-- invoice list -->
<div id="receiptlist" class="internalwindow displaynone" title="">
    <p class="titletr">Receipt List<a href="#" class="linkright" id="closerecplist">Close [X]</a></p>
    <p id="rcplist" class="validateTips3">Choose start/End Date</p>

    <form id="recplistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table width="100">
                <tr>
                    <td><label><input type="radio" name="receiptsradio" value="rentreceipts" checked="checked">Rent Receipts</label></td>
                    <td><label><input type="radio" name="receiptsradio" value="otherreceipts">Other Receipts </label></td>
                </tr>
                <tr>
                    <td><label>Start Date </label></td>
                    <td><label>End Date </label></td>
                </tr>
                <tr>
                    <td><input id="startdaterecp" style="width:150px;" type="text"></td>
                    <td><input id="enddaterecp" style="width:150px;" type="text"></td>
                </tr>
                <tr>
                    <td>Filter By tenant</td>
                    <td><select id="tenantfilter">
                            <option value="" selected="selected">Select Tenant</option>
                            <?php $tenants = getallSystemtenants("all", "tenant_name", "ASC", $_SESSION["username"]);
                            foreach ($tenants as $tenant) { ?>
                                <option value="<?= $tenant["Id"] ?>"><?= $tenant["tenant_name"] ?></option>

                            <?php }
                            ?>

                        </select></td>
                </tr>
                <tr>
                    <td><input type="radio" name="receiptproperties" value="0" checked="checked">Single Property</td>
                    <td><input type="radio" name="receiptproperties" value="1">All Properties</td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btnrecplist" class="text ui-widget-content ui-corner-aEll" value="RECEIPT LIST" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>

<div id="depositlist" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Deposit List<a href="#" class="linkright" id="closedeposits">Close [X]</a></p>
    <p id="rcplist" class="validateTips3">Choose start/End Date</p>

    <form id="depositlistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table width="100">
                <tr>
                    <td><label>Start Date </label></td>
                    <td><label>End Date </label></td>
                </tr>
                <tr>
                    <td><input id="startdatedeposit" class="datepicker" style="width:150px;" type="text"></td>
                    <td><input id="enddatedeposit" class="datepicker" style="width:150px;" type="text"></td>
                </tr>
                <tr>
                    <td><input type="radio" name="depositproperties" value="0" checked="checked">Single Property</td>
                    <td><input type="radio" name="depositproperties" value="1">All Properties</td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btndepositlist" class="text ui-widget-content ui-corner-aEll" value="DEPOSIT LIST" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!--tenant statement's-->
<div id="tstmnt" class="internalwindow displaynone" title="">
    <p class="titletr">Tenant Statement<a href="#" id="closestatement" style="float:right">Close X</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="newstatementform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td>Mode:</td>
                    <td style="color:black;"><input type="radio" name="clients" value="0">Single Tenant
                        <input type="radio" name="clients" value="1">All Tenants
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><label for="usergroup">Tenant Name &nbsp;</label></td>
                    <td><select id='clientnamestmnt' name='clientnamestmnt' style="width:250px;">
                            <option selected="selected" value="">Select Tenant/Client</option>
                            <?php findtenantbypropertyid($_SESSION['propertyid']) ?>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="cname">From Date &nbsp;</label></td>
                    <td><label for="cname">To Date</label></td>
                </tr>
                <tr>
                    <td><input id="fromdate" name="fromdate" readonly style="width:200px; height:20px; border:1px solid green " />
                    </td>
                    <td><input id="todate" name="todate" readonly style="width:200px; height:20px; border:1px solid green " /></td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btnfetchstatement" class="text ui-widget-content ui-corner-aEll" value="VIEW STATEMENT" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
            </center>
        </fieldset>
    </form>
</div>



<div id="prepayment-report" class="internalwindow displaynone" title="">
    <p class="titletr">Report Prepayment<a href="#" id="closeprepayment" style="float:right">Close X</a></p>
    <p id="validaterecp" class="validateTips3">Select Appartment</p>

    <form id="newstatementform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td>Mode:</td>
                    <td style="color:black;"><input type="radio" name="clients" value="0"><?php echo findpropertybyid($_SESSION['propertyid'])?>
                       
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><label for="usergroup">Apartment Tag &nbsp;</label></td>
                    <td><select id='prepayment-select' name='prepayment' style="width:250px;">
                            <option selected="selected" value="">Select Apartment</option>
                            <?php //find($_SESSION['propertyid']) 
                            $apt = getPropertyApartments($_SESSION['propertyid']);
                            //  print_r($apt);
                            foreach ($apt as $ap) {
                                $apt_tag = $ap['apt_tag'];
                                $apt_id = $ap['apt_id'];
                                echo "<option value='$apt_id'>$apt_tag</option>";
                            }

                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btnreportprepayment" class="text ui-widget-content ui-corner-aEll" value="VIEW STATEMENT" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
            </center>
        </fieldset>
    </form>
</div>
<!--tenant statement's arrears and prepayments-->
<div id="arrearsprep" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Arrears and Prepayments<a href="#" id="closearrears" style="float:right">Close X</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="newarrearsform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td>Report:</td>
                    <td style="color:black;"><input type="radio" name="arrearsorprepayment" value="A">Arrears
                        <input type="radio" name="arrearsorprepayment" value="P">Prepayments
                    </td>
                </tr>
                <tr>
                    <td>Mode:</td>
                    <td style="color:black;"><input type="radio" name="clients1" value="0">Single Tenant
                        <input type="radio" name="clients1" value="1">All Tenants
                        <input type="radio" name="clients1" value="2">All Properties
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><label for="usergroup">Tenant Name &nbsp;</label></td>
                    <td><select id='clientnamearrears' name='clientnamearrears' style="width:250px;">
                            <option selected="selected" value="">---</option>
                            <?php findtenantbypropertyid($_SESSION['propertyid']) ?>
                        </select></td>
                </tr>
                <tr>
                    <td><label>Arrears To:</label>
                    </td>
                    <td>
                        <input id="fromdatearrears" class="datepicker" name="fromdate" placeholder="From Date" readonly style="width:200px; height:20px; border:1px solid green " />
                        <br>
                        <input id="todatearrears" placeholder="To Date" class="datepicker" name="todate" readonly style="width:200px; height:20px; border:1px solid green " />
                    </td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btnfetcharrearsprep" class="text ui-widget-content ui-corner-aEll" value="VIEW REPORT" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
            </center>
        </fieldset>
    </form>
</div>
<!--arrears prepayments-->
<!-- Performance -->
<div id="plotperformance" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Property Performance<a href="javascript:void(0)" id="closeplotperformance" style="float:right">Close X</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="newperformanceform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td>Report:</td>
                    <td style="color:black;">

                        <input type="radio" name="allplots" value="one"><?php echo propertyname($_SESSION['propertyid']) ?>
                        <input type="radio" name="allplots" value="two">Agent wise
                        <input type="radio" name="allplots" value="all">All Properties
                    </td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>

                <tr>
                    <td><label>FROM :</label>
                    </td>
                    <td>
                        <input id="fromdateperformance" class="datepicker" name="fromdate" placeholder="From Date" readonly style="width:200px; height:20px; border:1px solid green " />
                    <td>
                </tr>
                <tr>
                    <td><label> To:</label>
                    </td>
                    <td>
                        <input id="todateperformance" placeholder="To Date" class="datepicker" name="todate" readonly style="width:200px; height:20px; border:1px solid green " />
                    </td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btnfetchplotperformance" class="text ui-widget-content ui-corner-aEll" value="VIEW REPORT" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
            </center>
        </fieldset>
    </form>
</div>
<!-- Performance-->
<!-- filter by percentage-->
<style type="text/css">
    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 8px;
        border-radius: 5px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: #04AA6D;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #04AA6D;
        cursor: pointer;
    }
</style>
<div id="filterbypercentage" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Report By percentage Rent Paid<a href="javascript:void(0)" id="closefilterbypercentage" style="float:right">Close X</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="newfilterbypercentageform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>

                <tr>
                    <td colspan="4" style="padding: 10px;">
                        Select Percentage from:
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 10px;">
                        <input type="range" min="0" max="100" value="0" class="slider" id="myRange">
                    </td>
                    <td style=" padding-left: 50px;"><span id="demo"></span>%</td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 10px;">
                        Select Percentage to:
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding: 10px;">
                        <input type="range" min="0" max="100" value="0" class="slider" id="myRangeto">
                    </td>
                    <td style=" padding-left: 50px;"><span id="demoto"></span>%</td>
                </tr>
                <tr>

                </tr>
                <tr></tr>
                <tr>
                    <td>Report:</td>
                    <td style="color:black;">
                        <input type="radio" name="allplots" value="all">All Properties
                        <input type="radio" name="allplots" value="one"><?php echo propertyname($_SESSION['propertyid']) ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td><label>FROM :</label>
                    </td>
                    <td>
                        <input id="fromdatefilterbypercentage" class="datepicker" name="fromdate" placeholder="From Date" readonly style="width:200px; height:20px; border:1px solid green " />
                    <td>
                </tr>
                <tr>
                    <td><label> To:</label>
                    </td>
                    <td>
                        <input id="todatefilterbypercentage" placeholder="To Date" class="datepicker" name="todate" readonly style="width:200px; height:20px; border:1px solid green " />
                    </td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btnfilterbypercentage" class="text ui-widget-content ui-corner-aEll" value="VIEW REPORT" style="width:150px;font-weight:bold;" /></td>
                </tr>

</div>

<script>
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    output.innerHTML = slider.value;

    slider.oninput = function() {
        output.innerHTML = this.value;
    }
    var slider1 = document.getElementById("myRangeto");
    var output1 = document.getElementById("demoto");
    output1.innerHTML = slider.value;

    slider1.oninput = function() {
        output1.innerHTML = this.value;
    }
</script>
</table>
</center>
</fieldset>
</form>
</div>
<!-- filter by %-->
<!--penalties-->
<div id="penaltiesdiv" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Penalties<a href="#" id="closepenalty" style="float:right">Close X</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="newpenaltiesform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>

                <tr>
                    <td>Mode:</td>
                    <td style="color:black;"><input type="radio" name="penaltyradio" value="0">Single Tenant
                        <input type="radio" name="penaltyradio" value="1">Property Tenants
                        <input type="radio" name="penaltyradio" value="2">All properties
                    </td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><label for="usergroup">Tenant Name &nbsp;</label></td>
                    <td><select id='clientnamepenalty' name='clientnamepenalty' style="width:250px;">
                            <option selected="selected" value="">---</option>
                            <?php findtenantbypropertyid($_SESSION['propertyid']) ?>
                        </select></td>
                </tr>

                <tr>
                    <td><label>Penalties To:</label>
                    </td>
                    <td><input id="todatepenalty" class="datepicker" name="todate" readonly style="width:200px; height:20px; border:1px solid green " /></td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;&nbsp;<input type="submit" id="btnfetchpenalty" class="text ui-widget-content ui-corner-aEll" value="VIEW REPORT" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!--Bank Accounts-->
<div id="acctlist" class="internalwindow" title="">
    <p class="titletr">Accounts List<a href="#" id="closeacctlist" style="float:right">Close X</a></p>
    <form id="statementform" name="statementform">
        <div>&nbsp; From Date&nbsp;<input id="fromdatestatement" class="datepicker" placeholder="from date" name="fromdatestatement" style="border:1px solid green " />&nbsp;To Date&nbsp;<input id="todatestatement" placeholder="to date" class="datepicker" name="todatestatement" style="border:1px solid green " /><button name="viewstatement" id="viewstatement">Statement</button></div>
        <p></p>

        <fieldset id="bkaccts">
            <table id="treport">

            </table>
        </fieldset>
    </form>
</div>
<!--bank accounts-->
<!--income statement-->
<div id="incomestmnt" class="internalwindow" title="">
    <p class="titletr">Income Statement<a href="#" id="closeincomestatement" style="float:right">Close X</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="incomestatementform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td colspan="2"><label for="usergroup">Income Statement &nbsp;</label></td>
                </tr>
                <tr>
                    <td><label for="cname">From Date &nbsp;</label></td>
                    <td><label for="cname">To Date</label></td>
                </tr>
                <tr>
                    <td><input id="fromdateinc" name="fromdateinc" readonly style="width:200px; height:20px; border:1px solid green " />
                    </td>
                    <td><input id="todateinc" name="todateinc" readonly style="width:200px; height:20px; border:1px solid green " /></td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" id="btnfetchincstatement" class="text ui-widget-content ui-corner-aEll" value="VIEW STATEMENT" style="width:150px;font-weight:bold;" /></td>
                </tr>
            </table>
            </center>
        </fieldset>
    </form>
</div>
<!--income statement-->
<!-- New supplier bill -->
<div id="newsbill" class="internalwindow" title="">
    <p class="titletr">Create Expense Bill<a href="#" style="float:right" id="closebill">Close [X]</a></p>
    <span id="validatebill">All fields are required</span>

    <form id="newsbillform" method="post" enctype="multipart/form-data">
        <fieldset>
            <center>
                <table>
                    <tr>
                        <td><input type="radio" name="expensetype" class="blackfont expensetype" checked="checked" value="1"><span class="blackfont">Property Expense</span></td>
                        <td><input type="radio" name="expensetype" class="expensetype" value="0"><span class="blackfont">Office Expense</span></td>
                    </tr>
                    <tr>
                        <td><label for="usergroup">Expense/Supplier Name &nbsp;</label></td>
                        <td><label for="cname">Billing Date &nbsp;</label></td>
                    </tr>
                    <tr>
                        <td class="expenseentry"><select id='supplliername' name='supplliername' style="width:250px;">
                                <option selected="selected" value="">Select Expense Ledger</option>
                                <?php
                                $propid = $_SESSION['propertyid'];
                                $glaccountexp =  getLandlordExpenseAccounts(array('gl' => 'LandlordExpense', 'property_id' => $propid));
                                foreach ($glaccountexp as $expenseacct) {
                                    $glcode = $expenseacct['glcode'];
                                    $vat = $expenseacct['has_vat'];
                                    $propertyid = $expenseacct['property_id'];
                                    echo "<option value='$glcode' title='$propertyid' tab-index='$vat' class='supplier' >" . htmlspecialchars($expenseacct['acname']) . "</option>";
                                }
                                ?>
                            </select>

                        </td>
                        <td><input id="billdate" class="datepicker" value="<?php echo date('d/m/Y'); ?>" style="width:245px; height:20px;" />
                        </td>
                    </tr>

                    <tr>
                        <td><label for="cname">Expense =====>&nbsp;</label></td>
                        <td><input id="supplieditems" type="text" name="supplieditems" style="width:245px; height:20px;" /></td>
                    </tr>
                    <tr>
                        <td><label>Financial Period</label></td>
                        <td id="expenseperioddiv">
                            <select id='expenseperiod' name='expenseperiod'>
                                <?php $period = getPeriodByDate(date('d/m/Y'));
                                if (sizeof($period) > 0) {
                                ?>
                                    <option selected="selected" value="<?php echo $period['idclose_periods'] ?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])) . ' to ' . date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>
                                <?php } else { ?>
                                    <option selected="selected" value="">Financial Period Not Created Yet!</option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="cname">Total Amount owed=====> &nbsp;</label></td>
                        <td><input id="owedamount" type="text" name="owedamount" style="width:245px; height:20px;" /></td>
                    </tr>
                    <tr>
                        <td><label for="usergroup">Charge Fee As Income (%) &nbsp;</label></td>
                        <td><input id="feepercent" type="text" name="feepercent" style="width:105px; height:20px;" /><input id="totalcharges" type="text" name="totalcharges" placeholder="totalamount" style="width:105px; height:20px;border:1px solid green" /></td>
                    </tr>
                    <tr>
                        <td><label for="usergroup">Income Account &nbsp;</label></td>
                        <td>
                            <?php $incomeglss = getAgentIncomeGls(); ?>

                            <select id="agentlandlordexpenseincome">
                                <option value="" selected="selected">Select Income Account</option>
                                <?php foreach ($incomeglss as $value) {
                                    echo "<option value='" . $value['acno'] . "' >" . htmlspecialchars($value['acname']) . "</option>";
                                }  ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="remarks">Remarks (Expense Details)&nbsp;</label></td>
                        <td><textarea id="billremarks" name='billremarks' style="width:245px; height:100px; text-wrap:normal;"></textarea>
                            <br /><br />
                        </td>
                    </tr>
                    <tr>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center><input type="submit" id="btnreceivebill" class="text ui-widget-content ui-corner-aEll" value="CREATE EXPENSE ENTRY" style="width:250px; height:60px;font-weight:bold;" /></center>>
                        </td>
                    </tr>
                </table>
            </center>
        </fieldset>

    </form>
</div>
<!--s-->
<!-- pay bill -->
<div id="paybill" class="internalwindow" title="">
    <p class="titletr">Pay Bill<a href="#" style="float:right" id="closepbill">Close [X]</a></p>
    <p id="validatebill" class="validateTips3">All fields are required</p>

    <form id="newpbillform" method="post" enctype="multipart/form-data">
        <fieldset>
            <center>
                <table>
                    <tr>
                        <td><label for="usergroup">Expense/Supplier Name &nbsp;</label></td>
                        <td><label for="cname">Payment Date &nbsp;</label></td>
                    </tr>
                    <tr>
                        <td><select id='suppliernameselect' name='suppliernameselect' style="width:250px;">
                                <option selected="selected" value="">Select Expense/Supplier</option>
                                <?php
                                $propid = $_SESSION['propertyid'];
                                $glaccountexp =  getLandlordExpenseAccounts(array('gl' => 'LandlordExpense', 'property_id' => $propid));
                                foreach ($glaccountexp as $expenseacct) {
                                    $glcode = $expenseacct['glcode'];
                                    $propertyid = $expenseacct['property_id'];
                                    echo "<option value='$glcode' title='$propertyid' >" . htmlspecialchars($expenseacct['acname']) . "</option>";
                                }
                                //agent expenses
                                $agentexpense = getAgentExpenseAccount();
                                foreach ($agentexpense as $expenseacct1) {
                                    $glcode1 = $expenseacct1['glcode'];
                                    $propertyid1 = 0;
                                    echo "<option value='$glcode1' title='$propertyid1' class='supplier' >" . htmlspecialchars($expenseacct1['acname']) . "</option>";
                                }
                                ?>

                            </select></td>
                        <td><input id="paydate" class="datepicker" value="<?php echo date('d/m/Y'); ?>" style="width:245px; height:20px;" />
                        </td>
                    </tr>
                    <tr>
                        <td><label>Financial Period</label></td>
                        <td id="payperioddiv">
                            <select id='payperiod' name='payperiod'>
                                <?php $period = getPeriodByDate(date('d/m/Y'));
                                if (sizeof($period) > 0) {
                                ?>
                                    <option selected="selected" value="<?php echo $period['idclose_periods'] ?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])) . ' to ' . date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>
                                <?php } else { ?>
                                    <option selected="selected" value="">Financial Period Not Created Yet!</option>
                                <?php }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="cname">Payment Mode &nbsp;</label></td>
                        <td><label for="cname">Total Amount</label></td>
                    </tr>
                    <tr>
                        <td><select id="paymodebill" style="width:245px; height:20px; ">
                                <option selected="selected" value="">---</option>
                                <option value="0">CASH</option>
                                <option value="1">CHEQUE</option>
                                <option value="2">CREDIT CARD</option>
                                <option value="3">BANK DEPOSIT</option>
                                <option value="4">MPESA</option>
                            </select>
                        </td>
                        <td><input id="payamountbill" type="text" name="payamountbill" readonly="true" style="width:245px; height:20px; border:1px solid green " /></td>
                    </tr>
                    <input type="hidden" id="sequencepayments" /><input type="hidden" id="billnos" />
                    <tr>
                        <td style="color:grey;font-weight:bold">Chequedate =========> </td>
                        <td><input id="chequedatebill" type="text" name="chequedatebill" readonly="true" style="width:245px; height:20px; border:1px solid green " /></td>
                    </tr>
                    <tr>
                        <td><label id="chequedate" style="display:none;">Cheque Date &nbsp;</label></td>
                        <td><label id="cheqno" style="display:none;">Cheque No &nbsp;</label></td>
                    </tr>
                    <tr>
                        <td id="chequenotd" style="color:grey;font-weight:bold;display:none;">CHEQUE NO ====></td>
                        <td><input id="cheqnobill" style="width:245px; height:20px; display:none;" />
                        </td>
                    </tr>

                    <tr>
                        <td><label id="chequedetailslabel" style="display:none;">Cheque Details &nbsp;</label></td>
                        <td><label for="remarks">Remarks &nbsp;</label></td>
                    </tr>
                    <tr>
                        <td><textarea id="chequedetailsbill" style="width:245px; height:60px; text-wrap:normal;"></textarea>
                        </td>
                        <td><textarea id="remarksbill" style="width:245px; height:60px; text-wrap:normal;"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="cname">Expense Account ========> &nbsp;</label></td>
                        <td>
                            <select id='expenseacctbill' name='expenseacctbill' style='width:250px;'>
                                <?php
                                foreach ($glaccountexp as $expenseacct) {
                                    $glcode = $expenseacct['glcode'];
                                    $propertyid = $expenseacct['property_id'];
                                    echo "<option value='$glcode' title='$propertyid' >" . htmlspecialchars($expenseacct['acname']) . "</option>";
                                    foreach ($agentexpense as $expenseacct1) {
                                        $glcode1 = $expenseacct1['glcode'];
                                        $propertyid1 = 0;
                                        echo "<option value='$glcode1' title='$propertyid1' class='supplier' >" . htmlspecialchars($expenseacct1['acname']) . "</option>";
                                    }
                                } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="cname">Cash Account=======> &nbsp;</label></td>
                        <td id="paybillbanks"></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div id="pendingbills"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <center><input type="submit" id="btnpaybill" class="text ui-widget-content ui-corner-aEll" value="PAY BILL" style="width:200px;height:30px;font-weight:bold;" /></center>
                        </td>
                    </tr>
                </table>
            </center>
        </fieldset>

    </form>
</div>
<!--s-->
<!--deposit refund -->
<div id="depositrefund" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Deposit Refund<a href="#" style="float:right" id="closedepositrefund">Close [X]</a></p>
    <p id="validatebill" class="validateTips3">All fields are required</p>
    <table>
        <form id="depositrefundform" method="post" enctype="multipart/form-data">
            <tr>
                <td><label>Tenant </label></td>
                <td><select id='tenantnamedeposit' name='tenantnamedeposit' style="width:100%;">
                        <option selected="selected" value="">---</option>
                        <?php findtenantbypropertyid($_SESSION['propertyid']) ?>
                    </select></td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><label>Deposit</label></td>
                <td id="tenantdepositdiv">
                </td>
            </tr>
            <tr>
                <td><label>Refund date</label></td>
                <td><input type="text" id="depositrefunddate" style="width:90%;" class="datepicker"> </td>
            </tr>
            <tr>
                <td><label>Payment Mode</label></td>
                <td><select id="paymoderefund" style="width:90%; height:20px; ">
                        <option selected="selected" value="">---</option>
                        <option value="0">CASH</option>
                        <option value="1">CHEQUE</option>
                        <option value="2">CREDIT CARD</option>
                        <option value="3">BANK DEPOSIT</option>
                        <option value="4">MPESA</option>
                    </select></td>
            </tr>
            <tr>
                <td><label>Cheque date</label></td>
                <td><input type="text" id="chequedatedeposits" style="width:90%;" class="datepicker"> </td>
            </tr>
            <tr>
                <td><label>Cheque No</label></td>
                <td><input type="text" id="chequenorefund" style="width:90%;"> </td>
            </tr>
            <tr>
                <td><label>Cheque Details</label></td>
                <td><textarea id="chequedetailsdeposit" style="width:250px; height:60px; text-wrap:normal;"></textarea></td>
            </tr>
            <tr>
                <td><label>Remarks</label></td>
                <td><textarea id="refundremarks" style="width:250px; height:60px; text-wrap:normal;"></textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                    <center><input type="submit" id="btnrefunddeposit" class="text ui-widget-content ui-corner-aEll" value="REFUND DEPOSIT" style="width:200px;height:30px;font-weight:bold;" /></center>
                </td>
            </tr>
        </form>
    </table>
</div>


<!--Payments List-->
<div id="paymentslist" class="internalwindow" title="">
    <p class="titletr">Payments List<a href="#" id="closepaylist" style="float:right">Close [X]</a></p>
    <p id="validaterecp" class="validateTips3">All fields are required</p>

    <form id="paymentslistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <table>
                <tr>
                    <td><input type="radio" name="expenselist" class="blackfont expenselist" checked="checked" value="0"><span class="blackfont">Property Expense</span></td>
                    <td><input type="radio" name="expenselist" class="blackfont expenselist" value="2"><span class="blackfont">All Properties</span></td>
                    <td><input type="radio" name="expenselist" class="expenselist" value="1"><span class="blackfont">Office Expense</span></td>
                </tr>
                <tr>
                    <td>Expense</td>
                    <td><select name="expenseacct" id="expenseaccountsupplier">
                            <option value="0">Select Filter </option>
                            <?php $expenselists =  array_merge($glaccountexp, getAgentExpenseAccount());
                            foreach ($expenselists as $expense) { ?>
                                <option value="<?php echo $expense['glcode'] ?>"><?php echo $expense['acname'] ?></option>
                            <?php }
                            ?>


                            <?php

                            ?>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="cname">From Date &nbsp;</label></td>
                    <td><label for="cname">To Date</label></td>
                </tr>
                <tr id="fromtopay">
                    <td><input id="fromdatepay" name="fromdatepay" readonly style="width:200px; height:20px; border:1px solid green " />
                    </td>
                    <td><input id="todatepay" name="todatepay" readonly style="width:200px; height:20px; border:1px solid green " /></td>
                </tr>
                <tr>
                    <td>Use Financial Periods Instead?</td>
                    <td><input type="checkbox" id="usefperiodspay" value="1" /></td>
                </tr>
                <tr>
                    <td style="display:none">Financial Year</td>
                    <td style="display:none">
                        <?php $financialyears = getFinancialYears();
                        foreach ($financialyears as $financialyear) {
                        ?>
                            <select id="financialyearpay">
                                <option selected="selected">Select Financial Year</option>
                                <option value="<?php echo $financialyear['idfinancial_year'] ?>"><?php echo date("d-m-Y",  strtotime($financialyear['start_date'])) ?> TO <?php echo date("d-m-Y",  strtotime($financialyear['end_date'])) ?></option>
                            </select>

                        <?php }
                        ?>


                    </td>
                </tr>
                <tr>
                    <td>Close Period(Month)</td>
                    <td id="closeperiodpayments">

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <center><input type="submit" id="btnpaymentlist" class="text ui-widget-content ui-corner-aEll" value="VIEW PAYMENTS LIST" style="width:250px;height:30px;font-weight:bold;" /></center>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!--payments list Commissions-->
<div id="commissionswindow" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Commissions<a href="#" class="closewindow" style="float:right">Close [X]</a></p>

    <form id="paymentslistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <input id="fromdatecommission" class="datepicker" name="fromdatecommission" readonly placeholder="from date" style="height:20px; border:1px solid green " />
            <input id="todatecommission" class="datepicker" name="todatecommission" readonly placeholder="to date" style="height:20px; border:1px solid green " />
            <p></p>
            <center><input type="submit" id="commissionslist" class="text ui-widget-content ui-corner-aEll" value="COMMISSIONS REPORT" style="width:250px;height:30px;font-weight:bold;" /></center>
        </fieldset>
    </form>
</div>
<!--payments list -->

<div id="landlordstatementdiv" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">LandLord Statement<a href="#" id="closelandlordstatementdiv" style="float:right">Close [X]</a></p>

    <form id="paymentslistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <input id="fromdatelandlord" class="datepicker" name="fromdatelandlord" readonly placeholder="fromdate" style="height:20px; border:1px solid green " />
            <input id="todatelandlord" class="datepicker" name="todatelandlord" readonly placeholder="todate" style="height:20px; border:1px solid green " />
            <p></p>
            <center><input type="submit" id="btnlandlordstatement" class="text ui-widget-content ui-corner-aEll" value="LANDLORDS STATEMENT" style="width:250px;height:30px;font-weight:bold;" /></center>
        </fieldset>
    </form>
</div>
<!--payments list -->

<div id="agentstatementdiv" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Agent Statement<a href="#" id="closeagentstatementdiv" style="float:right">Close [X]</a></p>

    <form id="paymentslistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <input id="fromdateagent" class="datepicker" name="fromdateagent" readonly placeholder="fromdate" style="height:20px; border:1px solid green " />
            <input id="todateagent" class="datepicker" name="todateagent" readonly placeholder="todate" style="height:20px; border:1px solid green " />
            <p></p>
            <center><input type="submit" id="btnagentstatement" class="text ui-widget-content ui-corner-aEll" value="AGENT STATEMENT" style="width:250px;height:30px;font-weight:bold;" /></center>
        </fieldset>
    </form>
</div>
<!--payments list -->

<!--payments list -->

<div id="depositrefunddiv" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Deposit Refunds<a href="#" id="closedepositrefunddiv" style="float:right">Close [X]</a></p>

    <form id="paymentslistform" method="post" enctype="multipart/form-data">
        <fieldset>
            <input id="fromdatedeposits" class="datepicker" name="fromdate" readonly placeholder="fromdate" style="height:20px; border:1px solid green " />
            <input id="todatedeposits" class="datepicker" name="todate" readonly placeholder="todate" style="height:20px; border:1px solid green " />
            <p></p>
            <center><input type="submit" id="btndepositrefund" class="text ui-widget-content ui-corner-aEll" value="VIEW REPORT" style="width:250px;height:30px;font-weight:bold;" /></center>
        </fieldset>
    </form>
</div>
<!--payments list -->

<div id="paylandlorddiv" class="internalwindow normalwindow displaynone" title="">
    <p class="titletr">Pay Landlord<a href="#" id="closelandlordpaydiv" style="float:right">Close [X]</a></p>
    <table>
        <tr>
            <td><label for="amount">Landlord/Property Name. &nbsp;</label></td>
            <?php $allproperties =  getProperties();
            ?>
            <td><select id="propertytopay">
                    <!-- <option selected="selected">Select Landlord</option> -->
                    <option value="<?php echo $_SESSION['propertyid'] ?>" selected="selected"><?php echo propertyname($_SESSION['propertyid']) ?></option>
                    <?php
                    // foreach ($allproperties as $property) { 
                    ?>
                    <!-- <option value="<?php // echo $property['property_id'] 
                                        ?>"><?php //echo $property['property_name'] 
                                            ?></option>    -->
                    <?php //} 
                    ?>

                </select></td>
        </tr>
        <tr>
            <td>Amount</td>
            <td><input id="amountpaylandlord" name="amount" placeholder="amount" style="height:20px; border:1px solid green " /></td>
        </tr>

        <?php //echo print_r($_SESSION);
        ?>
        <?php
        require '../loan/admin_class.php';
        $crud = new Action();
        $loan = json_decode($crud->loan_next($_SESSION['propertyid']));
        ?>
        <?php
        if ($loan->success) {
            echo '<tr><td>Total Loan :</td><td>' . $loan->total_loan . '</td></tr>';
            echo '<tr><td>Total Amount Remaining:</td><td>' . $loan->balance . '</td></tr>';
            if (!$loan->ispaid) {
                echo '<tr><td>Loan Monthly Deduction:</td><td>' . $loan->amount . '</td></tr>';
            } else {
                echo '<tr><td>Loan Monthly Deduction:</td><td>Cleared</td></tr>';
            }
        } else {
            echo '<tr><td>Loan:</td><td>' . $loan->message . '</td></tr>';
        }
        ?>

        <tr>
            <td><label id="chequedetailslabel">Payment Reason &nbsp;</label></td>
            <td>
                <select style='width:250px;' id="pay-reason">
                    <option>Select Reason</option>
                    <?php
                    $data = queryResults("lpayment_reasons");
                    // echo "<script>a)</script>";
                    if ($data->count > 0) {
                        // die($data->data );
                        foreach ($data->data as $entry) {
                            echo "<option value='$entry->reason'>$entry->reason</option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <script>
                function closepayment() {
                    $("#addreason").hide();
                }

                function addpayment() {
                    $("#addreason").show();
                }
            </script>
            <?php
            if ($_SESSION['usergroup'] == "1") {
                echo '<td> <a href="javascript:void(0)" onclick="addpayment()" style="font-size:30px"> +</a></td>';
            } ?>
        </tr>
        <tr>
            <td>Payment Source</td>
            <td><select id='banktopayfrom' name='bankdepositpay' style='width:250px;'>
                    <option value='0' selected='selected'>Select Bank Account</option>
                    <?php
                    $bankspays =  getBanks("b");

                    foreach ($bankspays as $value) {
                        echo "<option value='" . $value['id'] . "' >" . htmlspecialchars($value['bank_name']) . "</option>";
                    }
                    ?>
            </td>
        </tr>
        <tr>
            <td>Payment Date</td>
            <td><input id="paymentdatelandlord" class="datepicker" name="paymentdatelandlord" readonly placeholder="todate" value="<?= date("d-m-Y") ?>" style="height:20px; border:1px solid green " /></td>
        </tr>
        <tr>
            <td><label id="chequedate">Cheque Date &nbsp;</label></td>
            <td><input id="paymentdatecheque" class="datepicker" name="paymentdatecheque" placeholder="todate" value="<?= date("d-m-Y") ?>" style="height:20px; border:1px solid green " /></td>
        </tr>
        <tr>
            <td id="chequenotd" style="color:grey;font-weight:bold;">CHEQUE NO ====></td>
            <td><input id="cheqnopay" style="width:245px; height:20px;" />
            </td>
        </tr>
        <tr>
            <td><label id="chequedetailslabel">Cheque Details &nbsp;</label></td>
            <td><textarea id="chequedetailspay" style="width:245px; height:60px; text-wrap:normal;"></textarea></td>
        </tr>


        <tr>
            <td></td>
            <td><br></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" id="btnpaylandlord" class="text ui-widget-content ui-corner-aEll" value="PROCESS PAYMENT" style="width:160px;font-weight:bold;" /></td>
        </tr>

        <?php //die("sasas") 
        ?>
    </table>
    <input type="hidden" id="loggedinproperty" value="<?php echo $_REQUEST['propertyid']; ?>">
    <?php if (!$_SESSION['propertyid']) {
        $_SESSION['propertyid'] = @$_REQUEST['propertyid'];
    } ?>
</div>
<!--payments list -->

<script type="text/javascript">
    $(document).ready(function() {
        $(".propertyid").change(function(e) {
            propid = $(".propertyid :selected").val();
            var myData = "";
            $.ajax({
                type: "POST",
                url: "modules/agentproperty.php?propertyid=" + propid,
                data: myData,
                success: function(data) { //reload div
                },
                error: function(data) {
                    //alert(data);
                }
            });

            window.location.replace("accounts.php?propertyid=" + propid);
        });

        $("#searchtenant").click(function(e) {
            e.preventDefault();

            if ($("#tenantnamesearch").val() !== "" || $("#housenosearch").val() !== "") {
                $tenantname = $("#tenantnamesearch").val();
                $houseno = $("#housenosearch").val();
                window.open("modules/defaultreports.php?report=tenantlist&search=true&tenantname=" + $tenantname + "&houseno=" + $houseno);
            } else {
                alert("Please input search parameters!");
            }
        })
    });
</script>