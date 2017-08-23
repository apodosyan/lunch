<?php
include_once 'includes/session.php';
include_once("config/accessConfig.php");
$loungeHead = "active";
$loungeDropDown = "display:block;";
$loungeMenu7 = "active";

$intLocationID=$_SESSION['loc'];
$accessToken=file_get_contents('http://www.softpointdev.com/api2/generatetoken.php');


function getLocationName($locid) {
    $sql = "SELECT name FROM locations where id=" . $locid;
    $rs = mysql_query($sql);
    $d = mysql_fetch_array($rs);
    $nameloc = $d["name"];
    return $nameloc;
}

function GetLocalTime($intLocationID){
    $H_jsonurl = API."API2/getlocationtime.php?intLocationID=".$intLocationID."&server_time=";
    $H_json = file_get_contents($H_jsonurl,0,null,null);
    $H_datetimenow= $H_json ;
    $H_datetimenowk1=explode(",",$H_datetimenow);
    $H_date_new = explode(":",$H_datetimenowk1['1']);
    $H_pieces = explode(" ",str_replace('"','',$H_date_new[1]));
    $H_date = $H_pieces[0];
    $H_time1 = $H_pieces[1].':'.$H_date_new[2];
    return $H_date.' '.$H_time1;
}


?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $_SESSION['SITE_TITLE']; ?></title>

    <link rel="stylesheet" href="css/style.default.css"  type="text/css" />
    <link rel="stylesheet" href="css/custom.report.css"  type="text/css" />

    <style type="text/css">
        .ui-datepicker-calendar tr td a{ padding:3px 8px; text-align:center;}
        .ui-datepicker-calendar tr td span{ padding:3px 8px; text-align:center;}
        .ui-datepicker-month{width:33% !important; margin-right:5% !important;}
        .ui-datepicker-year{width:33% !important;}
        .ui-state-disabled {
            background-color: #c6c6c6 !important;
            padding: 2px 1px !important;
        }

        .searchbar
        {
            right: 0px !important;
        }

    </style>

    <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.alerts.js"></script>



</head>


<body style="top:0px;">

<div id="loading" style="display:none;"><img id="loading-image" src="images/loaders/loader7.gif" alt="Loading..." /></div>

<div class="mainwrapper">

    <?php include_once 'includes/header.php';?>

    <div class="leftpanel">

        <?php include_once 'includes/left_menu.php';?>

    </div><!-- leftpanel -->

    <div class="rightpanel">

        <ul class="breadcrumbs">
            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li class="capital_word">Lounges<span class="separator"></span></li>
            <li class="capital_word">Reports</li>

            <li class="right">
                <a href="" data-toggle="dropdown" class="dropdown-toggle capital_word"><i class="icon-tint"></i><?=$_SESSION['Color']?> <?=$_SESSION['Skins']?></a>
                <ul class="dropdown-menu pull-right skin-color">
                    <li><a href="default">Default</a></li>
                    <li><a href="navyblue">Navy Blue</a></li>
                    <li><a href="palegreen">Pale Green</a></li>
                    <li><a href="red">Red</a></li>
                    <li><a href="green">Green</a></li>
                    <li><a href="brown">Brown</a></li>
                </ul>
            </li>
            <?php require_once("lang_code.php");?>



        </ul>

        <div class="pageheader">
            <form action="" method="post" class="searchbar" onSubmit="return false;">
                <input type="hidden" id="table-id" name="table-id" value="" />

                <div id="additionalButtons" style="display: inline-block; visibility: hidden;">
                    <a href="javascript:void(0);" onClick="savePDF();" id="a_save_btn"><button class="btn btn-success btn-mini">Save</button> </a> &nbsp;
                    <a data-toggle="modal" href="#mailmodal" id="a_mail_btn"><button class="btn btn-success btn-mini">Email</button> </a> &nbsp;
                    <a href="javascript:void(0);" onClick="printDiv('')" id="a_print_btn"><button class="btn btn-success btn-mini">Print</button> </a> &nbsp;
                </div>


                <select id="report_select" class="buttonReport" style="width: 65.5%; margin-top: 4px;">
                    <option value="" selected="selected" disabled="disabled">---Select a Report---</option>
                    <option value="Sales Report">Sales Report</option>
                    <option value="Total Hours of SL Time Purchased">Total Hours of SL Time Purchased</option>
                    <option value="New Memberships Paid">Unused- New Memberships Paid</option>
                    <option value="Returning Members Including Additional Transactions on Same Day">Returning Members Including Additional Transactions on Same Day</option>
                    <option value="Returning Members with Visit Breakdown">Returning Members with Visit Breakdown</option>
                    <option value="Returning Members Not Same day">Returning Members Not Same day</option>
                    <option value="Number of Visits with Additional Transactions">Number of Visits with Additional Transactions</option>
                    <option value="Member Registered by which Method">Member Registered by which Method</option>
                    <option value="Members by Number of Visits on Multiple dates">Members by Number of Visits on Multiple Dates</option>
                    <option value="Non Returning Members">Non Returning Members</option>
                    <option value="Members List">Members List</option>
                    <option value="Members List By Birthdate">Members List by Birthdate</option>
                    <option value="Members By Zip Code">Members by Zip Code</option>
                    <option value="Members By Gender">Members by Gender</option>
                    <option value="Additional Transactions">Additional Transactions</option>
                    <option value="Members Registered But Not Visited">Members Registered But Not Visited</option>
                    <option value="Upgraded SL Time Purchased">Unused- Upgraded SL Time Purchased</option>
                    <option value="Registered Members vs. Paid Members Conversion Rate">Registered Members vs. Paid Members Conversion Rate</option>
                    <option value="Ranked Game Plays">Ranked Game Plays</option>
                    <option value="Type Game Played">Type Game Played</option>
                    <option value="Type Game Played By Group">Type Game Played by Group</option>
                    <option value="Type Game Played by Gender">Type Game Played by Gender</option>
                    <option value="RPT Temp Reg Duplicate Email">Unused- RPT Temp Reg Duplicate Email</option>
                </select>

                <input type="hidden" id="hiddenURL" value="<?php
                $txt=$_SERVER["SCRIPT_NAME"];

                $re1='(\\/)'; # Any Single Character 1
                $re2='((?:[a-z][a-z]+))'; # Word 1
                $re3='(\\/)'; # Any Single Character 2
                $re4='((?:[a-z][a-z0-9_]*))'; # Variable Name 1

                if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4."/is", $txt, $matches)) {
                    $c1=$matches[1][0];
                    $word1=$matches[2][0];
                    $c2=$matches[3][0];
                    $var1=$matches[4][0];
                    $c3=$matches[5][0];
                    $url = $c1."".$word1."".$c2."".$var1."".$c3;

                    //string $url = $c1.$word1.$c2.$var1.$c3;
                    echo $_SERVER['SERVER_NAME']. $url .'/';
                }
                ?>" />

            </form>
            <div class="pageicon"><span class="iconfa-bullseye"></span></div>
            <div class="pagetitle">
                <h5>All of of your Lounge Reports</h5>
                <h1>Lounge Reports</h1>
            </div>
        </div>

        <div class="maincontent">
            <div id="printableDiv" class="maincontentinner">

                <div class="report-header" style="display:none;">
                    <span class="tabname"> <?=$_SESSION['user_full_name'];?> - <?=$_SESSION["user"];?></span>
                    <div class="tabtitle"></div>
                    <br/>
                    <span class="title_location tablocation"> <?php echo getLocationName($_SESSION["loc"]); ?> (ID#: <?=$_SESSION["loc"];?>)</span><span class="tabdate"> <?php echo GetLocalTime($intLocationID);/*date("m/d/Y");*/?></span>
                </div>

                <div  class="widget">
                    <div id="report_filter" class="widget-sort" style="display:none;">
                        <select id="site_loaction" name="site_loaction">
                            <option value="0">- - - Select Location - - -</option>
                        </select>
                        <input type="text" id="starttime" name="starttime" placeholder="Start Date" />
                        <input style="display: none;" type="text" id="starttime_bday" name="starttime" placeholder="Start Date" />
                        <input  type="text" id="endtime" name="endtime" placeholder="End Date" />
                        <input style="display: none;" type="text" id="endtime_bday" name="endtime" placeholder="End Date" />
                        <input style="display: none;" type="text" id="r_numVisits" name="r_numVisits" placeholder="Number of visits From" />
                        <input style="display: none;" type="text" id="r_numVisitsTo" name="r_numVisits" placeholder="Number of visits To" />
                        <input style="display: none;" type="text" id="r_createdDate" name="createdDate" placeholder="Created Date" />
                        <select id="r_status" style="display: none;" >
                            <option value="0">--All--</option>
                            <option value="active" selected="selected">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <input type="text" id="r_search" name="r_search" placeholder="Search" />
                        <button id="report_submit" name="report_submit" class="btn btn-success" style="margin-bottom: 10px; padding-bottom: 3px;">Submit</button>
                    </div>

                    <h4 class="widgettitle" style="min-height:17px;">&nbsp;</h4>
                    <div class="widgetcontent">
                        <div id="report_data">
                        </div>
                        <div id="default_message" style="font-size:20px;font-weight:bold; text-align:center;height:25px;overflow:auto;">Please Select a Report</div>
                        <div id="no_data_message" style="font-size:20px;font-weight:bold; text-align:center;height:25px;overflow:auto; display: none;">No data</div>
                    </div><!--widgetcontent-->
                </div>



                <?php include_once 'includes/footer.php';?>
            </div>
        </div>
    </div>
</div>

<div id="savePdfContainer" style="display: none;"></div>


<?php
$location = $_SESSION['loc_name'];
?>
<div class="modal fade" id="mailmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Send Report</h4>
            </div>
            <div class="modal-body">
                <div id="content-modal"></div>
                <form name="fsendmail" method="POST" action="">
                    <!--<textarea name="addresslist" rows="2" style="width:100%;-moz-resize: none; -webkit-resize: none; resize: none;">    </textarea>-->
                    <p>Email address</p>
                    <input type="text" name="mailaddress" id="mailaddress" style="width:75%;" value="">
                    <p>Name</p>
                    <input type="text" name="destname" id="destname" style="width:75%;" value="">
                    <input type="hidden" name="loc_name" id="loc_name" value="<?php echo $location; ?>">
                    <input type="hidden" name="current_date" id="current_date" value="<?php echo date("Y-m-d", strtotime(GetLocalTime($intLocationID))); ?>">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onClick="sendMail();" class="btn btn-primary">Send Mail</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>


<div id="_print" style="display:none" ></div>


<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.printElement.min.js"></script>

<script type="text/javascript" src="js/jsPdf/jspdf.min.js"></script>
<script type="text/javascript" src="js/jsPdf/libs/addimage.js"></script>
<script type="text/javascript" src="js/jsPdf/libs/cell.js"></script>
<script type="text/javascript" src="js/jsPdf/libs/from_html.js"></script>
<script type="text/javascript" src="js/jsPdf/libs/javascript.js"></script>
<script type="text/javascript" src="js/jsPdf/libs/svg.js"></script>
<script type="text/javascript" src="js/jsPdf/libs/split_text_to_size.js"></script>
<script type="text/javascript" src="js/jsPdf/libs/standard_fonts_metrics.js"></script>

<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/lounges_data.js"></script>

<script type="text/javascript">
    var Global_access   = '<?php echo $accessToken; ?>';
    var apiphp        = '<?php echo APIPHP; ?>';

    jQuery(document).ready(function(e) {




        setTimeout(function(){
            var winSize = jQuery(window).height();


            if(jQuery('.rightpanel').height() < winSize) {
                jQuery('.rightpanel').height(winSize);
            }

            jQuery('.rightpanel').css({ height: 'auto' });
        },500);

        jQuery('#endtime').datepicker({
            changeMonth: true,
            dateFormat:"yy-mm-dd",
            changeYear: true,
            yearRange: '1950:' + (new Date().getFullYear()+1) ,
            onSelect: function(dateText, inst) {
                if(jQuery('#report_select').val() == 'Members List By Birthdate'){
                    var starttime = jQuery('#starttime').val();
                    var endtime = jQuery('#endtime').val();

                    var startDate = jQuery('#starttime').datepicker('getDate');
                    var endDate =  jQuery('#endtime').datepicker('getDate');

                    var diff=( endDate -startDate)/ (24*3600*1000);
                    if(diff>90)
                    {
                        jAlert('Selection cannot be greater than 90 days.', 'Alert');
                        return false;
                    }
                }
            }
        });




        jQuery('#starttime').datepicker({
            changeMonth: true,
            dateFormat:"yy-mm-dd",
            changeYear: true,
            yearRange: '1950:' + (new Date().getFullYear()+1) ,
            onSelect: function(dateText, inst) {
                if(jQuery('#report_select').val() == 'Members List By Birthdate'){
                    var starttime = jQuery('#starttime').val();
                    var endtime = jQuery('#endtime').val();

                    var startDate = jQuery('#starttime').datepicker('getDate');
                    var endDate =  jQuery('#endtime').datepicker('getDate');

                    var diff=( endDate -startDate)/ (24*3600*1000);
                    if(diff>90)
                    {
                        jAlert('Selection cannot be greater than 90 days.', 'Alert');
                        return false;
                    }
                }
            }
        });
        //
        jQuery('#starttime_bday').datepicker({
            changeMonth: true,
            dateFormat:"mm-dd",
            changeMonth: true,
            changeYear: true,
            onSelect: function(dateText, inst) {
                if(jQuery('#report_select').val() == 'Members List By Birthdate'){
                    var starttime = jQuery('#starttime_bday').val();
                    var endtime = jQuery('#endtime_bday').val();

                    var startDate = jQuery('#starttime_bday').datepicker('getDate');
                    var endDate =  jQuery('#endtime_bday').datepicker('getDate');

                    var diff=( endDate -startDate)/ (24*3600*1000);
                    if(diff>90)
                    {
                        jAlert('Selection cannot be greater than 90 days.', 'Alert');
                        return false;
                    }
                }
            }
        });


        jQuery('#endtime_bday').datepicker({
            changeMonth: true,
            dateFormat:"mm-dd",
            changeYear: true,
            onSelect: function(dateText, inst) {
                if(jQuery('#report_select').val() == 'Members List By Birthdate'){
                    var starttime = jQuery('#starttime_bday').val();
                    var endtime = jQuery('#endtime_bday').val();

                    var startDate = jQuery('#starttime_bday').datepicker('getDate');
                    var endDate =  jQuery('#endtime_bday').datepicker('getDate');

                    var diff=( endDate -startDate)/ (24*3600*1000);
                    if(diff>90)
                    {
                        jAlert('Selection cannot be greater than 90 days.', 'Alert');
                        return false;
                    }
                }
            }
        });


        jQuery('#r_createdDate').datepicker({
            changeMonth: true,
            dateFormat:"mm-dd-yy",
            changeYear: true,
            maxDate: 0
        });

        var d=new Date();

        var month=d.getMonth()+1;
        var dateStr= d.getFullYear() + '-'+month+'-'+ d.getDate();

        jQuery('#starttime').datepicker('setDate',dateStr);
        jQuery('#starttime_bday').datepicker('setDate',dateStr);

        jQuery('#endtime').datepicker('setDate',dateStr);
        jQuery('#endtime_bday').datepicker('setDate',dateStr);

        set_site_location_options();

        jQuery('#report_select').change(function(){

            var report_val=jQuery(this).val();
            var loc_name=jQuery("#loc_name").val();
            var current_date=jQuery("#current_date").val();

            jQuery("#destname").val(loc_name+" - "+report_val+" Report - "+current_date);

            createCookie("last_report",report_val);

            var d=new Date();
            // var dateStr= d.getMonth()+1 + '-'+d.getDate()+'-'+ d.getFullYear();
            var month=d.getMonth()+1;
            var dateStr= d.getFullYear() + '-'+month+'-'+ d.getDate();

            jQuery('#starttime').datepicker('setDate',dateStr);
            jQuery('#starttime_bday').datepicker('setDate',dateStr);

            jQuery('#endtime').datepicker('setDate',dateStr);
            jQuery('#endtime_bday').datepicker('setDate',dateStr);

            jQuery('#site_loaction').val('0');
            jQuery('#r_numVisits').hide();
            jQuery('#r_numVisitsTo').hide();
            jQuery('#r_status').hide();
            jQuery('#r_createdDate').hide();
            jQuery('#starttime').attr('placeholder','Start Date');
            jQuery('#endtime').attr('placeholder','End Date');
            jQuery('#starttime').show();
            jQuery('#starttime_bday').hide();
            jQuery('#endtime').show();
            jQuery('#endtime_bday').hide();
            jQuery('#r_search').val('');




            if(jQuery('#report_select option:selected').val()=='Number of Visits with Additional Transactions')
            {
                jQuery('#r_numVisits').show();
                jQuery('#r_numVisitsTo').show();
            }
            if(jQuery('#report_select option:selected').val()=='Members List')
            {
                jQuery('#r_status').show();
                jQuery('#r_createdDate').hide();
                var dateStr = '06-01-2016';
                jQuery('#r_createdDate').datepicker('setDate',dateStr);
                jQuery('#starttime').show();
                jQuery('#endtime').show();
            }

            if(jQuery('#report_select option:selected').val()=='Members List By Birthdate' )
            {

                jQuery('#starttime').hide();
                jQuery('#endtime').hide();

                jQuery('#starttime_bday').show();
                jQuery('#endtime_bday').show();

                jQuery('#starttime_bday').attr('placeholder','From');
                jQuery('#endtime_bday').attr('placeholder','To');
                d = new Date();
                var dateStr= d.getMonth()+1 + '-'+d.getDate();

                jQuery('#starttime_bday').datepicker('setDate',dateStr);

                jQuery('#endtime_bday').datepicker('setDate',dateStr);

            }

            if(jQuery('#report_select option:selected').val()== 'Additional Transactions' )
            {

                jQuery('#starttime').attr('placeholder','From');
                jQuery('#endtime').attr('placeholder','To');

            }

            if(!jQuery('#report_select option:selected').val()=='Members List By Birthdate' )
            {
                jQuery('#starttime').show();
                jQuery('#endtime').show();

                jQuery('#starttime_bday').hide();
                jQuery('#endtime_bday').hide();
            }

            if(jQuery('#report_select option:selected').val()=='Non Returning Members')
                jQuery('#endtime').hide();

            jQuery('#lastInfoTableRow table tbody').empty();
            jQuery('.tabtitle').html(jQuery('#report_select').val());

            hs_default_message();

            jQuery('#report_data').html('');

        });

        jQuery('#r_search').keyup(function(){
            console.log('#r_search keyup ...');
            var s_val = jQuery('#r_search').val();

            if(s_val == ''){
                jQuery('#dyntable tr').show();
                return false;
            }

            jQuery('#dyntable tr').each(function(i, v) {
                //console.log('v');
                //console.log(jQuery(v).html());

                //console.log('s_val');
                //console.log(s_val);
                var show = false;
                jQuery(jQuery(this).children('td')).each(function(i2, v2) {
                    //console.log('v2');
                    //console.log(jQuery(v2).html());

                    if(jQuery(v2).html() == s_val){
                        console.log(v2);
                        show = true;
                    }
                });

                //console.log('show');
                //console.log(show);
                if(show){
                    jQuery(v).show();
                } else {
                    jQuery(v).hide();
                }
            });
            jQuery('#dyntable tr th').parent('tr').show();
        });

        jQuery('#report_submit').click(function(){

            jQuery('#additionalButtons').css('visibility','visible');

            var siteid = ( jQuery('#site_loaction').val() ) ? jQuery('#site_loaction').val() : '1';

            var starttime = ( jQuery('#starttime').val() ) ? jQuery('#starttime').val() : '10-20-2014';
            var starttime_bday = ( jQuery('#starttime_bday').val() ) ? jQuery('#starttime_bday').val() : '10-20-2014';

            var endtime = ( jQuery('#endtime').val() ) ? jQuery('#endtime').val() : '<?php echo date('m-d-Y'); ?>';
            var endtime_bday = ( jQuery('#endtime_bday').val() ) ? jQuery('#endtime_bday').val() : '<?php echo date('m-d-Y'); ?>';


            /*     var starttime = jQuery('#starttime').datepicker('getDate');
             var endtime =  jQuery('#endtime').datepicker('getDate');

             starttime=starttime.getFullYear()+'-'+ (starttime.getMonth()+1)+'-'+starttime.getDate();
             endtime=endtime.getFullYear()+'-'+ (endtime.getMonth()+1)+'-'+endtime.getDate();
             */

            var source = 'Website';
            var gametype = 'arcade';

            /*
             var startDate = new Date(starttime);
             var endDate = new Date(endtime);  */


            var startDate = jQuery('#starttime').datepicker('getDate');

            var startDate_bday = jQuery('#starttime_bday').datepicker('getDate');

            var endDate =  jQuery('#endtime').datepicker('getDate');

            var endDate_bday =  jQuery('#endtime_bday').datepicker('getDate');



            if(!jQuery('#report_select option:selected').val()=='Members List By Birthdate' )
            {
                if (startDate > endDate) {
                    jAlert('Start Time is after End Time. Please revise search!', 'Alert');
                    return false;
                }
            }


            if(jQuery('#report_select').val() == 'Sales Report'){
                RPTSales(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Total Hours of SL Time Purchased'){
                RPTSLTimePurchase(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'New Memberships Paid'){
                RPTSLNewMembershipsPaid(siteid, starttime, endtime, source);
            }
            if(jQuery('#report_select').val() == 'Returning Members Including Additional Transactions on Same Day'){
                var diff=( endDate -startDate)/ (24*3600*1000);
                GetReturningMembers(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Returning Members Not Same day'){
                GetReturningMembersNotSameDay(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Returning Members with Visit Breakdown'){
                GetReturningMembersWithDetails(siteid, starttime, endtime);
            }

            if(jQuery('#report_select').val() == 'Number of Visits with Additional Transactions'){

                if(jQuery('#r_numVisits').val() =="")
                {
                    jAlert('Please enter number of Visits', 'Alert');
                    return false;

                }
                else
                    GetMembersByNumberOfVisits(siteid, starttime, endtime);
            }

            if(jQuery('#report_select').val() == 'Members Registered But Not Visited'){
                GetMembersRegisteredButNotVisited(siteid, starttime, endtime);
            }




            if(jQuery('#report_select').val() == 'Members by Number of Visits on Multiple dates'){


                GetMembersByNumberOfVisitsOnMultipleDates(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Member Registered by which Method'){


                GetMembersByType(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Non Returning Members'){

                var todays = new Date();
                todays.setHours(0,0,0,0);

                if (startDate.getTime()===todays.getTime() )
                {
                    jConfirm('Using Todays date will display practically all members, Would you like to proceed?', 'Non Returning Members', function(r){
                        if(r){
                            GetNonReturningMembers(siteid, starttime, endtime);
                        }
                    });

                }else
                    GetNonReturningMembers(siteid, starttime, endtime);
            }

            if(jQuery('#report_select').val() == 'Members List')
            {
                if (jQuery('#r_status option:selected').val() == '0' && jQuery('#starttime').val() == '' && jQuery('#endtime').val() == '')
                {
                    jConfirm('Using status --All-- will display practically all members, Would you like to proceed?', 'Member List', function(r){
                        if(r){
                            GetMembersList(siteid, starttime, endtime);
                        }
                    });

                }else
                    GetMembersList(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Members List By Birthdate')
            {
                if(jQuery('#starttime_bday').val() =="")
                {
                    jAlert('Please provide From Date', 'Alert');
                    return false;
                }
                if(jQuery('#endtime_bday').val() =="")
                {
                    jAlert('Please provide To Date', 'Alert');
                    return false;
                }

                var diff=( endDate_bday -startDate_bday)/ (24*3600*1000);

                if(diff>90)
                {
                    jAlert('Selection cannot be greater than 90 days.', 'Alert');
                    return false;
                }else
                    GetMembersListByBirthday(siteid, starttime_bday, endtime_bday);
            }
            if(jQuery('#report_select').val() == 'Additional Transactions'){
                if(jQuery('#starttime').val() =="")
                {
                    jAlert('Please provide From Date', 'Alert');
                    return false;
                }
                if(jQuery('#endtime').val() =="")
                {
                    jAlert('Please provide To Date', 'Alert');
                    return false;
                }

                GetUpgradedMembersList(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Members By Zip Code'){
                GetMembersByZipcode(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Members By Gender'){
                GetMembersByGender(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Upgraded SL Time Purchased'){
                RPTSLUpgradedTimePurchased(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Registered Members vs. Paid Members Conversion Rate'){
                RPTSLRegVSPaidMember(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Ranked Game Plays'){
                RPTSLRankedGamePlays(siteid, starttime, endtime);
            }
            if(jQuery('#report_select').val() == 'Type Game Played')
            {

                if(jQuery('#starttime').val() =="")
                {
                    jAlert('Please provide From Date', 'Alert');
                    return false;
                }
                if(jQuery('#endtime').val() =="")
                {
                    jAlert('Please provide To Date', 'Alert');
                    return false;
                }

                /* var diff=( endDate -startDate)/ (24*3600*1000);
                 if(diff>30)
                 {
                 jAlert('Selection cannot be greater than 30 days.', 'Alert');
                 return false;
                 }*/
                typedGamePlayedByMember(siteid, starttime, endtime);
            }

            if(jQuery('#report_select').val() == 'Type Game Played By Group')
            {

                if(jQuery('#starttime').val() =="")
                {
                    jAlert('Please provide From Date', 'Alert');
                    return false;
                }
                if(jQuery('#endtime').val() =="")
                {
                    jAlert('Please provide To Date', 'Alert');
                    return false;
                }


                typedGamePlayedByMember_Group(siteid, starttime, endtime);
            }

            if(jQuery('#report_select').val() == 'Type Game Played by Gender'){
                typedGamePlaysByGender(siteid, starttime, endtime);
                //RPTSLTypedGamePlaysByGender(siteid, starttime, endtime,gametype);
            }

            if(jQuery('#report_select').val() == 'RPT Temp Reg Duplicate Email'){
                GetTempMembers(siteid, starttime, endtime);
            }

            hs_default_message();


        });

        var options = jQuery('select#report_select option');
        var arr = options.map(function(_, o) { return { t: jQuery(o).text(), v: o.value }; }).get();
        arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
        options.each(function(i, o) {
            o.value = arr[i].v;
            jQuery(o).text(arr[i].t);
        });

        /*jQuery('select#report_select').prepend('<option selected="selected" disabled="disabled">---Select a Report---</option>'); */

        var last_report=getCookie("last_report");

        jQuery("#report_select").val(last_report).change();


    });

    function set_site_location_options(){

        console.log('set_site_location_options ...'+LoginJoinAPI);
        var url = LoginJoinAPI+"/sitelocations";
        jQuery.ajax({
            url: url,
            headers: { apicode: apicode },
            data: {  },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {
                console.log('before SEnded');
            },
            complete: function () {
                console.log('completed SEnded');
            },
            success: function (result) {

                console.log('set_site_location_options success: function (result) ...');
                console.log(result);

                var opts = '<option value="0">- - - Select Location - - -</option>';
                jQuery.each(result, function(i, v){
                    opts += '<option value="'+ v.siteid +'">'+ v.sitename +'</option>';
                });
                jQuery('#site_loaction').html(opts);
            },
            error: function (data) {
                console.log(JSON.stringify(data));
            }
        });
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function secondsToHMConversion(s) {


        var h = Math.floor(s/3600); //Get whole hours
        s -= h*3600;
        var m = Math.floor(s/60); //Get remaining minutes
        s -= m*60;
        s=parseInt(s);
        return (h < 10 ? '0'+h : h)+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s); //zero padding on minutes and seconds
    }

    function secondsToDHMConversion(s) {

        var d = Math.floor(s/86400);
        s -= d*86400;
        var h = Math.floor(s/3600); //Get whole hours
        s -= h*3600;
        var m = Math.floor(s/60); //Get remaining minutes
        s -= m*60;
        return (d < 10 ? '0'+d : d)+":"+(h < 10 ? '0'+h : h)+":"+(m < 10 ? '0'+m : m); //zero padding on minutes and seconds
    }

    function minutesToHMConversion(x) {


        var days =Math.floor(Math.floor(Math.floor(x/60)/60)/24)%24;      //DAYS
        var hours =Math.floor(Math.floor(x/60)/60)%60;
        var seconds =Math.floor(x/60)%60;


        if(days <10)
            days='0'+days;

        if(hours <10)
            hours='0'+hours;

        if(seconds <10)
            seconds='0'+seconds;                                   //MINUTES

        return  days+':'+ hours;

    }

    function RPTSales(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportSalesTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="6%" align="left" class="black3_bo" style="padding-left:10px;">Date</th>';
        report_table += '<th width="6%" align="left" class="black3_bo" style="padding-left:10px;">Time</th>';
        report_table += '<th width="6%" align="left" class="black3_bo" style="padding-left:10px;">Lounge</th>';
        report_table += '<th width="12%" align="left" class="black3_bo" style="padding-left:10px;">Member</th>';
        report_table += '<th width="12%" align="left" class="black3_bo" style="padding-left:10px;">Experience s</th>';
        report_table += '<th width="12%" align="left" class="black3_bo" style="padding-left:10px;">No. of Guest</th>';
        report_table += '<th width="12%" align="left" class="black3_bo" style="padding-left:10px;">Total Play Time</th>';
        report_table += '<th width="12%" align="left" class="black3_bo" style="padding-left:10px;">Amount</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_sales_report.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteid: siteid,
                startdate: starttime,
                enddate: endtime
            },
            type: 'POST',
            crossDomain: true,
            //   dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {
                try{
                    result=JSON.parse(result);
                }catch(e){

                    var str_index= result.indexOf('{');
                    var str = result.substring(str_index);
                    try{
                        result=JSON.parse(str);
                    }catch(e){

                        var siteid = ( jQuery('#site_loaction').val() ) ? jQuery('#site_loaction').val() : '1';
                        var starttime = ( jQuery('#starttime').val() ) ? jQuery('#starttime').val() : '10-20-2014';
                        var endtime = ( jQuery('#endtime').val() ) ? jQuery('#endtime').val() : '<?php echo date('m-d-Y'); ?>';
                        RPTSales(siteid, starttime, endtime);
                    }

                }

                var data = result.response;

                if(data.length != 0)
                {

                    var t_guest=0;
                    var t_amount=0;
                    var t_hours=0;
                    var t_mins=0;
                    jQuery.each(data, function(i, v){
                        if(v.ExperienceName == 'Unlimited' || v.ExperienceName == '5 Mins' ){
                            v.ExperienceAmount = '0.00';
                        }

                        if(typeof v.StartTime['date'] =='undefined')
                            var st_d=v.StartTime;
                        else
                            var st_d= v.StartTime['date'];

                        st_d=st_d.toString().split('.')[0];
                        var s_d=new Date(st_d);

                        if(typeof v.EndTime['date'] =='undefined')
                            var ed_d=v.EndTime;
                        else
                            var ed_d=v.EndTime['date'];

                        ed_d=ed_d.toString().split('.')[0];


                        var e_d=new Date(ed_d);

                        t_guest+=parseInt(v.coversCount);

                        /*var hour = Math.abs(e_d - s_d)/36e5;

                         var intHour = parseInt(hour);

                         if(intHour >0)
                         var mins = (hour / intHour)*30;
                         else
                         {
                         var mins = hour*30;
                         }*/

                        var diffMs = (e_d - s_d);
                        var intHour = Math.floor((diffMs % 86400000) / 3600000);
                        var mins = Math.round(((diffMs % 86400000) % 3600000) / 60000);

                        t_hours+=parseInt(intHour);
                        t_mins+=parseInt(mins);
                        if(diffMs == '0' && v.ExperienceName=='5 Mins'){
                            mins = '5';
                        }
                        var duration=  intHour+'h '+mins+'m';

                        var amount = parseFloat(v.ExperienceAmount);
                        amount=amount.toFixed(2);

                        t_amount+=parseFloat(v.ExperienceAmount);
                        if(typeof v.CreatedDate['date'] =='undefined')
                            var st_time=v.CreatedDate;
                        else
                            var st_time=v.CreatedDate['CreatedDate'];

                        st_time=st_time.toString().split('.')[0];
                        var st_date=st_time.toString().split(' ')[0];
                        var st_Time=st_time.toString().split(' ')[1];
                        st_Time = st_Time.toString().split(':')[0]+':'+st_Time.toString().split(':')[1];
                        //	if(v.ExperienceName == 'Unlimited' || v.ExperienceName == '5 Mins' ){
//								amount = '0.00';
//							}
                        jQuery('#reportSalesTable tbody').append('<tr class="whitebgcolor">\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+st_date+'</td>\
							<td align="left" class="black3_bo" style="padding-left:10px;">'+st_Time+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.BayName+'</td>\
							<td align="left" class="black3_bo" style="padding-left:10px;">'+v.FirstName+' '+v.LastName+'</td>\
							<td align="left" class="black3_bo" style="padding-left:10px;">'+v.ExperienceName+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.coversCount+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+duration+'</td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;">'+amount+'</td>\
                            </tr>');
                    });



                    var moreHours= parseInt(t_mins/60);
                    if(moreHours >0)
                        t_mins = t_mins - (moreHours*60)

                    // t_mins.toFixed(0);
                    t_hours+=moreHours;

                    var totalDuration =  numberWithCommas(t_hours)+'h '+t_mins+'m';



                    jQuery('#reportSalesTable').append('<tfoot></tfoot>');

                    jQuery('#reportSalesTable tfoot').append('<tr class="whitebgcolor">\
                        <td width="6%" align="left" class="black3_bo" style="padding-left:10px;"><b>Total</b></td>\
                        <td width="6%" align="left" class="black3_bo" style="padding-left:10px;"><b>'+numberWithCommas(data.length)+'</b></td>\
                        <td width="6%" align="left" class="black3_bo" style="padding-left:10px;"></td>\
                        <td width="12%" align="left" class="black3_bo" style="padding-left:10px;"></td>\
						<td width="12%" align="left" class="black3_bo" style="padding-left:10px;"></td>\
                        <td width="12%" align="left" class="black3_bo" style="padding-left:10px;"><b>'+numberWithCommas(t_guest)+'</b></td>\
                        <td width="12%" align="left" class="black3_bo" style="padding-left:10px;"><b>'+totalDuration+'</b></td>\
                        <td width="12%" class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(t_amount.toFixed(2))+'</b></td>\
                        </tr>');



                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();
                }else
                    jQuery('#additionalButtons').css('visibility','hidden');


                jQuery('#reportSalesTable').dataTable({
                    "sPaginationType": "full_numbers",
                    'iDisplayLength': 10000000,
                    "fnDrawCallback": function (oSettings) {

                        jQuery('#reportSalesTable_length').html('');
                        jQuery('#reportSalesTable_length').height(32);
                        jQuery('#reportSalesTable_paginate').html('');
                        jQuery('#reportSalesTable_info').html('').hide();
                    }
                });

                jQuery('#reportSalesTable_filter label').contents().filter(function() {
                    return this.nodeType == 3
                }).each(function(){
                    this.textContent = this.textContent.replace('Search','Search from Results ');
                });



            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });
    }

    function GetReturningMembers(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportReturningMembersTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">NickName</th>';
        report_table += '<th width="12%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">First Visit</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Last Visit</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px; ">Total Visits during Period</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/returning_member_recap.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                from: starttime,
                to: endtime,
                targetAction:'getVisits'
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMembers=0;
                    var totalVisitors=0;
                    jQuery.each(data, function(i, v){

                        if(v.MembershipNumber != '')
                        {

                            if(typeof v.DateOfBirth.date !='undefined')
                                var dob=v.DateOfBirth.date.split(' ')[0];
                            else
                                var dob=v.DateOfBirth.split(' ')[0];


                            if(typeof v.FirstVisit.date !='undefined')
                                var fvisit=v.FirstVisit.date;
                            else
                                var fvisit=v.FirstVisit;

                            if(typeof v.LastVisit.date !='undefined')
                                var lvisit=v.LastVisit.date;
                            else
                                var lvisit=v.LastVisit;



                            jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor">\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.MembershipNumber+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.GamerTag+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+dob+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+fvisit+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+lvisit+'</td>\
                                <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+v.TotalVisits+'</td>\
                                </tr>');
                            totalMembers++;
                            totalVisitors+=parseInt(v.TotalVisits);
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportReturningMembersTable').append('<tfoot></tfoot>');

                        jQuery('#reportReturningMembersTable tfoot').append('<tr class="whitebgcolor">\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Visits</b></td>\
                            <td class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalVisitors)+'</b></td>\
                            </tr>');
                        jQuery('#reportReturningMembersTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportReturningMembersTable_length').html('');
                                jQuery('#reportReturningMembersTable_length').height(32);
                                jQuery('#reportReturningMembersTable_paginate').html('');
                                jQuery('#reportReturningMembersTable_info').html('');
                            }
                        });

                        jQuery('#reportReturningMembersTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });


                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetReturningMembersNotSameDay(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportReturningMembersTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Phone</th>';
        report_table += '<th width="12%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">First Visit</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Last Visit</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px; ">Total Visits during Period</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/member_returning_not_same_day.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                from: starttime,
                to: endtime,
                targetAction:'getVisits'
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMembers=0;
                    var totalVisitors=0;
                    jQuery.each(data, function(i, v){

                        if(v.MembershipNumber != '')
                        {

                            if(typeof v.DateOfBirth.date !='undefined')
                                var dob=v.DateOfBirth.date.split(' ')[0];
                            else
                                var dob=v.DateOfBirth.split(' ')[0];


                            if(typeof v.FirstVisit.date !='undefined')
                                var fvisit=v.FirstVisit.date;
                            else
                                var fvisit=v.FirstVisit;

                            if(typeof v.LastVisit.date !='undefined')
                                var lvisit=v.LastVisit.date;
                            else
                                var lvisit=v.LastVisit;



                            jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor">\
                                <td class="black3_bo" style="text-align:center;">'+v.MembershipNumber+'</td>\
                                <td class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                <td class="black3_bo" style="padding-left:10px;">'+v.Phone+'</td>\
                                <td class="black3_bo" style="text-align:center;">'+dob+'</td>\
                                <td class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                <td class="black3_bo" style="text-align:center;">'+fvisit+'</td>\
                                <td class="black3_bo" style="text-align:center;">'+lvisit+'</td>\
                                <td class="black3_bo" style="text-align:right; padding-right:10px;">'+v.TotalVisits+'</td>\
                                </tr>');
                            totalMembers++;
                            totalVisitors+=parseInt(v.TotalVisits);
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportReturningMembersTable').append('<tfoot></tfoot>');

                        jQuery('#reportReturningMembersTable tfoot').append('<tr class="whitebgcolor">\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Visits</b></td>\
                            <td class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalVisitors)+'</b></td>\
                            </tr>');
                        jQuery('#reportReturningMembersTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportReturningMembersTable_length').html('');
                                jQuery('#reportReturningMembersTable_length').height(32);
                                jQuery('#reportReturningMembersTable_paginate').html('');
                                jQuery('#reportReturningMembersTable_info').html('');
                            }
                        });

                        jQuery('#reportReturningMembersTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });


                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetReturningMembersWithDetails(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportReturningMembersTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">NickName</th>';
        report_table += '<th width="12%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">First Visit</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Last Visit</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px; ">Total Visits during Period</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/member_returning_not_same_day.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                from: starttime,
                to: endtime,
                targetAction:'getVisits'
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var memberIds=[];

                    jQuery.each(data, function(i, v){
                        memberIds.push(v.MemberId);
                    });

                    var url = apiphp +'API/lounge/get_members_visits_details.php';

                    jQuery.ajax({
                        url: url,
                        data: {
                            token: Global_access,
                            ids: JSON.stringify(memberIds),
                            from: starttime,
                            to: endtime
                        },
                        type: 'POST',
                        crossDomain: true,
                        dataType: 'json',
                        beforeSend: function () {

                            jQuery('#loading-header').show();
                        },
                        complete: function () {

                            jQuery('#loading-header').hide();
                        },
                        success: function (visits_data) {

                            var final_res=[];

                            jQuery.each(g_visits,function(i,v){
                                final_res.push({
                                    'MembershipNumber':v.MembershipNumber,
                                    'FirstName':v.FirstName,
                                    'LastName':v.LastName,
                                    'GamerTag':v.GamerTag,
                                    'DateOfBirth':v.DateOfBirth,
                                    'EmailAddress':v.EmailAddress,
                                    'FirstVisit':v.FirstVisit,
                                    'LastVisit':v.LastVisit,
                                    'TotalVisits':v.TotalVisits,
                                    'RowType':'Parent'
                                });
                                jQuery.each(visits_data.response,function(k,res)
                                {
                                    if(v.MemberId ==res.MemberId)
                                    {
                                        final_res.push({
                                            'MembershipNumber':v.MembershipNumber,
                                            'FirstName':v.FirstName,
                                            'LastName':v.LastName,
                                            'GamerTag':v.GamerTag,
                                            'DateOfBirth':v.DateOfBirth,
                                            'EmailAddress':v.EmailAddress,
                                            'FirstVisit':res.DateCreated,
                                            'LastVisit':res.DateCreated,
                                            'TotalVisits':v.TotalVisits,
                                            'EventName':res.name,
                                            'RowType':'Child'
                                        });
                                    }

                                });

                            });
                            var totalMembers=0;
                            var totalVisitors=0;

                            jQuery.each(final_res, function(i, v){
                                if(v.MembershipNumber != '')
                                {

                                    if(typeof v.DateOfBirth.date !='undefined')
                                        var dob=v.DateOfBirth.date.split(' ')[0];
                                    else
                                        var dob=v.DateOfBirth.split(' ')[0];


                                    if(typeof v.FirstVisit.date !='undefined')
                                        var fvisit=v.FirstVisit.date;
                                    else
                                        var fvisit=v.FirstVisit;

                                    if(typeof v.LastVisit.date !='undefined')
                                        var lvisit=v.LastVisit.date;
                                    else
                                        var lvisit=v.LastVisit;


                                    if(v.RowType=='Parent')
                                    {
                                        jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor" style="background-color: #eee;">\
                                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.MembershipNumber+'</td>\
                                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.GamerTag+'</td>\
                                            <td align="left" class="black3_bo" style="text-align:center;">'+dob+'</td>\
                                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                            <td align="left" class="black3_bo" style="text-align:center;">'+fvisit+'</td>\
                                            <td align="left" class="black3_bo" style="text-align:center;">'+lvisit+'</td>\
                                            <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+v.TotalVisits+'</td>\
                                            </tr>');
                                        totalMembers++;
                                        totalVisitors+=parseInt(v.TotalVisits);
                                    }
                                    else
                                    {
                                        jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor">\
                                            <td colspan="4" align="left" class="black3_bo" style="text-align:center;">'+v.EventName+'</td>\
                                            <td colspan="4" align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+fvisit+'</td>\
                                            </tr>');

                                    }
                                }
                            });

                            setTimeout(function(){

                                jQuery('#reportReturningMembersTable').append('<tfoot></tfoot>');

                                jQuery('#reportReturningMembersTable tfoot').append('<tr class="whitebgcolor">\
                                    <td class="black3_bo" style=""></td>\
                                    <td class="black3_bo" style=""></td>\
                                    <td class="black3_bo" style=""></td>\
                                    <td class="black3_bo" style=""></td>\
                                    <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                                    <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                                    <td class="black3_bo" style="padding-left:10px;"><b>Total Visits</b></td>\
                                    <td class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalVisitors)+'</b></td>\
                                    </tr>');
                                /*   jQuery('#reportReturningMembersTable').dataTable({
                                 "sPaginationType": "full_numbers",
                                 "order": [[ 0, "desc" ]],
                                 'iDisplayLength': 10000000,
                                 "fnDrawCallback": function (oSettings) {

                                 jQuery('#reportReturningMembersTable_length').html('');
                                 jQuery('#reportReturningMembersTable_length').height(32);
                                 jQuery('#reportReturningMembersTable_paginate').html('');
                                 jQuery('#reportReturningMembersTable_info').html('');
                                 }
                                 });        */

                                jQuery('#reportReturningMembersTable_filter label').contents().filter(function() {
                                    return this.nodeType == 3
                                }).each(function(){
                                    this.textContent = this.textContent.replace('Search','Search from Results ');
                                });


                            },200);

                            jQuery('#loading-header').hide();
                            jQuery('#no_data_message').hide();


                        }
                    });



                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetMembersByNumberOfVisits(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportReturningMembersTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">NickName</th>';
        report_table += '<th width="12%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">First Visit</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Last Visit</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px; ">Total Visits during Period</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/member_number_of_visits.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                from: starttime,
                to: endtime,
                visits: jQuery('#r_numVisits').val(),
                visitsTo: jQuery('#r_numVisitsTo').val(),
                searchStr:jQuery('#r_search').val(),
                targetAction:'getVisits'
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMembers=0;
                    var totalVisitors=0;
                    jQuery.each(data, function(i, v){

                        if(v.MembershipNumber != '')
                        {

                            if(typeof v.DateOfBirth.date !='undefined')
                                var dob=v.DateOfBirth.date.split(' ')[0];
                            else
                                var dob=v.DateOfBirth.split(' ')[0];


                            if(typeof v.FirstVisit.date !='undefined')
                                var fvisit=v.FirstVisit.date;
                            else
                                var fvisit=v.FirstVisit;

                            if(typeof v.LastVisit.date !='undefined')
                                var lvisit=v.LastVisit.date;
                            else
                                var lvisit=v.LastVisit;



                            jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor">\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.MembershipNumber+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.GamerTag+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+dob+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+fvisit+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+lvisit+'</td>\
                                <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+v.TotalVisits+'</td>\
                                </tr>');
                            totalMembers++;
                            totalVisitors+=parseInt(v.TotalVisits);
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportReturningMembersTable').append('<tfoot></tfoot>');

                        jQuery('#reportReturningMembersTable tfoot').append('<tr class="whitebgcolor">\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Visits</b></td>\
                            <td class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalVisitors)+'</b></td>\
                            </tr>');
                        jQuery('#reportReturningMembersTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportReturningMembersTable_length').html('');
                                jQuery('#reportReturningMembersTable_length').height(32);
                                jQuery('#reportReturningMembersTable_paginate').html('');
                                jQuery('#reportReturningMembersTable_info').html('');
                            }
                        });

                        jQuery('#reportReturningMembersTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetMembersRegisteredButNotVisited(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportReturningMembersTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="15%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="20%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="20%" class="black3_bo" style="padding-left:10px;">NickName</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="10%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="10%" class="black3_bo" style="text-align:center;">Created Date</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_reg_members_with_no_visit.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                from: starttime,
                to: endtime,
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMembers=0;
                    jQuery.each(data, function(i, v){

                        if(v.MembershipNumber != '')
                        {

                            if(typeof v.DateOfBirth.date !='undefined')
                                var dob=v.DateOfBirth.date.split(' ')[0];
                            else
                                var dob=v.DateOfBirth.split(' ')[0];

                            if(typeof v.DateOfBirth.date !='undefined'){
                                var created=v.DateCreated.date.split(' ')[0];
                            }else{
                                var created=v.DateCreated.split(' ')[0];
                            }


                            jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor">\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.MembershipNumber+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.GamerTag+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+dob+'</td>\
								<td align="left" class="black3_bo" style="text-align:center;">'+created+'</td>\
                                </tr>');
                            totalMembers++;
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportReturningMembersTable').append('<tfoot></tfoot>');

                        jQuery('#reportReturningMembersTable tfoot').append('<tr class="whitebgcolor">\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
							<td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                            </tr>');
                        jQuery('#reportReturningMembersTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportReturningMembersTable_length').html('');
                                jQuery('#reportReturningMembersTable_length').height(32);
                                jQuery('#reportReturningMembersTable_paginate').html('');
                                jQuery('#reportReturningMembersTable_info').html('');
                            }
                        });

                        jQuery('#reportReturningMembersTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });

    }

    function GetMembersByNumberOfVisitsOnMultipleDates(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportReturningMembersTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">NickName</th>';
        report_table += '<th width="12%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">First Visit</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Last Visit</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px; ">Total Visits during Period</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/member_number_of_visits_multiple_dates.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                from: starttime,
                to: endtime
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMembers=0;
                    var totalVisitors=0;
                    jQuery.each(data, function(i, v){

                        if(v.MembershipNumber != '')
                        {

                            if(typeof v.DateOfBirth.date !='undefined')
                                var dob=v.DateOfBirth.date.split(' ')[0];
                            else
                                var dob=v.DateOfBirth.split(' ')[0];


                            if(typeof v.FirstVisit.date !='undefined')
                                var fvisit=v.FirstVisit.date;
                            else
                                var fvisit=v.FirstVisit;

                            if(typeof v.LastVisit.date !='undefined')
                                var lvisit=v.LastVisit.date;
                            else
                                var lvisit=v.LastVisit;



                            jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor">\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.MembershipNumber+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.GamerTag+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+dob+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+fvisit+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+lvisit+'</td>\
                                <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+v.TotalVisits+'</td>\
                                </tr>');
                            totalMembers++;
                            totalVisitors+=parseInt(v.TotalVisits);
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportReturningMembersTable').append('<tfoot></tfoot>');

                        jQuery('#reportReturningMembersTable tfoot').append('<tr class="whitebgcolor">\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Visits</b></td>\
                            <td class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalVisitors)+'</b></td>\
                            </tr>');
                        jQuery('#reportReturningMembersTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportReturningMembersTable_length').html('');
                                jQuery('#reportReturningMembersTable_length').height(32);
                                jQuery('#reportReturningMembersTable_paginate').html('');
                                jQuery('#reportReturningMembersTable_info').html('');
                            }
                        });

                        jQuery('#reportReturningMembersTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetNonReturningMembers(siteid, starttime, endtime){


        jQuery('#report_data').html('');

        var report_table = '<table id="reportReturningMembersTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">NickName</th>';
        report_table += '<th width="12%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">First Visit</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Last Visit</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px; ">Total Visits during Period</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/non_returning_members.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                date: starttime,
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMembers=0;
                    var totalVisitors=0;
                    jQuery.each(data, function(i, v){

                        if(v.MembershipNumber != '')
                        {

                            if(typeof v.DateOfBirth.date !='undefined')
                                var dob=v.DateOfBirth.date.split(' ')[0];
                            else
                                var dob=v.DateOfBirth.split(' ')[0];


                            if(typeof v.FirstVisit.date !='undefined')
                                var fvisit=v.FirstVisit.date;
                            else
                                var fvisit=v.FirstVisit;

                            if(typeof v.LastVisit.date !='undefined')
                                var lvisit=v.LastVisit.date;
                            else
                                var lvisit=v.LastVisit;



                            jQuery('#reportReturningMembersTable tbody').append('<tr class="whitebgcolor">\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.MembershipNumber+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.GamerTag+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+dob+'</td>\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+fvisit+'</td>\
                                <td align="left" class="black3_bo" style="text-align:center;">'+lvisit+'</td>\
                                <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+v.TotalVisits+'</td>\
                                </tr>');
                            totalMembers++;
                            totalVisitors+=parseInt(v.TotalVisits);
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportReturningMembersTable').append('<tfoot></tfoot>');

                        jQuery('#reportReturningMembersTable tfoot').append('<tr class="whitebgcolor">\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style=""></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Visits</b></td>\
                            <td class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalVisitors)+'</b></td>\
                            </tr>');
                        jQuery('#reportReturningMembersTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportReturningMembersTable_length').html('');
                                jQuery('#reportReturningMembersTable_length').height(32);
                                jQuery('#reportReturningMembersTable_paginate').html('');
                                jQuery('#reportReturningMembersTable_info').html('');
                            }
                        });

                        jQuery('#reportReturningMembersTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });


    }

    function GetMembersList(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersListTable" style="table-layout:fixed;" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px; min-width: 150px; width:200px; max-width: 200px !important; ">Name</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">Membership Number</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px; width :300px; max-width :400px;">Email Address</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">Gender</th>';
        report_table += '<th align="left" class="black3_bo center" style="padding-left:10px;">DOB</th>';
        report_table += '<th align="left" class="black3_bo center" style="padding-left:10px;">Phone</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">City</th>';
        report_table += '<th align="left" class="black3_bo center" style="padding-left:10px;">Zip Code</th>';
        report_table += '<th align="left" class="black3_bo center" style="padding-left:10px;">Created Date</th>';
        report_table += '<th class="black3_bo" style="padding-left:10px; text-align:justify;">Status</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_all_members_report.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                status:jQuery('#r_status option:selected').val(),
                createdDate:jQuery('#starttime').val(),
                createdDateTo:jQuery('#endtime').val(),
                searchStr:jQuery('#r_search').val(),
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;

                if(data.length != 0)
                {

                    var totalMem=0;

                    jQuery.each(data, function(i, v){

                        if(v.Status=='0')
                            v.Status='Inactive';
                        else
                            v.Status='Active';

                        if(v.DateCreated != 'null' && v.DateCreated != null )
                            v.DateCreated = v.DateCreated.split(' ')[0];
                        else
                            v.DateCreated='';

                        if(v.DOB != 'null' && v.DOB != null )
                            v.DOB = v.DOB.split(' ')[0];
                        else
                            v.DOB='';

                        if(v.EmailAddress == 'null' || v.EmailAddress == null )
                            v.EmailAddress='';

                        if(v.Gender == 'null' || v.Gender ==  null )
                            v.Gender='';

                        if(v.Phone ==  'null' || v.Phone ==  null )
                            v.Phone='';

                        if(v.City == 'null' || v.City == null )
                            v.City='';

                        if(v.Zipcode == 'null' || v.Zipcode ==  null )
                            v.Zipcode='';



                        jQuery('#reportMembersListTable tbody').append('<tr class="whitebgcolor">\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :200px;">'+v.FirstName+' '+v.LastName+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :150px;">'+v.MembershipNumber+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :400px;">'+v.EmailAddress+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :100px;">'+v.Gender+'</td>\
                            <td align="left" class="black3_bo center" style="padding-left:10px; width :200px;">'+v.DOB+'</td>\
                            <td align="left" class="black3_bo center" style="padding-left:10px; width :150px;">'+v.Phone+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :100px;">'+v.City+'</td>\
                            <td align="left" class="black3_bo center" style="padding-left:10px; width :150px;">'+v.Zipcode+'</td>\
                            <td align="left" class="black3_bo center" style="padding-left:10px; width :200px;">'+v.DateCreated+'</td>\
                            <td class="black3_bo" style="padding-left:10px; width :150px; text-align:justify;">'+v.Status+'</td>\
                            </tr>');

                        totalMem++;
                    });

                    setTimeout(function(){

                        jQuery('#reportMembersListTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersListTable tfoot').append('<tr class="whitebgcolor">\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td align="left" class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');


                        jQuery('#reportMembersListTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "asc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersListTable_length').html('');
                                jQuery('#reportMembersListTable_length').height(32);
                                jQuery('#reportMembersListTable_paginate').html('');
                                jQuery('#reportMembersListTable_info').html('').hide();
                            }
                        });


                        jQuery('#reportMembersListTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();


                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetMembersListByBirthday(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersListTable" style="table-layout:fixed;" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px; min-width: 150px; width:200px; max-width: 200px !important; ">Name</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">Membership Number</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px; width :300px; max-width :400px;">Email Address</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">Gender</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">DOB</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">DOB(mm-dd)</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">Phone</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">City</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">Zip Code</th>';
        report_table += '<th align="left" class="black3_bo" style="padding-left:10px;">Created Date</th>';
        report_table += '<th class="black3_bo" style="padding-left:0px; text-align:center;">Status</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_all_members_bday_report.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                from:starttime,
                to:endtime,
                searchStr:jQuery('#r_search').val(),
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;

                if(data.length != 0)
                {

                    var totalMem=0;

                    jQuery.each(data, function(i, v){

                        if(v.Status=='0')
                            v.Status='Inactive';
                        else
                            v.Status='Active';

                        if(v.DateCreated != 'null' && v.DateCreated != null )
                            v.DateCreated = v.DateCreated.split(' ')[0];
                        else
                            v.DateCreated='';

                        if(v.DOB != 'null' && v.DOB != null )
                            v.DOB = v.DOB.split(' ')[0];
                        else
                            v.DOB='';

                        if(v.EmailAddress == 'null' || v.EmailAddress == null )
                            v.EmailAddress='';

                        if(v.Gender == 'null' || v.Gender ==  null )
                            v.Gender='';

                        if(v.Phone ==  'null' || v.Phone ==  null )
                            v.Phone='';

                        if(v.City == 'null' || v.City == null )
                            v.City='';

                        if(v.Zipcode == 'null' || v.Zipcode ==  null )
                            v.Zipcode='';

                        var dy= v.DOB.split('-')
                        var dob=dy[1]+'-'+dy[2];

                        jQuery('#reportMembersListTable tbody').append('<tr class="whitebgcolor">\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :200px;">'+v.FirstName+' '+v.LastName+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :150px;">'+v.MembershipNumber+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :400px;">'+v.EmailAddress+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :100px;">'+v.Gender+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :200px;">'+v.DOB+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :200px;">'+dob+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :150px;">'+v.Phone+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :100px;">'+v.City+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :150px;">'+v.Zipcode+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; width :200px;">'+v.DateCreated+'</td>\
                            <td class="black3_bo" style="padding-left:0px; width :150px; text-align:center;">'+v.Status+'</td>\
                            </tr>');

                        totalMem++;
                    });

                    setTimeout(function(){

                        jQuery('#reportMembersListTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersListTable tfoot').append('<tr class="whitebgcolor">\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td align="left" class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');


                        jQuery('#reportMembersListTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "asc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersListTable_length').html('');
                                jQuery('#reportMembersListTable_length').height(32);
                                jQuery('#reportMembersListTable_paginate').html('');
                                jQuery('#reportMembersListTable_info').html('').hide();
                            }
                        });

                        jQuery('#reportMembersListTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetUpgradedMembersList(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportUpgradedMembersListTable" style="table-layout:fixed;" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th  class="black3_bo" style="padding-left:10px;  max-width: 100px !important; ">SubType</th>';
        report_table += '<th  class="black3_bo" style="padding-left:10px;  max-width: 200px !important; ">Experience Name</th>';
        report_table += '<th  class="black3_bo center" style="padding-right:10px;">Member Count</th>';
        report_table += '<th  class="black3_bo center" style="padding-right:10px;">Hours</th>';
        report_table += '<th  class="black3_bo center" style="padding-right:10px;">Count Percentage</th>';
        report_table += '</tr></thead>';
        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_upgraded_members.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                from:starttime,
                to:endtime
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;

                if(data.length != 0)
                {

                    var totalMem=0;
                    var totalPer=0;
                    var totalhours=0;
                    var fullhours=0;
                    totalSeconds=0;
                    jQuery.each(data, function(i, v){
                        fullhours+=parseFloat(v.minutesDiff * 60);
                    });

                    jQuery.each(data, function(i, v){

                        seconds = parseFloat(v.minutesDiff)*60;
                        totalSeconds+= seconds;
                        var percentage =  parseFloat(parseFloat(parseFloat(v.minutesDiff * 60)/fullhours )*100).toFixed(2);


                        jQuery('#reportUpgradedMembersListTable tbody').append('<tr class="whitebgcolor">\
                            <td align="left" class="black3_bo" style="padding-left:10px; ">'+v.SubType+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px; ">'+v.ExperienceName+'</td>\
                            <td align="left" class="black3_bo center" style="padding-right:10px;">'+v.MembersCount+'</td>\
                            <td align="left" class="black3_bo center" style="padding-right:10px;">'+secondsToHMConversion((seconds))+'</td>\
                            <td align="left" class="black3_bo center" style="padding-right:10px;">'+percentage+'%</td>\
                            </tr>');

                        totalMem+=parseInt(v.MembersCount);
                        totalhours+=parseFloat(v.hours);
                        totalPer+=parseFloat(percentage);
                    });

                    setTimeout(function(){

                        jQuery('#reportUpgradedMembersListTable').append('<tfoot></tfoot>');
                        totalPer=parseFloat(totalPer).toFixed(2);
                        totalPer=totalPer+'%';


                        var totalConverted=secondsToHMConversion((parseFloat(totalhours)*3600));
                        console.log(totalSeconds,totalhours,totalConverted);
                        jQuery('#reportUpgradedMembersListTable tfoot').append('<tr class="whitebgcolor">\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"></td>\
                            <td  align="left" class="black3_bo" style="padding-left:10px;"><b>Total</b></td>\
                            <td  align="left" class="black3_bo center" style="padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            <td  align="left" class="black3_bo center" style="padding-right:10px;"><b>'+totalConverted+'</b></td>\
                            <td align="left" class="black3_bo center" style="padding-right:10px;"><b>100%</b></td>\
                            </tr>');


                        jQuery('#reportUpgradedMembersListTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "asc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportUpgradedMembersListTable_length').html('');
                                jQuery('#reportUpgradedMembersListTable_length').height(32);
                                jQuery('#reportUpgradedMembersListTable_paginate').html('');
                                jQuery('#reportUpgradedMembersListTable_info').html('').hide();
                            }
                        });

                        jQuery('#reportUpgradedMembersListTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });

    }

    function GetMembersByZipcode(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersZipcodeTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%" align="left" class="black3_bo" style="padding-left:10px;">Zip code</th>';
        report_table += '<th width="12%" align="left" class="black3_bo" style="padding-left:10px;">Members</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_users_zipcode.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                from:starttime,
                to:endtime
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMem=0;
                    var totalUnspecified=0;

                    jQuery.each(data, function(i, v){

                        if(v.ZIPCode =="" || v.ZIPCode == '<null>' || v.ZIPCode == 'null'  || v.ZIPCode ==null)
                            totalUnspecified+=parseInt(v.Total);
                        else

                            jQuery('#reportMembersZipcodeTable tbody').append('<tr class="whitebgcolor">\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.ZIPCode+'</td>\
                                <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(v.Total)+'</td>\
                                </tr>');

                        totalMem+=parseInt(v.Total);


                    });

                    jQuery('#reportMembersZipcodeTable tbody').append('<tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Unspecified</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalUnspecified)+'</td>\
                        </tr>');

                    setTimeout(function(){

                        jQuery('#reportMembersZipcodeTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersZipcodeTable tfoot').append('<tr class="whitebgcolor">\
                            <td width="50%" align="left" class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td width="50%" align="left" class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');
                        jQuery('#reportMembersZipcodeTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersZipcodeTable_length').html('');
                                jQuery('#reportMembersZipcodeTable_length').height(32);
                                jQuery('#reportMembersZipcodeTable_paginate').html('');
                                jQuery('#reportMembersZipcodeTable_info').html('');
                            }
                        });

                        jQuery('#reportMembersZipcodeTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetMembersByGender(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersZipcodeTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%" align="left" class="black3_bo" style="padding-left:10px;">Gender</th>';
        report_table += '<th width="12%" align="left" class="black3_bo" style="padding-left:10px;">Members</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_users_info.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                from:starttime,
                to:endtime
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMem=0;

                    jQuery.each(data, function(i, v){

                        // if(v.Gender != "" && v.Gender != '<null>')
                        {
                            /*if(v.Gender == 'NotSpecified')
                             v.Gender='Not Specified'; */
                            jQuery('#reportMembersZipcodeTable tbody').append('<tr class="whitebgcolor">\
                                <td align="left" class="black3_bo" style="padding-left:10px;">'+v.Gender+'</td>\
                                <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(v.Total)+'</td>\
                                </tr>');

                            totalMem+=parseInt(v.Total);
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportMembersZipcodeTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersZipcodeTable tfoot').append('<tr class="whitebgcolor">\
                            <td width="50%" align="left" class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td width="50%" align="left" class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');
                        jQuery('#reportMembersZipcodeTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersZipcodeTable_length').html('');
                                jQuery('#reportMembersZipcodeTable_length').height(32);
                                jQuery('#reportMembersZipcodeTable_paginate').html('');
                                jQuery('#reportMembersZipcodeTable_info').html('');
                            }
                        });

                        jQuery('#reportMembersZipcodeTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetMembersByType(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersZipcodeTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%" align="left" class="black3_bo" style="padding-left:10px;">Registration Type</th>';
        report_table += '<th width="12%" align="right" class="black3_bo center" style="padding-left:10px;">Members</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_users_by_registration_type.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                from:starttime,
                to:endtime
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;

                if(data.length != 0)
                {

                    var totalMem=0;
                    var totalWebsite=0;
                    var totalSelfReg=0;
                    var totalMob=0;
                    var totalUnspecified=0;

                    var totalEvent=0;

                    jQuery.each(data, function(i, v)
                    {

                        if(v.RegType == 'In-Person' || v.RegType == 'Self+Registration' || v.RegType == 'Self Registration' )
                            totalSelfReg+=parseInt(v.Total);
                        else if(v.RegType == 'Website' || v.RegType == 'Webste')
                            totalWebsite+=parseInt(v.Total);
                        else if(v.RegType == 'Mobile')
                            totalMob+=parseInt(v.Total);
                        else if(v.RegType == 'Event')
                            totalEvent+=parseInt(v.Total);
                        else
                            totalUnspecified+=parseInt(v.Total);
                        totalMem+=parseInt(v.Total);
                    });


                    jQuery('#reportMembersZipcodeTable tbody').append('<tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Self Registration</td>\
                        <td align="center" class="black3_bo center" style="padding-right:10px;">'+numberWithCommas(totalSelfReg)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Website</td>\
                        <td align="center" class="black3_bo center" style="padding-right:10px;">'+numberWithCommas(totalWebsite)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Application</td>\
                        <td align="center" class="black3_bo center" style="padding-right:10px;">'+numberWithCommas(totalMob)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Event</td>\
                        <td align="center" class="black3_bo center" style="padding-right:10px;">'+numberWithCommas(totalEvent)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Unspecified</td>\
                        <td align="center" class="black3_bo center" style="padding-right:10px;">'+numberWithCommas(totalUnspecified)+'</td>\
                        </tr>');


                    setTimeout(function(){

                        jQuery('#reportMembersZipcodeTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersZipcodeTable tfoot').append('<tr class="whitebgcolor">\
                            <td width="50%" align="left" class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td width="50%" align="left" class="black3_bo center" style="padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');
                        jQuery('#reportMembersZipcodeTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersZipcodeTable_length').html('');
                                jQuery('#reportMembersZipcodeTable_length').height(32);
                                jQuery('#reportMembersZipcodeTable_paginate').html('');
                                jQuery('#reportMembersZipcodeTable_info').html('');
                            }
                        });

                        jQuery('#reportMembersZipcodeTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function typedGamePlaysByGender(siteid, starttime, endtime){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportTypedGamePlaysGenderTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="3%"  class="black3_bo" style="text-align:center;">Id</th>';
        report_table += '<th width="10%"  class="black3_bo" style="padding-left:10px;">Game</th>';
        report_table += '<th width="8%" class="black3_bo" style="padding-right:10px; text-align:right;">Played By Men</th>';
        report_table += '<th width="6%" class="black3_bo" style="padding-right:10px; text-align:right;">Played Time</th>';
        report_table += '<th width="8%" class="black3_bo" style="padding-right:10px; text-align:right;">Played By Women</th>';
        report_table += '<th width="6%"  class="black3_bo" style="padding-right:10px; text-align:right;">Played Time</th>';
        report_table += '<th width="8%"  class="black3_bo" style="padding-right:10px; text-align:right;">Played By Junior</th>';
        report_table += '<th width="6%"  class="black3_bo" style="padding-right:10px; text-align:right;">Played Time</th>';
        report_table += '<th width="10%" class="black3_bo" style="padding-right:10px; text-align:right;">Total Played Members</th>';
        report_table += '<th width="14%"  class="black3_bo" style="padding-right:10px; text-align:right;">Total Played Members Time</th>';
        report_table += '<th width="10%"  class="black3_bo" style="padding-right:10px; text-align:right;">Total Played All</th>';
        report_table += '<th width="12%"  class="black3_bo" style="padding-right:10px; text-align:right;">Total Played All Time</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/typed_games_played_gender_report.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                from: starttime,
                to: endtime,
                search:jQuery('#r_search').val()
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                if(data.length != 0)
                {
                    var sumMalePlayed=0;
                    var sumMalePlayedTime=0;

                    var sumFemalePlayed=0;
                    var sumFemalePlayedTime=0;

                    var sumJuniorPlayed=0;
                    var sumJuniorPlayedTime=0;

                    var sumMemberPlayed=0;
                    var sumMemberPlayedTime=0;

                    var sumAllPlayed=0;
                    var sumAllPlayedTime=0;

                    jQuery.each(data, function(i, v){

                        console.log(v);

                        var totalPlayed=0;
                        var totalPlayedAll=0;
                        var totalPlayedTime=0.00;
                        var totalPlayedAllTime=0.00;


                        if(typeof v.TotalMalePlayedGame == 'undefined')
                            v.TotalMalePlayedGame=0;
                        if(typeof v.TotalMalePlayedGameTime == 'undefined')
                            v.TotalMalePlayedGameTime=0.00;


                        if(typeof v.TotalFemalePlayedGame == 'undefined')
                            v.TotalFemalePlayedGame=0;
                        if(typeof v.TotalFemalePlayedGameTime == 'undefined')
                            v.TotalFemalePlayedGameTime=0.00;


                        if(typeof v.TotalJuniorPlayedGame == 'undefined')
                            v.TotalJuniorPlayedGame=0;
                        if(typeof v.TotalJuniorPlayedGameTime == 'undefined')
                            v.TotalJuniorPlayedGameTime=0.00;

                        if(typeof v.TotalMemberPlayedGame == 'undefined')
                            v.TotalMemberPlayedGame=0;
                        if(typeof v.TotalMemberPlayedGameTime == 'undefined')
                            v.TotalMemberPlayedGameTime=0.00;





                        totalPlayedAll = parseInt(v.TotalMemberPlayedGame)+ parseInt(v.TotalJuniorPlayedGame);
                        totalPlayedAllPlayTime = parseFloat(v.TotalMemberPlayedGameTime)+parseFloat(v.TotalJuniorPlayedGameTime);

                        jQuery('#reportTypedGamePlaysGenderTable tbody').append('<tr class="whitebgcolor">\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.Id+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.Name+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+numberWithCommas(v.TotalMalePlayedGame)+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+secondsToDHMConversion((v.TotalMalePlayedGameTime.toFixed(2)))+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+numberWithCommas(v.TotalFemalePlayedGame)+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+secondsToDHMConversion((v.TotalFemalePlayedGameTime.toFixed(2)))+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+numberWithCommas(v.TotalJuniorPlayedGame)+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+secondsToDHMConversion((v.TotalJuniorPlayedGameTime.toFixed(2)))+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+numberWithCommas(v.TotalMemberPlayedGame)+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+secondsToDHMConversion((v.TotalMemberPlayedGameTime.toFixed(2)))+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+numberWithCommas(totalPlayedAll)+'</td>\
                            <td align="left" class="black3_bo" style="padding-right:10px; text-align:right;">'+secondsToDHMConversion((totalPlayedAllPlayTime.toFixed(2)))+'</td>\
                            </tr>');

                        sumMalePlayed+=parseInt(v.TotalMalePlayedGame);
                        sumMalePlayedTime+=parseFloat(v.TotalMalePlayedGameTime);


                        sumFemalePlayed+=parseInt(v.TotalFemalePlayedGame);
                        sumFemalePlayedTime+=parseFloat(v.TotalFemalePlayedGameTime);


                        sumJuniorPlayed+=parseInt(v.TotalJuniorPlayedGame);
                        sumJuniorPlayedTime+=parseFloat(v.TotalJuniorPlayedGameTime);

                        sumMemberPlayed+=parseInt(v.TotalMemberPlayedGame);
                        sumMemberPlayedTime+=parseFloat(v.TotalMemberPlayedGameTime);

                        sumAllPlayed+=parseInt(totalPlayedTime);
                        sumAllPlayedTime+=parseFloat(totalPlayedAllPlayTime);



                    });

                    setTimeout(function(){


                        jQuery('#reportTypedGamePlaysGenderTable').append('<tfoot></tfoot>');

                        jQuery('#reportTypedGamePlaysGenderTable>tfoot').append('<tr class="whitebgcolor">\
                            <td  class="black3_bo" style="padding-left:10px;"></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total</b></td>\
                            <td  class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(sumMalePlayed)+'</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+secondsToDHMConversion((sumMalePlayedTime.toFixed(2)))+'</b></td>\
                            <td  class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(sumFemalePlayed)+'</b></td>\
                            <td  class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+secondsToDHMConversion((sumFemalePlayedTime.toFixed(2)))+'</b></td>\
                            <td  class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(sumJuniorPlayed)+'</b></td>\
                            <td  class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+secondsToDHMConversion((sumJuniorPlayedTime.toFixed(2)))+'</b></td>\
                            <td  class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(sumMemberPlayed)+'</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+secondsToDHMConversion((sumMemberPlayedTime.toFixed(2)))+'</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(sumAllPlayed)+'</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+secondsToDHMConversion((sumAllPlayedTime.toFixed(2)))+'</b></td>\
                            </tr>');
                        jQuery('#reportTypedGamePlaysGenderTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportTypedGamePlaysGenderTable_length').html('');
                                jQuery('#reportTypedGamePlaysGenderTable_length').height(32);
                                jQuery('#reportTypedGamePlaysGenderTable_paginate').html('');
                                jQuery('#reportTypedGamePlaysGenderTable_info').html('');
                            }
                        });

                        jQuery('#reportTypedGamePlaysGenderTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function RPTSLTimePurchase(siteid, starttime, endtime){
        console.log('RPTSLTimePurchase ...');

        var url = lounge_api+"RPTSLTimePurchase";
        jQuery.ajax({
            url: url,
            headers: { apicode: apicode },
            data: {
                siteid: siteid,
                starttime: starttime,
                endtime: endtime
            },
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {
                console.log('RPTSLTimePurchase success: function (result) ...');
                console.log(result);
                var data = result.timePurchase;

                if(data.length == 0) {
                    jQuery('#report_data').html('');
                    // jQuery('#default_message').show();
                    jQuery('#no_data_message').show();
                    return false;
                }

                var report_table = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive" id="dyntable">';
                report_table += '<thead>';
                report_table += '<tr class="whitebgcolor">';
                report_table += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px;">Shooting Lounge</th>';
                report_table += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px;">Total Hours</th>';
                report_table += '</tr>';
                report_table += '</thead>';
                report_table += '<tbody>';
                jQuery.each(data, function(i, v){
                    report_table += '<tr class="whitebgcolor">';
                    report_table += '<td width="50%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.bayname +'</td>';
                    report_table += '<td width="50%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.totalhours +'</td>';
                    report_table += '</tr>';
                });
                report_table += '</tbody>';
                report_table += '</table>';

                jQuery('#report_data').html(report_table);

                jQuery('#dyntable').dataTable({
                    "sPaginationType": "full_numbers",
                    'iDisplayLength': 10000000,
                    "destroy": true,
                    "order": [[ 0, "asc" ]]
                });

                /*
                 if(result.status){
                 console.log('result.token ...');
                 console.log(result.token);
                 }
                 */

                jQuery('#loading-header').hide();
                jQuery('#no_data_message').hide();
            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });
    }

    function RPTSLNewMembershipsPaid(siteid, startdate, enddate, source){
        console.log('RPTSLNewMembershipsPaid ...');


        var url = lounge_api+"RPTSLNewMembershipsPaid";
        jQuery.ajax({
            url: url,
            headers: { apicode: apicode },
            data: {
                siteid: siteid,
                startdate: startdate,
                enddate: enddate,
                source: source
            },
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {
                console.log('RPTSLNewMembershipsPaid success: function (result) ...');
                console.log(result);
                var data = result.members;

                if(data.length == 0) {
                    jQuery('#report_data').html('');
                    // jQuery('#default_message').show();
                    jQuery('#no_data_message').show();
                    return false;
                }

                var report_table = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive" id="dyntable">';
                report_table += '<thead>';
                report_table += '<tr class="whitebgcolor">';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">First Name</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Last Name</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">DOB</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Created Date</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Membership Paid Date</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Membership Renewal Date</th>';
                report_table += '</tr>';
                report_table += '</thead>';
                report_table += '<tbody>';
                jQuery.each(data, function(i, v){
                    report_table += '<tr class="whitebgcolor">';
                    report_table += '<td width="16%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.firstname +'</td>';
                    report_table += '<td width="16%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.lastname +'</td>';
                    report_table += '<td width="16%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.dob +'</td>';
                    report_table += '<td width="16%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.createddate +'</td>';
                    report_table += '<td width="16%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.membershippaiddate +'</td>';
                    report_table += '<td width="16%" align="left" class="black3_bo" style="padding-left:10px;">'+ v.membershiprenewaldate +'</td>';
                    report_table += '</tr>';
                });
                report_table += '</tbody>';
                report_table += '</table>';

                jQuery('#report_data').html(report_table);

                jQuery('#dyntable').dataTable({
                    "sPaginationType": "full_numbers",
                    'iDisplayLength': 10000000,
                    "destroy": true,
                    "order": [[ 0, "asc" ]]
                });

                /*
                 if(result.status){
                 console.log('result.token ...');
                 console.log(result.token);
                 }
                 */

                jQuery('#loading-header').hide();
                jQuery('#no_data_message').hide();
            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });
    }

    function RPTSLReturningMembers(siteid, startdate, enddate){
        console.log('RPTSLReturningMembers ...');

        var url = lounge_api+"RPTSLReturningMembers";
        jQuery.ajax({
            url: url,
            headers: { apicode: apicode },
            data: {
                siteid: siteid,
                startdate: startdate,
                enddate: enddate
            },
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {
                console.log('RPTSLReturningMembers success: function (result) ...');
                console.log(result);
                var data = result.members;

                if(data.length == 0) {
                    jQuery('#report_data').html('');
                    // jQuery('#default_message').show();
                    jQuery('#no_data_message').show();
                    return false;
                }

                var report_table = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive" id="dyntable">';
                report_table += '<thead>';
                report_table += '<tr class="whitebgcolor">';
                report_table += '<th width="20%" align="left" class="black3_bo" style="padding-left:10px;">First Name</th>';
                report_table += '<th width="20%" align="left" class="black3_bo" style="padding-left:10px;">Last Name</th>';
                report_table += '<th width="20%" align="left" class="black3_bo" style="padding-left:10px;">DOB</th>';
                report_table += '<th width="20%" align="left" class="black3_bo" style="padding-left:10px;">Date Returned</th>';
                report_table += '<th width="20%" align="left" class="black3_bo" style="padding-left:10px;">Times Checked-In</th>';
                report_table += '</tr>';
                report_table += '</thead>';
                report_table += '<tbody>';
                jQuery.each(data, function(i, v){
                    report_table += '<tr class="whitebgcolor">';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.firstname +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.lastname +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.dob +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.lastcreateddate +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.timecheckincount +'</td>';
                    report_table += '</tr>';
                });
                report_table += '</tbody>';
                report_table += '</table>';

                jQuery('#report_data').html(report_table);

                jQuery('#dyntable').dataTable({
                    "sPaginationType": "full_numbers",
                    'iDisplayLength': 10000000,
                    "destroy": true,
                    "order": [[ 0, "asc" ]]
                });

                /*
                 if(result.status){
                 console.log('result.token ...');
                 console.log(result.token);
                 }
                 */

                jQuery('#loading-header').hide();
                jQuery('#no_data_message').hide();
            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });
    }

    function RPTSLUpgradedTimePurchased(siteid, startdate, enddate){

        var url = lounge_api+"RPTSLUpgradedTimePurchased";
        jQuery.ajax({
            url: url,
            headers: { apicode: apicode },
            data: {
                siteid: siteid,
                startdate: startdate,
                enddate: enddate
            },
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {
                console.log(result);
                var data = result.bays;

                if(data.length == 0) {
                    jQuery('#report_data').html('');
                    // jQuery('#default_message').show();
                    jQuery('#no_data_message').show();
                    return false;
                }

                var report_table = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive" id="dyntable">';
                report_table += '<thead>';
                report_table += '<tr class="whitebgcolor">';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Header</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Id</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Name</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Site Location</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Status</th>';
                report_table += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Upgraded Hours</th>';
                report_table += '</tr>';
                report_table += '</thead>';
                report_table += '<tbody>';
                jQuery.each(data, function(i, v){
                    report_table += '<tr class="whitebgcolor">';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.header +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.id +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.name +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.sitelocation +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.status +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.upgradedhours +'</td>';
                    report_table += '</tr>';
                });
                report_table += '</tbody>';
                report_table += '</table>';

                jQuery('#report_data').html(report_table);

                jQuery('#dyntable').dataTable({
                    "sPaginationType": "full_numbers",
                    'iDisplayLength': 10000000,
                    "destroy": true,
                    "order": [[ 0, "asc" ]]
                });



                jQuery('#loading-header').hide();
                jQuery('#no_data_message').hide();
            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });
    }

    function RPTSLRegVSPaidMember(siteid, startdate, enddate){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersZipcodeTable" style="table-layout:fixed;" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th  class="black3_bo" style="padding-left:10px;  max-width: 100px !important; ">Source</th>';
        report_table += '<th  class="black3_bo" style="padding-left:10px;  max-width: 200px !important; ">Registered</th>';
        report_table += '<th  class="black3_bo" style="padding-right:10px; text-align:right;">Paid</th>';
        report_table += '<th  class="black3_bo" style="padding-right:10px; text-align:right;">Rate</th>';
        report_table += '</tr></thead>';
        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/get_paid_registered_users.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                from:startdate,
                to:enddate
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;

                if(data.length != 0)
                {

                    var totalMem=0;
                    var totalWebsite=0;
                    var totalSelfReg=0;
                    var totalMob=0;
                    var totalUnspecified=0;

                    var totalEvent=0;

                    jQuery.each(data, function(i, v)
                    {

                        if(v.RegType == 'In-Person' || v.RegType == 'Self+Registration' || v.RegType == 'Self Registration' )
                            totalSelfReg+=parseInt(v.Total);
                        else if(v.RegType == 'Website' || v.RegType == 'Webste')
                            totalWebsite+=parseInt(v.Total);
                        else if(v.RegType == 'Mobile')
                            totalMob+=parseInt(v.Total);
                        else if(v.RegType == 'Event')
                            totalEvent+=parseInt(v.Total);
                        else
                            totalUnspecified+=parseInt(v.Total);
                        totalMem+=parseInt(v.Total);
                    });


                    jQuery('#reportMembersZipcodeTable tbody').append('<tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Self Registration</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalSelfReg)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalSelfReg)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalSelfReg)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Website</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalWebsite)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalWebsite)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalWebsite)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Application</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalMob)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalMob)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalMob)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Event</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalEvent)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalEvent)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalEvent)+'</td>\
                        </tr>\
                        <tr class="whitebgcolor">\
                        <td align="left" class="black3_bo" style="padding-left:10px;">Unspecified</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalUnspecified)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalUnspecified)+'</td>\
                        <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(totalUnspecified)+'</td>\
                        </tr>');


                    setTimeout(function(){

                        jQuery('#reportMembersZipcodeTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersZipcodeTable tfoot').append('<tr class="whitebgcolor">\
                            <td width="50%" align="left" class="black3_bo" style="padding-left:10px;"><b></b></td>\
                            <td width="50%" align="left" class="black3_bo" style="padding-left:10px;"><b></b></td>\
                            <td width="50%" align="left" class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td width="50%" align="left" class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');
                        jQuery('#reportMembersZipcodeTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersZipcodeTable_length').html('');
                                jQuery('#reportMembersZipcodeTable_length').height(32);
                                jQuery('#reportMembersZipcodeTable_paginate').html('');
                                jQuery('#reportMembersZipcodeTable_info').html('');
                            }
                        });

                        jQuery('#reportMembersZipcodeTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');

            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });

    }

    function RPTSLRankedGamePlays(siteid, startdate, enddate){


        var url = lounge_api+"RPTSLRankedGamePlays";
        jQuery.ajax({
            url: url,
            headers: { apicode: apicode },
            data: {
                siteid: siteid,
                startdate: startdate,
                enddate: enddate
            },
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {
                console.log('RPTSLRankedGamePlays success: function (result) ...');
                console.log(result);
                var data = result.games;
                var data_r = result.rank;

                if(data.length == 0) {
                    jQuery('#report_data').html('');
                    // jQuery('#default_message').show();
                    jQuery('#no_data_message').show();
                    return false;
                }

                var report_table = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive" id="dyntable">';
                report_table += '<thead>';
                report_table += '<tr class="whitebgcolor">';
                report_table += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px;">Event Name</th>';
                report_table += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px;">Player Rank</th>';
                report_table += '</tr>';
                report_table += '</thead>';
                report_table += '<tbody>';
                jQuery.each(data, function(i, v){
                    report_table += '<tr class="whitebgcolor">';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.name +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ data_r[i] +'</td>';
                    report_table += '</tr>';
                });
                report_table += '</tbody>';
                report_table += '</table>';

                jQuery('#report_data').html(report_table);

                jQuery('#dyntable').dataTable({
                    "sPaginationType": "full_numbers",
                    'iDisplayLength': 10000000,
                    "destroy": true,
                    "order": [[ 0, "asc" ]]
                });

                /*
                 if(result.status){
                 console.log('result.token ...');
                 console.log(result.token);
                 }
                 */

                jQuery('#loading-header').hide();
                jQuery('#no_data_message').hide();
            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });
    }

    function typedGamePlayedByMember(siteid, startdate, enddate){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersZipcodeTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">ID</th>';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">Name</th>';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">Count</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/typed_game_played_report.php';

        jQuery.ajax({
            url: url,

            data: {
                'token': Global_access,
                'from':startdate,
                'to':enddate,
                'siteid': siteid,
                'search':jQuery('#r_search').val()
            },

            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                if(data.length != 0)
                {

                    var totalMem=0;

                    jQuery.each(data, function(i, v){



                        jQuery('#reportMembersZipcodeTable tbody').append('<tr class="whitebgcolor">\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.Id+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.name+'</td>\
                            <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(v.TotalMembersPlayedGames)+'</td>\
                            </tr>');

                        totalMem+=parseInt(v.TotalMembersPlayedGames);
                    });

                    setTimeout(function(){

                        jQuery('#reportMembersZipcodeTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersZipcodeTable tfoot').append('<tr class="whitebgcolor">\
                            <td width="33%" align="left" class="black3_bo" style="padding-left:10px;"><b></b></td>\
                            <td width="33%" align="left" class="black3_bo" style="padding-left:10px;"><b>Total</b></td>\
                            <td width="33%" align="left" class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');
                        jQuery('#reportMembersZipcodeTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersZipcodeTable_length').html('');
                                jQuery('#reportMembersZipcodeTable_length').height(32);
                                jQuery('#reportMembersZipcodeTable_paginate').html('');
                                jQuery('#reportMembersZipcodeTable_info').html('');
                            }
                        });

                        jQuery('#reportMembersZipcodeTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });

    }

    function typedGamePlayedByMember_Group(siteid, startdate, enddate){

        jQuery('#report_data').html('');

        var report_table = '<table id="reportMembersZipcodeTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">ID</th>';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">Name</th>';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">Group Name</th>';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">Group Count</th>';
        report_table += '<th  align="left" class="black3_bo" style="padding-left:10px;">Count</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/typed_game_played_group_report.php';

        jQuery.ajax({
            url: url,

            data: {
                'token': Global_access,
                'from':startdate,
                'to':enddate,
                'siteid':siteid,
                'search':jQuery('#r_search').val()
            },

            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;


                if(data.length != 0)
                {
                    var totalMem=0;
                    jQuery.each(data,function(index,v){

                        if(typeof v.groupName =='undefined')
                            v.groupName='';

                        if(typeof v.totalGroupCount =='undefined')
                            v.totalGroupCount=0;

                        if(typeof v.TotalMembersPlayedGames =='undefined')
                            v.TotalMembersPlayedGames=0;


                        jQuery('#reportMembersZipcodeTable tbody').append('<tr class="whitebgcolor">\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.Id+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.name+'</td>\
                            <td align="left" class="black3_bo" style="padding-left:10px;">'+v.groupName+'</td>\
                            <td align="left" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(v.totalGroupCount)+'</td>\
                            <td align="center" class="black3_bo" style="text-align:right; padding-right:10px;">'+numberWithCommas(v.TotalMembersPlayedGames)+'</td>\
                            </tr>');

                        totalMem+=parseInt(v.TotalMembersPlayedGames);


                    });



                    setTimeout(function(){

                        jQuery('#reportMembersZipcodeTable').append('<tfoot></tfoot>');

                        jQuery('#reportMembersZipcodeTable tfoot').append('<tr class="whitebgcolor">\
                            <td width="10%" align="left" class="black3_bo" style="padding-left:10px;"><b></b></td>\
                            <td width="30%" align="left" class="black3_bo" style="padding-left:10px;"><b></b></td>\
                            <td width="30%" align="left" class="black3_bo" style="padding-left:10px;"><b></b></td>\
                            <td width="20%" align="left" class="black3_bo" style="padding-left:10px;"><b>Total</b></td>\
                            <td width="10%" align="left" class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalMem)+'</b></td>\
                            </tr>');
                        jQuery('#reportMembersZipcodeTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportMembersZipcodeTable_length').html('');
                                jQuery('#reportMembersZipcodeTable_length').height(32);
                                jQuery('#reportMembersZipcodeTable_paginate').html('');
                                jQuery('#reportMembersZipcodeTable_info').html('');
                            }
                        });

                        jQuery('#reportMembersZipcodeTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });

                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });

    }

    function RPTSLTypedGamePlaysByGender(siteid, starttime, endtime){


        jQuery('#report_data').html('');

        var report_table = '<table id="reportTypedGamePlaysByGenderTable" width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive">';
        report_table += '<thead>';
        report_table += '<tr class="whitebgcolor">';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Member Id</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">Full Name</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px;">NickName</th>';
        report_table += '<th width="12%" class="black3_bo" style="text-align:center;">DOB</th>';
        report_table += '<th width="25%" class="black3_bo" style="padding-left:10px;">Email Address</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">First Visit</th>';
        report_table += '<th width="8%"  class="black3_bo" style="text-align:center;">Last Visit</th>';
        report_table += '<th width="12%" class="black3_bo" style="padding-left:10px; ">Total Visits during Period</th>';
        report_table += '</tr></thead>';

        report_table += '<tbody></tbody>';
        report_table += '</table>';

        jQuery('#report_data').html(report_table);

        var url = apiphp +'API/lounge/non_returning_members.php';

        jQuery.ajax({
            url: url,
            data: {
                token: Global_access,
                siteId: siteid,
                date: starttime,
            },
            type: 'POST',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {

                var data = result.response;
                g_visits= result.response;
                if(data.length != 0)
                {

                    var totalMembers=0;
                    var totalVisitors=0;
                    jQuery.each(data, function(i, v){

                        if(v.MembershipNumber != '')
                        {

                            if(typeof v.DateOfBirth.date !='undefined')
                                var dob=v.DateOfBirth.date.split(' ')[0];
                            else
                                var dob=v.DateOfBirth.split(' ')[0];


                            if(typeof v.FirstVisit.date !='undefined')
                                var fvisit=v.FirstVisit.date;
                            else
                                var fvisit=v.FirstVisit;

                            if(typeof v.LastVisit.date !='undefined')
                                var lvisit=v.LastVisit.date;
                            else
                                var lvisit=v.LastVisit;



                            jQuery('#reportTypedGamePlaysByGenderTable tbody').append('<tr class="whitebgcolor">\
                                <td class="black3_bo" style="text-align:center;">'+v.MembershipNumber+'</td>\
                                <td  class="black3_bo" style="padding-left:10px;">'+v.FirstName+ '  '+v.LastName+'</td>\
                                <td  class="black3_bo" style="padding-left:10px;">'+v.GamerTag+'</td>\
                                <td  class="black3_bo" style="text-align:center;">'+dob+'</td>\
                                <td  class="black3_bo" style="padding-left:10px;">'+v.EmailAddress+'</td>\
                                <td  class="black3_bo" style="text-align:center;">'+fvisit+'</td>\
                                <td  class="black3_bo" style="text-align:center;">'+lvisit+'</td>\
                                <td  class="black3_bo" style="text-align:right; padding-right:10px;">'+v.TotalVisits+'</td>\
                                </tr>');
                            totalMembers++;
                            totalVisitors+=parseInt(v.TotalVisits);
                        }

                    });

                    setTimeout(function(){

                        jQuery('#reportTypedGamePlaysByGenderTable').append('<tfoot></tfoot>');

                        jQuery('#reportTypedGamePlaysByGenderTable tfoot').append('<tr class="whitebgcolor">\
                            <td class="black3_bo" style="padding-left:10px;"></td>\
                            <td class="black3_bo" style="padding-left:10px;"></td>\
                            <td class="black3_bo" style="padding-left:10px;"></td>\
                            <td class="black3_bo" style="padding-left:10px;"></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Members</b></td>\
                            <td class="black3_bo" style="padding-right:10px; text-align:right;"><b>'+numberWithCommas(totalMembers)+'</b></td>\
                            <td class="black3_bo" style="padding-left:10px;"><b>Total Visits</b></td>\
                            <td class="black3_bo" style="text-align:right; padding-right:10px;"><b>'+numberWithCommas(totalVisitors)+'</b></td>\
                            </tr>');

                        jQuery('#reportTypedGamePlaysByGenderTable').dataTable({
                            "sPaginationType": "full_numbers",
                            "order": [[ 0, "desc" ]],
                            'iDisplayLength': 10000000,
                            "fnDrawCallback": function (oSettings) {

                                jQuery('#reportTypedGamePlaysByGenderTable_length').html('');
                                jQuery('#reportTypedGamePlaysByGenderTable_length').height(32);
                                jQuery('#reportTypedGamePlaysByGenderTable_paginate').html('');
                                jQuery('#reportTypedGamePlaysByGenderTable_info').html('');
                            }
                        });
                        jQuery('#reportTypedGamePlaysByGenderTable_filter label').contents().filter(function() {
                            return this.nodeType == 3
                        }).each(function(){
                            this.textContent = this.textContent.replace('Search','Search from Results ');
                        });
                    },200);

                    jQuery('#loading-header').hide();
                    jQuery('#no_data_message').hide();

                }
                else
                    jQuery('#additionalButtons').css('visibility','hidden');


            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });






    }

    function GetTempMembers(siteid, startdate, enddate){



        var url = lounge_api+"GetTempMembers";
        jQuery.ajax({
            url: url,
            headers: { apicode: apicode },
            data: {
                siteid: siteid,
                startdate: startdate,
                enddate: enddate
            },
            type: 'GET',
            crossDomain: true,
            dataType: 'json',
            beforeSend: function () {

                jQuery('#loading-header').show();
            },
            complete: function () {

                jQuery('#loading-header').hide();
            },
            success: function (result) {
                console.log('GetTempMembers success: function (result) ...');
                console.log(result);
                var data = result.members;

                if(data.length == 0) {
                    jQuery('#report_data').html('');
                    // jQuery('#default_message').show();
                    jQuery('#no_data_message').show();
                    return false;
                }

                var report_table = '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-bordered responsive" id="dyntable">';
                report_table += '<thead>';
                report_table += '<tr class="whitebgcolor">';
                report_table += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px;">Id</th>';
                report_table += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px;">Name</th>';
                report_table += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px;">Played Count</th>';
                report_table += '</tr>';
                report_table += '</thead>';
                report_table += '<tbody>';
                jQuery.each(data, function(i, v){
                    report_table += '<tr class="whitebgcolor">';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.id +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.name +'</td>';
                    report_table += '<td width="" align="left" class="black3_bo" style="padding-left:10px;">'+ v.playedcount +'</td>';
                    report_table += '</tr>';
                });
                report_table += '</tbody>';
                report_table += '</table>';

                jQuery('#report_data').html(report_table);

                jQuery('#dyntable').dataTable({
                    "sPaginationType": "full_numbers",
                    "destroy": true,
                    "order": [[ 0, "asc" ]]
                });


                jQuery('#loading-header').hide();
                jQuery('#no_data_message').hide();
            },
            error: function () {

                jQuery('#loading-header').hide();
            }
        });
    }

    function hs_default_message(){
        if(jQuery('#report_select').val() == '') {
            jQuery('#default_message').show();

            jQuery('.report-header').hide();
            jQuery('#report_filter').hide();
            jQuery('#report_data').hide();
            jQuery('#additionalButtons').css('visibility','hidden');
        } else {
            jQuery('#default_message').hide();

            jQuery('.report-header').show();
            jQuery('#report_filter').show();
            jQuery('#report_data').show();
            //jQuery('#additionalButtons').css('visibility','visible');
        }
    }

    function printDiv(print_type){

        var download = jQuery("#report_select option:selected").text().replace(/ /g, '_').toLowerCase();
        var filter_display = 'Summary';

        var lastTDborder ;
        var customStyleHeader="";
        var i=0, j=0, page=0;
        if (download == 'sales_report'){
            if(print_type==''){
                jQuery("#PrintTypeModal").modal('show');
                jQuery('#PrintTypeModal').css({top:'50%',left:'50%',margin:'-'+(jQuery('#PrintTypeModal').height() / 2)+'px 0 0 -'+(jQuery('#PrintTypeModal').width() / 2)+'px'});
                return false;
            }else{
                jQuery("#PrintTypeModal").modal('hide');
            }
            var len =jQuery('#reportSalesTable tr').length;
        }else  if (download == 'returning_members_including_additional_transactions_on_same_day' || download == 'number_of_visits_with_additional_transactions' || download == 'non_returning_members' || download == 'members_by_number_of_visits_on_multiple_dates' || download == 'returning_members_not_same_day'  ){
            var len =jQuery('#reportReturningMembersTable tr').length;
        }else if (download == 'members_list' || download == 'members_list_by_birthdate'){
            var len =jQuery('#reportMembersListTable tr').length;
        }else if (download == 'members_by_zip_code' || download == 'members_by_gender' || download == 'type_game_played' || download == "type_game_played_by_group"){
            var len =jQuery('#reportMembersZipcodeTable tr').length;
        }else if (download == "additional_transactions" ){
            var len =jQuery('#reportUpgradedMembersListTable tr').length;
        }else if (download == "type_game_played_by_gender" ){
            var len =jQuery('#reportTypedGamePlaysGenderTable tr').length;
        }else if (download == "member_registered_by_which_method" ){
            var len =jQuery('#reportMembersZipcodeTable tr').length;
        }else if (download == "registered_members_vs._paid_members_conversion_rate" ){
            var len =jQuery('#reportMembersZipcodeTable tr').length;
            customStyleHeader="font-size:14px;";
        }else if (download == "returning_members_with_visit_breakdown" || download == "members_registered_but_not_visited" ){
            var len =jQuery('#reportReturningMembersTable tr').length;
        }else{
            var len =jQuery('#dyntable tr').length;
        }

        flen = len-1;
        var html = "";
        var k=1;
        var fullreport="";
        var rowheight = 53;


        if (download == "returning_members_including_additional_transactions_on_same_day")
        {
            customStyleHeader="font-size:12px;";
        }
        else if (download == "member_registered_by_which_method")
        {
            customStyleHeader="font-size:16px;";
        }

        for (i=1; i<len; i++) {
            var isClose="N";
            if(k==1){
                if(i!=1){
                    if(eval(page)==3){
                        rowheight = 68;
                    }else{
                        rowheight = 53;
                    }
                }


                page=page + 1;
                html = "<html><title></title><div style=float: left; width: 100%; margin:0px; border: 1px solid #000; class=print-page><table cellpadding=0 cellspacing=0 id='print_"+i+"' style='margin-left:0px;margin-top:9px !important;width:100%;border:1px solid;position:relative;'>";






                html +="<thead><tr><td><span style='page-break-after:always !important;' ></span></td></tr><tr style='height:"+rowheight+"px !important;padding-top:0px;'><td colspan='100%' style='text-align:center;height:"+ rowheight +"px !important;border:2px solid !important;'>\                <div style='float:left;width:100%;height:20px;position:relative;top:0px;font-weight:bolder;margin-top:-12px;'><h3 style='text-transform:uppercase;"+customStyleHeader+"'>"+jQuery('.tabtitle').html()+"</h3></div>\                <div style='float:left;width:45%;text-align:left;font-size:1.7ex;font-weight:bolder;position:relative;top:-15px;padding-left:5px;height:20px;'>"+jQuery('.tabname').html()+"<br><br>"+jQuery('.tablocation').html()+"</div>\                <div style='float:right;width:45%;text-align:right;font-size:1.7ex;font-weight:bolder;position:relative;top:5px;padding-right:5px;height:20px;'>"+jQuery('.tabdate').html()+"</div>\                </td></tr>";



                if (download == 'sales_report')
                {
                    html +='<tr class="whitebgcolor">';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">DATE</th>';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">TIME</th>';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">LOUNGE</th>';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">MEMBER</th>';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">EXPERIENCE a</th>';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">NO. OF GUEST</th>';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">TOTAL PLAY TIME</th>';
                    html +='<th width="12%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px;">AMOUNT</th>';
                    html +='</tr>';

                }
                else if(download == 'total_hours_of_sl_time_purchased')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="50%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">SHOOTING LOUNGE</th>';
                    html += '<th width="50%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; ">TOTAL HOURS</th>';
                    html += '</tr>';
                }
                else if(download == 'new_memberships_paid')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">FIRST NAME</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">LAST NAME</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">DOB</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">CREATED DATE</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">MEMBERSHIP PAID DATE</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; ">MEMBERSHIP RENEWAL DATE</th>';
                    html += '</tr>';

                }
                else if (download == 'returning_members_including_additional_transactions_on_same_day' || download == 'number_of_visits_with_additional_transactions' || download == 'non_returning_members' || download == 'members_by_number_of_visits_on_multiple_dates' || download == 'returning_members_not_same_day' )
                {
                    html +='<tr class="whitebgcolor" role="row"><th width="8%" align="left" class="black3_bo sorting_asc" style="border: 1px solid black;padding-left: 10px; width: 80.2292px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Member Id: activate to sort column descending">MEMBER ID</th><th width="12%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 138.229px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending">FULL NAME</th><th width="12%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 138.229px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="NickName: activate to sort column ascending">NICKNAME</th><th width="8%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 80.2292px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="DOB: activate to sort column ascending">DOB</th><th width="12%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 216.229px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Email Address: activate to sort column ascending">EMAIL ADDRESS</th><th width="12%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 140.229px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="First Visit: activate to sort column ascending">FIRST VISIT</th><th width="12%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 140.229px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Last Visit: activate to sort column ascending">LAST VISIT</th><th width="18%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 229.229px;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Total Visits during Period: activate to sort column ascending">TOTAL VISITS DURING PERIOD</th></tr>';
                }
                else if (download == 'members_list' || download == 'members_list_by_birthdate')
                {
                    html +='<tr class="whitebgcolor" role="row" style="font-size:11px;"><th align="left" class="black3_bo sorting_asc" style="border: 1px solid black;padding-left: 10px; min-width: 150px; width: 199.667px; max-width: 200px !important;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending">NAME</th><th align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 96.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="Membership Number: activate to sort column ascending">MEMBERSHIP NUMBER</th><th align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 299.667px; max-width: 400px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="Email Address: activate to sort column ascending">EMAIL ADDRESS</th><th align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 96.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="Gender: activate to sort column ascending">GENDER</th><th align="left" class="black3_bo sorting" style="text-align: center;border: 1px solid black;padding-left: 10px; width: 96.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="DOB: activate to sort column ascending">DOB</th>';

                    if(download == 'members_list_by_birthdate')
                    {
                        html +='<th align="left" class="black3_bo sorting" style="text-align: center;border: 1px solid black;padding-left: 10px; width: 96.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="DOB: activate to sort column ascending">DOB (MM-DD)</th>';
                    }

                    html +='<th align="left" class="black3_bo sorting" style="text-align: center;border: 1px solid black;padding-left: 10px; width: 97.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">PHONE</th><th align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 97.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="City: activate to sort column ascending">CITY</th><th align="left" class="black3_bo sorting" style="text-align: center;border: 1px solid black;padding-left: 10px; width: 97.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="Zip Code: activate to sort column ascending">ZIP CODE</th><th align="left" class="black3_bo sorting" style="text-align: center;border: 1px solid black;padding-left: 10px; width: 97.6667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="Created Date: activate to sort column ascending">CREATED DATE</th><th class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; text-align: justify; width: 107.667px;" tabindex="0" aria-controls="reportMembersListTable" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">STATUS</th></tr>';
                }
                else if (download == 'members_by_zip_code')
                {
                    html +='<tr class="whitebgcolor" role="row"><th width="8%" align="left" class="black3_bo sorting_desc" style="border: 1px solid black;padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="Zip code: activate to sort column ascending">ZIP CODE</th><th width="12%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-label="Members: activate to sort column ascending">MEMBERS</th></tr>';
                }
                else if (download == 'members_by_gender')
                {
                    html +='<tr class="whitebgcolor" role="row"><th width="8%" align="left" class="black3_bo sorting_desc" style="border: 1px solid black;padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="Zip code: activate to sort column ascending">GENDER</th><th width="12%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-label="Members: activate to sort column ascending">MEMBERS</th></tr>';
                }
                else if (download == 'type_game_played')
                {
                    html +='<tr class="whitebgcolor" role="row"><th align="left" class="black3_bo sorting_desc" style="border: 1px solid black;padding-left: 10px; width: 546.5px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="ID: activate to sort column ascending">ID</th><th align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 546.5px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">NAME</th><th align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 546.5px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-label="Count: activate to sort column ascending">COUNT</th></tr>';
                }
                else if (download == 'additional_transactions')
                {
                    html +='<tr class="whitebgcolor" role="row" style="font-size:11px;">';
                    html +='<th align="left" class="black3_bo sorting" style="padding-left: 10px; width: 471.667px;border: 1px solid black;" tabindex="0" aria-controls="reportUpgradedMembersListTable" rowspan="1" colspan="1" aria-label="Member Count: activate to sort column ascending">SUBTYPE</th>';
                    html +='<th align="left" class="black3_bo sorting_asc" style="border: 1px solid black;padding-left: 10px; max-width: 200px !important; width: 471.667px;" tabindex="0" aria-controls="reportUpgradedMembersListTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Experience Name: activate to sort column descending">EXPERIENCE NAME</th>';
                    html +='<th align="left" class="black3_bo sorting center" style="border: 1px solid black;padding-left: 10px; width: 471.667px;" tabindex="0" aria-controls="reportUpgradedMembersListTable" rowspan="1" colspan="1" aria-label="Member Count: activate to sort column ascending">MEMBER COUNT</th>';
                    html +='<th align="left" class="black3_bo sorting center" style="border: 1px solid black;padding-left: 10px; width: 471.667px;" tabindex="0" aria-controls="reportUpgradedMembersListTable" rowspan="1" colspan="1" aria-label="Member Count: activate to sort column ascending">HOURS</th>';
                    html +='<th align="left" class="black3_bo sorting center" style="border: 1px solid black;padding-left: 10px; width: 471.667px;" tabindex="0" aria-controls="reportUpgradedMembersListTable" rowspan="1" colspan="1" aria-label="Count Percentage: activate to sort column ascending">COUNT PERCENTAGE</th>';
                    html +='</tr>';

                }
                else if(download == 'upgraded_sl_time_purchased')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">HEADER</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">ID</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">NAME</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">SITE LOCATION</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">STATUS</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px;">UPGRADED HOURS</th>';
                    html += '</tr>';
                }
                else if(download == 'registered_members_vs._paid_members_conversion_rate')
                {

                    html += '<tr class="whitebgcolor">';
                    html += '<th width="25%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">SOURCE</th>';
                    html += '<th width="25%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">REGISTERED</th>';
                    html += '<th width="25%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">PAID</th>';
                    html += '<th width="25%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px;">RATE</th>';
                    html += '</tr>';
                }
                else if(download == 'ranked_game_played')
                {

                    html += '<tr class="whitebgcolor">';
                    html += '<th width="50%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">EVENT NAME</th>';
                    html += '<th width="50%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px;">PLAYER RANK</th>';
                    html += '</tr>';
                }
                else if (download == 'type_game_played_by_gender')
                {
                    html +='<tr class="whitebgcolor" role="row"><th width="5%" align="left" class="black3_bo sorting_desc" style="border: 1px solid black;padding-left: 10px; width: 66.4985px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="Id: activate to sort column ascending">ID</th><th width="15%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 235.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Game: activate to sort column ascending">GAME</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Played By Men: activate to sort column ascending">PLAYED BY MEN</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Played Time: activate to sort column ascending">PLAYED TIME</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Played By Women: activate to sort column ascending">PLAYED BY WOMEN</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Played Time: activate to sort column ascending">PLAYED TIME</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Played By Junior: activate to sort column ascending">PLAYED BY JUNIOR</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Played Time: activate to sort column ascending">PLAYED TIME</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Total Played: activate to sort column ascending">TOTAL PLAYED</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Total Played Time: activate to sort column ascending">TOTAL PLAYED TIME</th><th width="10%" align="left" class="black3_bo sorting" style="border: 1px solid black;padding-left: 10px; width: 150.499px;" tabindex="0" aria-controls="reportTypedGamePlaysGenderTable" rowspan="1" colspan="1" aria-label="Total Played Time: activate to sort column ascending">TOTAL PLAYED ALL TIME</th></tr>';

                }
                else if(download == 'type_game_played')
                {

                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">ID</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">NAME</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px;">PLAYED COUNT</th>';
                    html += '</tr>';
                }
                else if(download == 'rpt_temp_reg_duplicate_email')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">ID</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">NAME</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px;">PLAYED COUNT</th>';
                    html += '</tr>';
                }
                else if(download == 'member_registered_by_which_method')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">REGISTRATION TYPE</th>';
                    html += '<th width="33%" align="right" class="black3_bo center" style="border: 1px solid black;padding-left:10px; border-right:1px solid; text-align:center;">MEMBERS</th>';
                    html += '</tr>';
                }
                else if(download == 'registered_members_vs._paid_members_conversion_rate')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">SOURCE</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">REGISTERED</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">PAID</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">RATE</th>';
                    html += '</tr>';
                }
                else if(download == 'returning_members_with_visit_breakdown')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">MEMBER ID</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">FULL NAME</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">NICKNAME</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">DOB</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">EMAIL ADDRESS</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">FIRST VISIT</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">LAST VISIT</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">TOTAL VISITS DURING PERIOD</th>';
                    html += '</tr>';
                }
                else if(download == 'type_game_played_by_group')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="10%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">ID</th>';
                    html += '<th width="30%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">NAME</th>';
                    html += '<th width="30%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">GROUP NAME</th>';
                    html += '<th width="20%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">GROUP COUNT</th>';
                    html += '<th width="20%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">COUNT</th>';
                    html += '</tr>';
                }
                else if(download == 'members_registered_but_not_visited')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">MEMBER ID</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">FULL NAME</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">NICKNAME</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">EMAIL ADDRESS</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">DOB</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="border: 1px solid black;padding-left:10px; border-right:1px solid;">Created Date</th>';
                    html += '</tr>';
                }



                isClose="Y";
            }

            html += "<tbody>";


            if (download == 'sales_report')
            {
                var innerColLen= jQuery('#reportSalesTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';
                        if(jQuery(jQuery('#reportSalesTable')[0].rows[0].cells[j]).html()=='Amount'){
                            html += "<td style='text-align:right !important; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; '>"+jQuery(jQuery('#reportSalesTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportSalesTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'returning_members_including_additional_transactions_on_same_day' || download == 'number_of_visits_with_additional_transactions' || download == 'non_returning_members' || download == 'members_by_number_of_visits_on_multiple_dates' || download == 'returning_members_not_same_day'  )
            {
                var innerColLen= jQuery('#reportReturningMembersTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportReturningMembersTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportReturningMembersTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'members_list' || download == 'members_list_by_birthdate')
            {
                var innerColLen= jQuery('#reportMembersListTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        var align = "text-align:"+jQuery(jQuery('#reportMembersListTable')[0].rows[i].cells[j]).css("text-align")+";";
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td index="+j+" style='border-top:1px solid;"+align+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportMembersListTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td index="+j+" style='border-top:1px solid;"+align+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportMembersListTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'members_by_zip_code' || download == 'members_by_gender' ||  download == 'type_game_played' ||  download == 'type_game_played_by_group')
            {
                var innerColLen= jQuery('#reportMembersZipcodeTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'additional_transactions')
            {
                var innerColLen= jQuery('#reportUpgradedMembersListTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        var align = "text-align:"+jQuery(jQuery('#reportUpgradedMembersListTable')[0].rows[i].cells[j]).css("text-align")+";";
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='"+align+"border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportUpgradedMembersListTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='"+align+"border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportUpgradedMembersListTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'type_game_played_by_gender')
            {
                var innerColLen= jQuery('#reportTypedGamePlaysGenderTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportTypedGamePlaysGenderTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportTypedGamePlaysGenderTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'member_registered_by_which_method')
            {
                var innerColLen= jQuery('#reportMembersZipcodeTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:center;border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'registered_members_vs._paid_members_conversion_rate')
            {
                var innerColLen= jQuery('#reportMembersZipcodeTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'returning_members_with_visit_breakdown')
            {
                var innerColLen= jQuery('#reportReturningMembersTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        var colspan='';

                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if(innerColLen==2)
                        {
                            colspan='colspan="4"';
                        }
                        else
                        {
                            lastTDborder+='font-weight:bold;';
                        }

                        if (j%2==1){
                            html += "<td "+colspan+" style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportReturningMembersTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td "+colspan+" style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportReturningMembersTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'members_registered_but_not_visited')
            {
                var innerColLen= jQuery('#reportReturningMembersTable ')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        var align = "text-align:"+jQuery(jQuery('#reportReturningMembersTable ')[0].rows[i].cells[j]).css("text-align")+";";
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td index="+j+" style='border-top:1px solid;"+align+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportReturningMembersTable ')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td index="+j+" style='border-top:1px solid;"+align+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportReturningMembersTable ')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else
            {

                var innerColLen= jQuery('#dyntable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#dyntable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#dyntable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }


            html +="</tbody>";
            k++;


        }
        if(k != 1){
            fullreport = fullreport + html + "</table></div></html>";
        }


        var pgtitle = '<?php echo $_SESSION['loc_name'];?>'+' - '+jQuery('.tabtitle').html()+' - '+jQuery('.tabdate').html();


        jQuery("#_print").html(fullreport);

        if(print_type=='80mm' && download == 'sales_report'){
            jQuery('#_print').css('width','303px');
            jQuery('#spItemDetails table').css('width','100%');
            jQuery("#_print").show();
            jQuery("#_print").printElement({pageTitle:pgtitle,isHeaderFooterEnabled:false},{
                overrideElementCSS:[
                    'css/style.default.css',
                    { href:'css/print_half.css',media:'print'}]
            });

            jQuery('.footer-left').hide();
            setTimeout(function(){
                jQuery("#_print").hide();
            },200);
        }else{
            jQuery('#_print').css('width','98%');
            jQuery('#spItemDetails table').css('width','100%');

            jQuery("#_print").show();
            jQuery("#_print").printElement({pageTitle:pgtitle,isHeaderFooterEnabled:false});
            jQuery('.footer-left').hide();
            setTimeout(function(){
                jQuery("#_print").hide();
            },200);
        }
    }

    function savePDF_(){

        var download = jQuery("#report_select option:selected").text().replace(/ /g, '_').toLowerCase();
        var filter_display = 'Summary';

        var lastTDborder;


        var reportHeader = "<div class=\"report-header\" style=\"\">\        <span class=\"tabname\" style=\"font-size: 16px;\" > "+ jQuery('.tabname').text()+"</span>\        <div class=\"tabtitle\" style=\" font-size: 17px;margin: -20px auto;padding: 0 5px 0 0;position: relative;text-align: center;width: 100%;\">"+ jQuery('.tabtitle').text()+"</div>\        <br>\        <span class=\"title_location tablocation\" >"+ jQuery('.title_location').text()+"</span><span class=\"tabdate\" style=\" float: right;font-size: 14px;\">"+ jQuery('.tabdate').text()+"</span>\        </div>" ;




        var i=0, j=0, page=0;


        if (download == 'sales_report')
            var len =jQuery('#reportSalesTable tr').length;
        else  if (download == 'returning_members_including_additional_transactions_on_same_day' || download == 'number_of_visits_with_additional_transactions' || download == 'non_returning_members'  )
            var len =jQuery('#reportReturningMembersTable tr').length;
        else if (download == 'members_by_zip_code' || download == 'members_by_gender' )
            var len =jQuery('#reportMembersZipcodeTable tr').length;
        else if (download == 'additional_transactions' )
            var len =jQuery('#reportUpgradedMembersListTable tr').length;
        else
            var len =jQuery('#dyntable tr').length;

        flen = len-1;
        var html = "";
        var k=1;
        var fullreport="";
        var rowheight = 70;

        for (i=1; i<len; i++) {
            var isClose="N";
            if(k==1){
                if(i!=1){
                    if(eval(page)==3){
                        rowheight = 68;
                    }else{
                        rowheight = 70;
                    }
                }

                page=page + 1;
                html = "<html><div style=float: left; width: 100%; margin:0px; border: 1px solid #000; class=print-page><table cellpadding=0 cellspacing=0 id='print_"+i+"' style='margin-left:0px;margin-top:9px !important;width:100%;border:1px solid;position:relative;'>";

                html +="<thead><tr><td><span style='page-break-after:always !important;' >Hello</span></td></tr><tr style='height:"+rowheight+"px !important;padding-top:0px;'><td colspan='100%' style='text-align:center;height:"+ rowheight +"px !important;border:2px solid !important;'>\                <div style='float:left;width:100%;height:20px;position:relative;top:0px;font-weight:bolder;margin-top:-12px;'><h3 style='text-transform:uppercase;'>"+jQuery('.tabtitle').html()+"</h3></div>\                <div style='float:left;width:45%;text-align:left;font-size:1.7ex;font-weight:bolder;position:relative;top:-15px;padding-left:5px;height:20px;'>"+jQuery('.tabname').html()+"<br><br>"+jQuery('.tablocation').html()+"</div>\                <div style='float:right;width:45%;text-align:right;font-size:1.7ex;font-weight:bolder;position:relative;top:5px;padding-right:5px;height:20px;'>"+jQuery('.tabdate').html()+"</div>\                </td></tr>";
                if (download == 'sales_report')
                {
                    html +='<tr class="whitebgcolor"><th width="12%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Time</th><th width="12%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Lounge</th><th width="12%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Status</th><th width="12%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Member</th><th width="12%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">No. of Guest</th><th width="12%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">User</th><th width="12%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Total Play Time</th><th width="12%" align="left" class="black3_bo" style="padding-left:10px;">Amount</th></tr>';

                }
                else if(download == 'total_hours_of_sl_time_purchased')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Shooting Lounge</th>';
                    html += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px; ">Total Hours</th>';
                    html += '</tr>';
                }
                else if(download == 'new_memberships_paid')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">First Name</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Last Name</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">DOB</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Created Date</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Membership Paid Date</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; ">Membership Renewal Date</th>';
                    html += '</tr>';

                }
                else if (download == 'returning_members_including_additional_transactions_on_same_day' || download == 'number_of_visits_with_additional_transactions' || download == 'non_returning_members'  )
                {
                    html +='<tr class="whitebgcolor" role="row"><th width="8%" align="left" class="black3_bo" style="padding-left: 10px; width: 80.2292px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Member Id: activate to sort column descending">Member Id</th><th width="12%" align="left" class="black3_bo" style="padding-left: 10px; width: 138.229px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Full Name: activate to sort column ascending">Full Name</th><th width="12%" align="left" class="black3_bo" style="padding-left: 10px; width: 138.229px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="NickName: activate to sort column ascending">NickName</th><th width="8%" align="left" class="black3_bo" style="padding-left: 10px; width: 80.2292px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="DOB: activate to sort column ascending">DOB</th><th width="12%" align="left" class="black3_bo" style="padding-left: 10px; width: 216.229px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Email Address: activate to sort column ascending">Email Address</th><th width="12%" align="left" class="black3_bo" style="padding-left: 10px; width: 140.229px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="First Visit: activate to sort column ascending">First Visit</th><th width="12%" align="left" class="black3_bo" style="padding-left: 10px; width: 140.229px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Last Visit: activate to sort column ascending">Last Visit</th><th width="18%" align="left" class="black3_bo" style="padding-left: 10px; width: 229.229px; border : 1px solid;" tabindex="0" aria-controls="reportReturningMembersTable" rowspan="1" colspan="1" aria-label="Total Visits during Period: activate to sort column ascending">Total Visits during Period</th></tr>';
                }
                else if (download == 'members_by_zip_code')
                {
                    html +='<tr class="whitebgcolor" role="row"><th width="8%" align="left" class="black3_bo sorting_desc" style="padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="Zip code: activate to sort column ascending">Zip code</th><th width="12%" align="left" class="black3_bo sorting" style="padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-label="Members: activate to sort column ascending">Members</th></tr>';
                }
                else if (download == 'members_by_gender')
                {
                    html +='<tr class="whitebgcolor" role="row"><th width="8%" align="left" class="black3_bo sorting_desc" style="padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-sort="descending" aria-label="Zip code: activate to sort column ascending">Gender</th><th width="12%" align="left" class="black3_bo sorting" style="padding-left: 10px; width: 697.229px;" tabindex="0" aria-controls="reportMembersZipcodeTable" rowspan="1" colspan="1" aria-label="Members: activate to sort column ascending">Members</th></tr>';
                }
                else if(download == 'upgraded_sl_time_purchased')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Header</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Id</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Name</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Site Location</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Status</th>';
                    html += '<th width="16%" align="left" class="black3_bo" style="padding-left:10px;">Upgraded Hours</th>';
                    html += '</tr>';
                }
                else if(download == 'registered_members_vs._paid_members_conversion_rate')
                {

                    html += '<tr class="whitebgcolor">';
                    html += '<th width="25%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Source</th>';
                    html += '<th width="25%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Registered</th>';
                    html += '<th width="25%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Paid</th>';
                    html += '<th width="25%" align="left" class="black3_bo" style="padding-left:10px;">Rate</th>';
                    html += '</tr>';
                }
                else if(download == 'ranked_game_played')
                {

                    html += '<tr class="whitebgcolor">';
                    html += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Event Name</th>';
                    html += '<th width="50%" align="left" class="black3_bo" style="padding-left:10px;">Player Rank</th>';
                    html += '</tr>';
                }
                else if(download == 'type_game_played')
                {

                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Id</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Name</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px;">Played Count</th>';
                    html += '</tr>';
                }
                else if(download == 'rpt_temp_reg_duplicate_email')
                {
                    html += '<tr class="whitebgcolor">';
                    html += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Id</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px; border-right:1px solid;">Name</th>';
                    html += '<th width="33%" align="left" class="black3_bo" style="padding-left:10px;">Played Count</th>';
                    html += '</tr>';
                }


                isClose="Y";
            }

            html += "<tbody>";


            if (download == 'sales_report')
            {
                var innerColLen= jQuery('#reportSalesTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportSalesTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportSalesTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'returning_members_including_additional_transactions_on_same_day' || download == 'number_of_visits_with_additional_transactions' || download == 'non_returning_members'  )
            {
                var innerColLen= jQuery('#reportReturningMembersTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportReturningMembersTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportReturningMembersTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else if (download == 'members_by_zip_code' || download == 'members_by_gender')
            {
                var innerColLen= jQuery('#reportMembersZipcodeTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportMembersZipcodeTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }
            else
            {
                jQuery('#table-id').val('reportUpgradedMembersListTable');
                var innerColLen= jQuery('#reportUpgradedMembersListTable')[0].rows[i].cells.length;
                {
                    html +="<tr style='font-size:1.7ex;'>";
                    for (j=0;j<innerColLen;j++)
                    {
                        if(j != innerColLen)
                            lastTDborder = 'border-right:1px solid;';
                        else
                            lastTDborder ='';

                        if (j%2==1){
                            html += "<td style='text-align:right; border-top:1px solid;"+lastTDborder+"padding:5px;height:30px; text-align:left; '>"+jQuery(jQuery('#reportUpgradedMembersListTable')[0].rows[i].cells[j]).html()+"</td>";
                        }else{
                            html += "<td style='border-top:1px solid;"+lastTDborder+"padding:5px;height:30px;'>"+jQuery(jQuery('#reportUpgradedMembersListTable')[0].rows[i].cells[j]).html()+"</td>";
                        }
                    }
                    html += "</tr>";

                }
            }


            html +="</tbody>";
            k++;


        }
        if(k != 1){
            fullreport = fullreport + html + "</table></div></html>";
        }


        var pgtitle = '<?php echo $_SESSION['loc_name'];?>'+' - '+jQuery('.tabtitle').html()+' - '+jQuery('.tabdate').html();



        g_fullreport =fullreport;
        jQuery('#savePdfContainer').show();
        jQuery('#savePdfContainer').html(g_fullreport);


        var table = tableToJson($('#table-id').get(0))

        var doc = new jsPDF();
        doc.setFontSize(8);
        var specialElementHandlers = {
            '#ignore1': function(element, renderer){
                return true;
            },
            '#ignore2': function(element, renderer){
                return true;
            },
            '#ignore3': function(element, renderer){
                return true;
            },
            '#ignore4':function(element, renderer){
                return true;
            },
            '#bypassme': function (element, renderer) {
                return true
            }
        };
        doc.fromHTML(jQuery('#savePdfContainer').html(), 5, 5, {
            'width': 500 ,
            'elementHandlers': specialElementHandlers,
        });
        doc.save('sample-file.pdf');

        console.log(doc);







    }


    function savePDF() {
        var download = jQuery("#report_select option:selected").text().replace(/ /g, '_').toLowerCase();
        var hiddenURL = jQuery("#hiddenURL").val();
        var pathURL = hiddenURL +'lounges_reports_pdf.php';

        var siteid = ( jQuery('#site_loaction').val() ) ? jQuery('#site_loaction').val() : '1';

        //var startDate = jQuery('#starttime').datepicker('getDate');
        //var startDate_bday = jQuery('#starttime_bday').datepicker('getDate');
        //var endDate =  jQuery('#endtime').datepicker('getDate');
        //var endDate_bday =  jQuery('#endtime_bday').datepicker('getDate');

        var from = jQuery('#starttime').val();
        var to = jQuery('#endtime').val();
        var query_string="";
        var searchStr=jQuery('#r_search').val();

        if(download=="number_of_visits_with_additional_transactions")
        {
            var visits=jQuery('#r_numVisits').val();
            var visitsTo=jQuery('#r_numVisitsTo').val();
            query_string="&targetAction='getVisits'&visitsTo="+visitsTo+"&visits="+visits;
        }
        if(download=="members_list")
        {
            var status=jQuery('#r_status option:selected').val();
            query_string+="&status="+status;
        }
        if(download=="members_list_by_birthdate")
        {
            from = jQuery('#starttime_bday').val();
            to = jQuery('#endtime_bday').val();
        }
        document.location = 'http://'+ pathURL +'?download='+ download +'&siteid='+siteid+'&from='+ from +'&searchStr='+searchStr+'&to='+ to + query_string;
    }
    var delayTimer;
    function sendMail(){
        var _mailAddress = jQuery("#mailaddress").val();
        jQuery("#loading-header").show();
        clearTimeout(delayTimer);

        delayTimer = setTimeout(function() {
            var download = jQuery("#report_select option:selected").text().replace(/ /g, '_').toLowerCase();
            var _hiddenURL = jQuery("#hiddenURL").val();
            var _pathURL = _hiddenURL +'lounges_reports_pdf_mailer.php';
            var _destName = jQuery("#destname").val();
            _mailAddress = jQuery.trim(_mailAddress);
            var mailValid = mailValidate(_mailAddress);
            var siteid = ( jQuery('#site_loaction').val() ) ? jQuery('#site_loaction').val() : '1';

            var from = jQuery('#starttime').val();
            var to = jQuery('#endtime').val();

            if(mailValid === true){
                jQuery('#close').click();
                var url  = location.protocol +'//'+ _pathURL;
                console.log(url);
                jQuery.ajax({
                    type: "GET",
                    url: url,
                    data: ({
                        mailaddress : _mailAddress,
                        destname : _destName
                        ,download : download
                        ,from: from
                        ,siteid:siteid
                        ,to: to
                    }),
                    cache: false,
                    success: function(data){
                        jQuery("#loading-header").hide();
                        jAlert(data,'Alert dialog');
                        jQuery('#mailaddress').val('');
                    }
                });
            }else{
                jQuery("#loading-header").hide();
                jQuery("#content-modal").html("Invalid Email Address");
            }
        }, 1500);
    }
    function mailValidate(mailaddress){
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        var address = mailaddress;
        if(reg.test(address) == false){
            return false;
        }else{
            return true;
        }
    }

    function createCookie(name, value, days) {
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        else {
            expires = "";
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function getCookie(c_name) {
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return "";
    }
</script>
<div class="modal hide fade" style="width:302px;display:none;" id="PrintTypeModal" >
    <div class="modal-header" style="text-align:left;" >
        <button type="button" class="close" data-dismiss="modal"	aria-hidden="true">&times;</button>
        <h4>Print Type</h4>
    </div>
    <div class="modal-body"  style="text-align:center;overflow:hidden;">

        <table style="width:270px" cellpadding="0" cellspacing="0" style="font-size:24px;" >
            <tr>
                <td width="50%"><button onClick="printDiv('80mm')" class="btn btn-primary">80 mm</button></td>
                <td width="50%"><button onClick="printDiv('normal')" class="btn btn-primary">Normal</button></td>
            </tr>
        </table>
    </div>
    <div class="modal-footer" style="text-align: center;">
        <p>
            <button data-dismiss="modal" class="btn">Cancel</button>
        </p>
    </div>
</div>
</html>