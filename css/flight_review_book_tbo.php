<?php    

include 'FLYTBOAPI/APIConstants.php';
include 'FLYTBOAPI/RestApiCaller.php';

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


$_SESSION['agentprice']=$_REQUEST['hgaid'];
$randval=rand('000000','999999');
$_SESSION['randval']=$randval;

$fromDestinationFlightArr=explode("-",$_SESSION['fromDestinationFlight']);
$toDestinationFlightArr=$_SESSION['toDestinationFlight'];

// checkLCC
$isLCCchk=0;
$a=GetPageRecord('*','wig_flight_json_bkp',' id="'.decode($_REQUEST['i']).'" and agentId="'.$_SESSION['agentUserid'].'"');
$res=mysqli_fetch_array($a);

$journeyDate1= date('Y-m-d',strtotime($res['DEP_DATE']));
$infantDate = strtotime($journeyDate1.' -2 year');
$infantDateValidate=date('Y-m-d',$infantDate);

$childDate = strtotime($journeyDate1.' -12 year');
$childDateValidate=date('Y-m-d',$childDate);

$isLCCchk=$res['IsLCC'];

if($_REQUEST['r']!=''){

$ab=GetPageRecord('*','wig_flight_json_bkp',' id="'.decode($_REQUEST['r']).'" and agentId="'.$_SESSION['agentUserid'].'"');
$resret=mysqli_fetch_array($ab);

$isLCCchk=$resret['IsLCC'];

$str_arr = explode (",", $resret['agfare']);  
$basefare = explode ("=", $str_arr[0]);
$basefareret = $basefare[1];


$bst = explode ("=", $str_arr[1]);
$basetaxret = $bst[1];

$bsf = explode ("=", $str_arr[2]);
$totalfareret = $bsf[1];

}


if($res['id']=="" || $res['id']<1){
echo "Something went wrong...<br>Please back to search page.";
exit();
}

$ResultIndex=$res['ResultIndex'];
$_SESSION['ResultIndex']=$ResultIndex;


$ResultIndex2=$resret['ResultIndex'];
$_SESSION['ResultIndex2']=$ResultIndex2;


//echo $res['ResultIndex'];


// Inboud and and out bound

if( ($_SESSION['tripType'] == 1 && $_SESSION['isdomestic'] == 'Yes')  || ($_SESSION['tripType'] == 2 && $_SESSION['isRoundTripInt'] == 1)  )
{ 


	//echo "test1";
	 include_once dirname(__FILE__).'/FLYTBOAPI/FlightFarequote.php';
	 
	 include_once dirname(__FILE__).'/FLYTBOAPI/FlightSSR.php';
//echo "test2";


}else{

	  // For Round Trip	
	 include_once dirname(__FILE__).'/FLYTBOAPI/FlightFarequote.php';
	 include_once dirname(__FILE__).'/FLYTBOAPI/FlightSSR.php';


	 include_once dirname(__FILE__).'/FLYTBOAPI/FlightFarequote2.php';
	 include_once dirname(__FILE__).'/FLYTBOAPI/FlightSSR2.php';
	 
 	//include_once dirname(__FILE__).'/FLYTBOAPI/FlightFarequoteReturnOB.php';
    //include_once dirname(__FILE__).'/FLYTBOAPI/FlightFarequoteReturnIB.php';  

}




/*echo "<pre>";
//print_r($_SESSION['search_result']);

print_r($fare_quote_result);
die;*/
/*
echo "<pre>";
print_r($ssr_result);*/


if(($_SESSION['tripType']==1) || ($_SESSION['tripType'] == 2 && $_SESSION['isRoundTripInt'] == 1) )
{

	$BaseFare=0;
	$Tax=0;
	$PublishedFare=0;
	/* ssr detail */
	$_SESSION['SSR']= $ssr_result; 
	
	//echo "<pre>";
//	print_r($_SESSION['SSR']);
//	die;
	
	$Baggages= $ssr_result['Response']['Baggage']['0'];
	$MealDynamic= $ssr_result['Response']['MealDynamic']['0'];
	$SpecialServices= $ssr_result['Response']['SpecialServices']['0']['SegmentSpecialService']['0']['SSRService'];
	$Meal= $ssr_result['Response']['Meal'];
	$SeatPreference= $ssr_result['Response']['SeatPreference'];
	$SeatDynamic= $ssr_result['Response']['SeatDynamic'];
	/* end ssr detail */
	
	$refstatuss= $fare_quote_result['Response']['Results']['IsRefundable'];
	if($refstatuss == 1){$refstatus= "Refundable";}else{$refstatus= "Non-Refundable";}
	
	unset($outbound); unset($inbound); unset($seg);
	
	if($fare_quote_result['Response']['ErrorCode']==0)
	{
		// Fare 
		$BaseFare=$fare_quote_result['Response']['Results']['Fare']['BaseFare'];
		$Tax=$fare_quote_result['Response']['Results']['Fare']['Tax'];
		$PublishedFare=$fare_quote_result['Response']['Results']['Fare']['PublishedFare'];
		
			
			//$flightType=1;
			//$getCalCost=calculateflightcost(encode($_SESSION['agentUserid']),$airline,$flightType,$ResultFareType,$toalPax,$totalPaxFare,$totalTax);
			//$getCalCost2=calculateflightcostForAgent(encode($agentid),$airline,$flightType,$ResultFareType,$toalPax,$totalPaxFare,$totalTax);
	
				
				$search_result = $fare_quote_result['Response']['Results'];
				if(is_array($search_result['Segments']['SegmentIndicator']))
				{
					 $seg[] = $search_result['Segments'];
				}
				else 
				{
					 $seg = $search_result['Segments'];
				
				}	
				
				
		  foreach($seg as $segment)
		  {
			 $outbound[] = $segment;
		  }
			

	  
	}

}


/*echo "<pre>";
print_r($ssr_result);
echo "<br>*****SSR2</br>";
print_r($ssr_result2);
die;*/

/******************* Trip Type2 *************************************************/

if($_SESSION['tripType'] == 2 && $_SESSION['isRoundTripInt']==0)
{

/******************************** farequote1******************************/

	$BaseFare=0;
	$Tax=0;
	$PublishedFare=0;
	/* ssr detail */
	//print_r($ssr_result);
	
	$_SESSION['SSR']= $ssr_result; 
	$_SESSION['fare_quote_result']= $fare_quote_result; 
	
	$Baggages= $ssr_result['Response']['Baggage']['0'];
	$MealDynamic= $ssr_result['Response']['MealDynamic']['0'];

	$SpecialServices= $ssr_result['Response']['SpecialServices']['0']['SegmentSpecialService']['0']['SSRService'];
	$Meal= $ssr_result['Response']['Meal'];
	$SeatPreference= $ssr_result['Response']['SeatPreference'];
	$SeatDynamic= $ssr_result['Response']['SeatDynamic'];
	/* end ssr detail */
	
	$refstatuss= $fare_quote_result['Response']['Results']['IsRefundable'];
	if($refstatuss == 1){$refstatus= "Refundable";}else{$refstatus= "Non-Refundable";}
	
	unset($outbound); unset($inbound); unset($seg);
	
	if($fare_quote_result['Response']['ErrorCode']==0)
	{
		// Fare 
		$BaseFare=$fare_quote_result['Response']['Results']['Fare']['BaseFare'];
		$Tax=$fare_quote_result['Response']['Results']['Fare']['Tax'];
		$PublishedFare=$fare_quote_result['Response']['Results']['Fare']['PublishedFare'];	
				
				$search_result = $fare_quote_result['Response']['Results'];
				if(is_array($search_result['Segments']['SegmentIndicator']))
				{
					 $seg[] = $search_result['Segments'];
				}
				else 
				{
					 $seg = $search_result['Segments'];
				}	
				
				
		  foreach($seg as $segment)
		  {
			 $outbound[] = $segment;
		  }
			

	  
	}


/******************************** farequote2******************************/
	$BaseFare2=0;
	$Tax2=0;
	$PublishedFare2=0;
	/* ssr detail */
	$_SESSION['SSR2']= $ssr_result2; 
	$Baggages2= $ssr_result2['Response']['Baggage']['0'];
	$MealDynamic2= $ssr_result2['Response']['MealDynamic']['0'];
	$SpecialServices2= $ssr_result2['Response']['SpecialServices']['0']['SegmentSpecialService']['0']['SSRService'];
	$Meal2= $ssr_result2['Response']['Meal'];
	$SeatPreference2= $ssr_result2['Response']['SeatPreference'];
	$SeatDynamic2= $ssr_result2['Response']['SeatDynamic'];
	/* end ssr detail */
	
	
	$refstatuss2= $fare_quote_result2['Response']['Results']['IsRefundable'];
	if($refstatuss2 == 1){$refstatus= "Refundable";}else{$refstatus= "Non-Refundable";}
	
	unset($outbound2); unset($inbound2); unset($seg2);
	
	if($fare_quote_result2['Response']['ErrorCode']==0)
	{
		// Fare 
		$BaseFare2=$fare_quote_result2['Response']['Results']['Fare']['BaseFare'];
		$Tax2=$fare_quote_result2['Response']['Results']['Fare']['Tax'];
		$PublishedFare2=$fare_quote_result2['Response']['Results']['Fare']['PublishedFare'];
		
	
				$search_result2 = $fare_quote_result2['Response']['Results'];
				if(is_array($search_result2['Segments']['SegmentIndicator']))
				{
					 $seg2[] = $search_result2['Segments'];
				}
				else 
				{
					 $seg2 = $search_result2['Segments'];
				
				}	
				
				
		  foreach($seg2 as $seg2ment)
		  {
			 $outbound2[] = $seg2ment;
		  }
			
	}
	

}

/*********End  Trip Type2 *************************************************/
/*echo "<pre>outbound";
print_r($outbound);
echo "<br>******Outbound2********<br>";

print_r($outbound2);*/



 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>Flight Booking Review - <?php echo stripslashes($getcompanybasicinfo['companyName']); ?></title>
<?php include "headerinc.php"; ?>

 
 

</head>

<body>

<?php include "header.php"; ?>

 
<div class="top_bg_ofr_sb2 flightreview">
<div class="container">
<table border="0" align="left" cellpadding="0" cellspacing="0">
  <tr>
    <td >
	 
<table border="0" cellpadding="0" cellspacing="0" class="flightreviewbox active" id="strp1"  >
  <tr>
    <td colspan="2"><div class="iconfa"><i class="fa fa-plane" aria-hidden="true"></i></div></td>
    <td><div class="steptext">FIRST STEP</div>Flight Itinerary</td>
  </tr>
</table>	</td>
	
    <td  class="showonlyaftercheck">
	 
	 <div class="midline"></div>	</td>
 
    <td  class="showonlyaftercheck"> 
	 
	<table border="0" cellpadding="0" cellspacing="0" class="flightreviewbox"  id="strp2"  >
  <tr>
    <td colspan="2"><div class="iconfa"><i class="fa fa-user" aria-hidden="true"></i></div></td>
    <td><div class="steptext">SECOND STEP</div>Passenger Details</td>
  </tr>
</table>	</td>
    <td  class="showonlyaftercheck"><div class="midline"></div></td>
    
    <td class="showonlyaftercheck"> <table border="0" cellpadding="0" cellspacing="0" class="flightreviewbox"  id="strp4">
  <tr>
    <td colspan="2"><div class="iconfa"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></div></td>
    <td><div class="steptext">FINISH STEP</div>Payments</td>
  </tr>
</table></td>
  </tr>
</table>

</div>
</div>


 


<div class="container" style="margin-top:20px; margin-bottom:20px;"> 
<form id="flightbookingsubmit" method="post" action="flight-booking-action2" target="bookingframe">
<div class="row" id="bookingdatainfo">
<div class="col-9" style="min-height:500px;">

<input name="coid" id="coid" type="hidden" value="0" >

<div class="row">
<div class="col-12" style="position:relative; margin-bottom:20px;"> 
<h2>Flight Details </h2>





 <div class="card cardresult" style="width:100%;" >

 <div class="card-header"><?php if($_SESSION['isRoundTripInt']==1){  ?>International Departure and Return<?php } else { ?>Departure<?php } ?></div>

 <div class="card-body" id="loadonewaytrip"><div style="text-align:center; padding:100px 0px; text-align:center;"><img src="images/loadinggif.gif" width="40" style="margin:auto;"></div></div>

 </div>

<script>

$('#loadonewaytrip').load('flightdetailsbox.php?id=<?php echo $res['id']; ?>&preview=1;');

</script>



<?php if($resret['id']>0){ ?>

<div class="card cardresult" style="width:100%;" >

 <div class="card-header">Return</div>

 <div class="card-body" id="loadonewaytripreturn"><div style="text-align:center; padding:100px 0px; text-align:center;"><img src="images/loadinggif.gif" width="40" style="margin:auto;"></div></div>

 </div>

<script>

$('#loadonewaytripreturn').load('flightdetailsbox.php?id=<?php echo $resret['id']; ?>&preview=1;');

</script>

<?php } ?>

<div class="cardresult" style="width:100%;">

<div style="display:none;">
<div class="card-header">
    <?php echo stripslashes($res['ORG_NAME']); ?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i> <?php echo stripslashes($res['DES_NAME']); ?> <span>on  <?php echo date('D, M d Y',strtotime($res['DEP_DATE'])); ?> &nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;<?php echo $res['DUR']; ?></span>
  </div>
<div class="card-body">
<div class="detailscontent">
<div class="row">
<div class="col-12">

<?php
$ifoffile=offlineflight($_SESSION['agentUserid'],$res['FLIGHT_NAME'],$res['PCC']);



?>

<?php
$j=0; 

//echo "<pre>";
//print_r ((unserialize(stripslashes($res['CON_DETAILS']))));

//if($isroundtrip && FLIGHT_TYPE == 'INT'){
if(($_SESSION['tripType'] == 2 && $_SESSION['isdomestic'] == 'No') || ($_SESSION['tripType'] == 2 && $_SESSION['isRoundTripInt'] == 1) )
{

   $outbound= $outbound;
   
  // echo 'Test1';
   
}
else
{
	$outbound= $outbound[0];
	//echo 'Test2';
}

//echo "<pre>";
//print_r($outbound);

if($_SESSION['tripType'] == 2 && $_SESSION['isRoundTripInt'] == 1)
{
	$outbound= $outbound[0];
}

foreach($outbound as $obound)
{


                        //echo '<pre>';print nl2br(print_r($outbound, true));echo '</pre>';

                        $NoOfSeatAvailable = $obound['NoOfSeatAvailable'];  
                        $Baggage = $obound['Baggage'];
                        $CabinBaggage = $obound['CabinBaggage'];
                        $SegmentIndicator = $obound['SegmentIndicator'];
                		$journeytime= $obound['Duration'];
                        $fdurhour= floor($journeytime/60); 
                        $fdurmint= $journeytime% 60; 
                        $deptime = $obound['Origin']['DepTime'];
                    	$depcity = $obound['Origin']['Airport']['CityName'].", ".$obound['Origin']['Airport']['CountryName']."(".$obound['Origin']['Airport']['AirportCode'].")";

        			    $depcityy = $obound['Origin']['Airport']['CityName'];
        			    $depTerminal = $ibound['Origin']['Airport']['Terminal'];
       			    	$tm1 = date('H:i',strtotime($deptime));
       			    	$dtms1 = date('D, d M',strtotime($deptime));
        				$dt1 = date('D, d M Y',strtotime($deptime));
        				$arrtime = $obound['Destination']['ArrTime'];
               	    	$arrcity = $obound['Destination']['Airport']['CityName'].", ".$obound['Destination']['Airport']['CountryName']."(".$obound['Destination']['Airport']['AirportCode'].")";
                	    $arrcityy = $obound['Destination']['Airport']['CityName'];
                	    $arrTerminal = $ibound['Destination']['Airport']['Terminal'];
                	    $tm2 = date('H:i',strtotime($arrtime));
                	    $dt2 = date('D, d M Y',strtotime($arrtime));
                	    $AirlineCode = $airlinecode = $obound['Airline']['AirlineCode'];
                	    $airline = $obound['Airline']['AirlineName'];
                	    $airlinenum = $airlinecode."-".$obound['Airline']['FlightNumber']." ".$obound['Airline']['FareClass'];
                	    $FlightNumber= $obound['Airline']['FlightNumber'];
						$ssrSeatFlight1=$obound['Origin']['Airport']['AirportCode']."-".$obound['Destination']['Airport']['AirportCode']." : ".$airlinecode." ".$FlightNumber;
	
?>
<div class="row multiflightbox">
<div class="col-3">
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="<?php echo $imgurl.getflightlogo(stripslashes($res['FLIGHT_NAME'])); ?>" width="32" height="32"></td>
    <td>
	<div class="flightname"><?php echo $airline; ?> </div>
	<div class="flightnumber"><?php echo $airlinecode; ?> <?php echo $FlightNumber; ?></div>
	
	</td>
  </tr>
</table>


</div>

<div class="col-9">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="33%" align="center">
	<div class="coltime">
	<?php echo $dt1; ?> <?php echo $tm1; ?></div>
	<div class="graysmalltext">
	<?php echo  $depcity; ?></div>
	</td>
    <td width="33%" align="center"><div class="nostops"><?php echo $fdurhour .'H , ' . $fdurmint.'M' ; ?></div> <div style="margin-top:2px;">Non-Stop</div></td>
    <td width="33%" align="center"><div class="coltime">
	<?php echo $dt2; ?> <?php echo $tm2;?></div>
	<div class="graysmalltext">
	<?php echo $arrcity; ?><br>
</div></td>
  </tr>
</table>

</div>

<?php
  
if($Baggage!="")
{
?>
<div style="margin:0px 0px;"><i class="fa fa-suitcase" aria-hidden="true"></i> <?php echo 'Baggage:'. $Baggage. ", Cabin Baggage: ".$CabinBaggage;?>
</div>
<?php
}
 ?>


<?php if($layoverFlight->LAYOVER_INFO!=''){ ?>
  <div style="text-align: center; color: #0b0b0b; padding: 5px; background-color: #e4f8ff; font-weight: 600; border-radius: 24px; margin-top:20px;margin-bottom:20px;"><?php echo $layoverFlight->LAYOVER_INFO; ?></div>
<?php } ?>
</div>

<?php  $j++; }  ?>
	
	
	  
<?php if($j==0){ ?>
<div class="row multiflightbox">
<div class="col-4">
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="<?php echo $imgurl.getflightlogo(stripslashes($res['FLIGHT_NAME'])); ?>" width="32" height="32"></td>
    <td>
	<div class="flightname"><?php echo stripslashes($res['FLIGHT_NAME']); ?></div>
	<div class="flightnumber"><?php echo stripslashes($res['FLIGHT_CODE']); ?>-<?php echo stripslashes($res['FLIGHT_NO']); ?></div>
	
	</td>
  </tr>
</table>

</div>

<div class="col-8">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="33%" align="center">
	<div class="coltime">
	<?php echo date('D, M d Y',strtotime(unserialize(stripslashes($res['searchJson']))->D_DATE)); ?> <?php echo stripslashes($res['DEP_TIME']); ?></div>
	<div class="graysmalltext">
	<?php echo stripslashes($res['ORG_NAME']); ?></div>
	</td>
    <td width="33%" align="center"><div class="nostops"><?php echo $res['DUR']; ?></div><div class="graysmalltext"><?php if($res['STOP']==0){ ?>
			Non Stop<?php  }else{ ?><span style="color:#bf0000 !important;"><?php echo $res['STOP'].' Stop '; ?></span><?php } ?></div></td>
    <td width="33%" align="center"><div class="coltime">
	<?php echo date('D, M d Y',strtotime(unserialize(stripslashes($res['searchJson']))->A_DATE)); ?><?php echo stripslashes($res['ARRV_TIME']); ?></div>
	<div class="graysmalltext">
	<?php echo stripslashes($res['DES_NAME']); ?></div></td>
  </tr>
</table>

</div>
</div>

<?php
  
$arr=explode("|",unserialize(stripslashes($res['searchJson']))->FLIGHT_INFO)
?>
<div style=" margin-top:20px;"><i class="fa fa-suitcase" aria-hidden="true"></i> <?php echo str_replace(':',': ',str_replace(',',', ',str_replace('=',': ',$arr[2]))); ?></div>
<?php } ?>



 
<div class="col-12" style="margin-top:20px;">
<button type="button" class="btn btn-outline-secondary btn-sm farerulebtn" onClick="showfarerule('<?php echo $ResultIndex; ?>');">Show Fare Rules</button>
</div> 
 <div style=" display:none;" id="showfarerule"></div>

</div>
</div>

</div>
</div>

<?php
if($_SESSION['tripType'] == 2 && $_SESSION['isRoundTripInt'] == 1)
{
	include("tbo_round_int_return_flight_detail.php");
}

 ?>


<?php if($resret['id']!=''){ ?>

<div class="card-header">
    <?php echo stripslashes($resret['ORG_NAME']); ?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i> <?php echo stripslashes($resret['DES_NAME']); ?> <span>on  <?php echo date('D, M d Y',strtotime($resret['DEP_DATE'])); ?> &nbsp;&nbsp;&nbsp;<i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;<?php echo $resret['DUR']; ?></span>
  </div>
<div class="card-body">
<div class="detailscontent">
<div class="row">
<div class="col-12">

<?php
$j=0; 

if($_SESSION['tripType'] == 2 && $_SESSION['isdomestic'] == 'No')
{

   $outbound2= $outbound2;
}
else
{
	$outbound2= $outbound2[0];
}

foreach($outbound2 as $obound)
{


                        //echo '<pre>';print nl2br(print_r($outbound, true));echo '</pre>';

                        $NoOfSeatAvailable = $obound['NoOfSeatAvailable'];  
                        $Baggage = $obound['Baggage'];
                        $CabinBaggage = $obound['CabinBaggage'];
                        $SegmentIndicator = $obound['SegmentIndicator'];
                		$journeytime= $obound['Duration'];
                        $fdurhour= floor($journeytime/60); 
                        $fdurmint= $journeytime% 60; 
                        $deptime = $obound['Origin']['DepTime'];
                    	$depcity = $obound['Origin']['Airport']['CityName'].", ".$obound['Origin']['Airport']['CountryName']."(".$obound['Origin']['Airport']['AirportCode'].")";

        			    $depcityy = $obound['Origin']['Airport']['CityName'];
        			    $depTerminal = $ibound['Origin']['Airport']['Terminal'];
       			    	$tm1 = date('H:i',strtotime($deptime));
       			    	$dtms1 = date('D, d M',strtotime($deptime));
        				$dt1 = date('D, d M Y',strtotime($deptime));
        				$arrtime = $obound['Destination']['ArrTime'];
               	    	$arrcity = $obound['Destination']['Airport']['CityName'].", ".$obound['Destination']['Airport']['CountryName']."(".$obound['Destination']['Airport']['AirportCode'].")";
                	    $arrcityy = $obound['Destination']['Airport']['CityName'];
                	    $arrTerminal = $ibound['Destination']['Airport']['Terminal'];
                	    $tm2 = date('H:i',strtotime($arrtime));
                	    $dt2 = date('D, d M Y',strtotime($arrtime));
                	    $AirlineCode = $airlinecode = $obound['Airline']['AirlineCode'];
                	    $airline = $obound['Airline']['AirlineName'];
                	    $airlinenum = $airlinecode."-".$obound['Airline']['FlightNumber']." ".$obound['Airline']['FareClass'];
                	    $FlightNumber= $obound['Airline']['FlightNumber'];
						
						$ssrSeatFlight2=$obound['Origin']['Airport']['AirportCode']."-".$obound['Destination']['Airport']['AirportCode']." : ".$airlinecode." ".$FlightNumber;
	
?>
<div class="row multiflightbox">
<div class="col-3">
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="<?php echo $imgurl.getflightlogo(stripslashes($resret['FLIGHT_NAME'])); ?>" width="32" height="32"></td>
    <td>
	<div class="flightname"><?php echo $airline; ?> </div>
	<div class="flightnumber"><?php echo $airlinecode; ?> <?php echo $FlightNumber; ?></div>
	
	</td>
  </tr>
</table>

</div>

<div class="col-9">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="33%" align="center">
	<div class="coltime">
	<?php echo $dt1; ?> <?php echo $tm1; ?></div>
	<div class="graysmalltext">
	<?php echo  $depcity; ?></div>
	</td>
    <td width="33%" align="center"><div class="nostops"><?php echo $fdurhour .'H , ' . $fdurmint.'M' ; ?></div> <div style="margin-top:2px;">Non-Stop</div></td>
    <td width="33%" align="center"><div class="coltime">
	<?php echo $dt2; ?> <?php echo $tm2;?></div>
	<div class="graysmalltext">
	<?php echo $arrcity; ?><br>
</div></td>
  </tr>
</table>

</div>

<?php
  
if($Baggage!="")
{
?>
<div style="margin:0px 0px;"><i class="fa fa-suitcase" aria-hidden="true"></i> <?php echo 'Baggage:'. $Baggage. ", Cabin Baggage: ".$CabinBaggage; ?>
</div>
<?php
}
?>


<?php if($layoverFlight->LAYOVER_INFO!=''){ ?>
  <div style="text-align: center; color: #0b0b0b; padding: 5px; background-color: #e4f8ff; font-weight: 600; border-radius: 24px; margin-top:20px;margin-bottom:20px;"><?php echo $layoverFlight->LAYOVER_INFO; ?></div>
<?php } ?>
</div>

<?php  $j++; }  ?>
	
	  
<?php if($j==0){ ?>

<div class="row multiflightbox">
<div class="col-4">
 <table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2"><img src="<?php echo $imgurl.getflightlogo(stripslashes($resret['FLIGHT_NAME'])); ?>" width="32" height="32"></td>
    <td>
	<div class="flightname"><?php echo stripslashes($resret['FLIGHT_NAME']); ?></div>
	<div class="flightnumber"><?php echo stripslashes($resret['FLIGHT_CODE']); ?>-<?php echo stripslashes($resret['FLIGHT_NO']); ?></div>
	
	</td>
  </tr>
</table>

</div>

<div class="col-8">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="33%" align="center">
	<div class="coltime">
	<?php echo date('D, M d Y',strtotime(unserialize(stripslashes($resret['searchJson']))->D_DATE)); ?> <?php echo stripslashes($resret['DEP_TIME']); ?></div>
	<div class="graysmalltext">
	<?php echo stripslashes($resret['ORG_NAME']); ?></div>
	</td>
    <td width="33%" align="center"><div class="nostops"><?php echo $resret['DUR']; ?></div><div class="graysmalltext"><?php if($resret['STOP']==0){ ?>
			Non Stop<?php  }else{ ?><span style="color:#bf0000 !important;"><?php echo $resret['STOP'].' Stop '; ?></span><?php } ?></div></td>
    <td width="33%" align="center"><div class="coltime">
	<?php echo date('D, M d Y',strtotime(unserialize(stripslashes($resret['searchJson']))->A_DATE)); ?><?php echo stripslashes($resret['ARRV_TIME']); ?></div>
	<div class="graysmalltext">
	<?php echo stripslashes($resret['DES_NAME']); ?></div></td>
  </tr>
</table>

</div>
</div>

<?php
  
$arr=explode("|",unserialize(stripslashes($resret['searchJson']))->FLIGHT_INFO)
?>
<div style=" margin-top:20px;"><i class="fa fa-suitcase" aria-hidden="true"></i> <?php echo str_replace(':',': ',str_replace(',',', ',str_replace('=',': ',$arr[2]))); ?></div>
<?php } ?>



 
<div class="col-12" style="margin-top:20px;">
<button type="button" class="btn btn-outline-secondary btn-sm farerulebtn2" onClick="showfarerule2('<?php echo $ResultIndex2; ?>');">Show Fare Rules</button>
</div> 
 <div style=" display:none;" id="showfarerule2"></div>

</div>
</div>

</div>
</div>
<?php } ?>
</div>
<div class="card-footer text-muted" style="display:none;">
   
   <button type="button" class="btn btn-danger btn-sm float-left"  style="display:none;"  onclick="history.back()"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back</button>
  
  
  
   <button type="button" class="btn btn-danger btn-sm" id="confirmingprice" style="float:right;background-color: #ffed00; color: #000; border: 1px solid #ffe600; font-weight: 600;">Confirming Price Wait Please...</button>
   
   <button type="button" class="btn btn-danger btn-sm" id="addpassengersbtn" style="float:right; display:none;"  onclick="$('#steponeflightdetails').hide();$('#steptwopassengerdetails').show();$('.flightreviewbox').removeClass('active');$('#strp2').addClass('active');$('#showfooterpay').hide();">Add Passengers</button>
  </div>
</div>

</div>


<div class="col-12" style="position:relative;display:none;"  id="steptwopassengerdetails">
<h2>Passenger Details

<script>
 function clearfield(){
 $('#steptwopassengerdetails input').val('');
 $('#steptwopassengerdetails select').val('');
 }
</script>

<button type="button" class="btn btn-danger btn-sm float-left" style="position: absolute; right: 80px; background-color:#CC3300;border:1px solid #CC3300;" onClick="clearfield();" >Clear</button><button type="button" class="btn btn-danger btn-sm float-left" style="position: absolute; right: 0px;" onClick="loadpop('Import Customer',this,'900px')" data-toggle="modal" data-target=".bs-example-modal-center" popaction="action=importcustomertobooking" >Import</button>
</h2>
<div class="card cardresult" style="width:100%;">



   <!-- Input -->
										<?php //$param_arr = unserialize(stripslashes($res['PARAM_DATA'])); ?>
										
										
										<?php for($adult=1; $adult<=$_SESSION['ADT']; $adult++){ ?>
										
							  		
							 <div class="card-header">Adult <?php echo $adult; ?> (12 + yrs)</div>
										 
						 <div class="card-body">
										<div class="row">
										<div class="col-sm-2 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Title
                                                </label>
												<select class="form-control validate1" name="titleAdt<?php echo $adult; ?>" id="titleAdt<?php echo $adult; ?>">
													<option value="">Select</option>
													<option value="Mr">Mr.</option>
													<option value="Mrs">Mrs</option>
													<option value="Ms">Ms.</option>
												</select>
                                            </div>
                                        </div>
										
                                        <div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    First Name
                                                </label>

                                                <input type="text" class="form-control validate1" name="firstNameAdt<?php echo $adult; ?>" id="firstNameAdt<?php echo $adult; ?>" placeholder="" aria-label="" required
                                                data-msg="Please enter your first name."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope">
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Input -->
                                        <div class="col-sm-3 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Last name
                                                </label>

                                                <input type="text" class="form-control validate1" name="lastNameAdt<?php echo $adult; ?>" id="lastNameAdt<?php echo $adult; ?>" required
                                                data-msg="Please enter your last name."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope">
                                            </div>
                                        </div>
										
										<div class="col-sm-3 mb-4" <?php if($_SESSION['isRoundTripInt'] != 1) {  ?>style="display:none;" <?php } ?>>
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    DOB
                                                </label>
												<div id="datepickerWrapperFromadt<?php echo $adult; ?>" class="u-datepicker input-group">
                        <div class="input-group-prepend"> <span class="d-flex align-items-center mr-2"> <i class="flaticon-calendar text-primary font-weight-semi-bold"></i> </span> </div>
												<input class="font-size-lg-16 form-control <?php if($_SESSION['isRoundTripInt']==1) {  ?>validate1 <?php } ?>border-1 datepickerfield"  id="dobAdt<?php echo $adult; ?>" name="dobAdt<?php echo $adult; ?>" readonly="readonly" value="<?php if($_SESSION['isRoundTripInt']!=1) {  ?>01-01-1988<?php } ?>">
												</div>	 
                                            </div>
                                        </div>
                                        <!-- End Input -->
										
										<div class="w-100"></div>
										
                                        <!-- Input -->
                                        
                                        <!-- End Input -->
										<?php if($_SESSION['isdomestic']=='No'){ ?>
                                        <!-- Input -->
										<div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Provided Country
                                                </label>
                                                <select class="form-control validate1 js-select selectpicker dropdown-select" id="nationalityAdt<?php echo $adult; ?>" name="nationalityAdt<?php echo $adult; ?>" data-msg="Please select country." data-error-class="u-has-error" data-success-class="u-has-success"
                                                    data-live-search="true"
                                                    data-style="form-control validate1 font-size-16 border-width-2 border-gray font-weight-normal">
                                                    <option value="">Select country</option>
                                                    <option value="AF">Afghanistan</option>
                                                    <option value="AX">Åland Islands</option>
                                                    <option value="AL">Albania</option>
                                                    <option value="DZ">Algeria</option>
                                                    <option value="AS">American Samoa</option>
                                                    <option value="AD">Andorra</option>
                                                    <option value="AO">Angola</option>
                                                    <option value="AI">Anguilla</option>
                                                    <option value="AQ">Antarctica</option>
                                                    <option value="AG">Antigua and Barbuda</option>
                                                    <option value="AR">Argentina</option>
                                                    <option value="AM">Armenia</option>
                                                    <option value="AW">Aruba</option>
                                                    <option value="AU">Australia</option>
                                                    <option value="AT">Austria</option>
                                                    <option value="AZ">Azerbaijan</option>
                                                    <option value="BS">Bahamas</option>
                                                    <option value="BH">Bahrain</option>
                                                    <option value="BD">Bangladesh</option>
                                                    <option value="BB">Barbados</option>
                                                    <option value="BY">Belarus</option>
                                                    <option value="BE">Belgium</option>
                                                    <option value="BZ">Belize</option>
                                                    <option value="BJ">Benin</option>
                                                    <option value="BM">Bermuda</option>

                                                    <option value="BT">Bhutan</option>
                                                    <option value="BO">Bolivia, Plurinational State of</option>
                                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                    <option value="BA">Bosnia and Herzegovina</option>
                                                    <option value="BW">Botswana</option>
                                                    <option value="BV">Bouvet Island</option>
                                                    <option value="BR">Brazil</option>
                                                    <option value="IO">British Indian Ocean Territory</option>
                                                    <option value="BN">Brunei Darussalam</option>
                                                    <option value="BG">Bulgaria</option>
                                                    <option value="BF">Burkina Faso</option>
                                                    <option value="BI">Burundi</option>
                                                    <option value="KH">Cambodia</option>
                                                    <option value="CM">Cameroon</option>
                                                    <option value="CA">Canada</option>
                                                    <option value="CV">Cape Verde</option>
                                                    <option value="KY">Cayman Islands</option>
                                                    <option value="CF">Central African Republic</option>
                                                    <option value="TD">Chad</option>
                                                    <option value="CL">Chile</option>
                                                    <option value="CN">China</option>
                                                    <option value="CX">Christmas Island</option>
                                                    <option value="CC">Cocos (Keeling) Islands</option>
                                                    <option value="CO">Colombia</option>
                                                    <option value="KM">Comoros</option>
                                                    <option value="CG">Congo</option>
                                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                                    <option value="CK">Cook Islands</option>
                                                    <option value="CR">Costa Rica</option>
                                                    <option value="CI">Côte d'Ivoire</option>
                                                    <option value="HR">Croatia</option>
                                                    <option value="CU">Cuba</option>
                                                    <option value="CW">Curaçao</option>
                                                    <option value="CY">Cyprus</option>
                                                    <option value="CZ">Czech Republic</option>
                                                    <option value="DK">Denmark</option>
                                                    <option value="DJ">Djibouti</option>
                                                    <option value="DM">Dominica</option>
                                                    <option value="DO">Dominican Republic</option>
                                                    <option value="EC">Ecuador</option>
                                                    <option value="EG">Egypt</option>
                                                    <option value="SV">El Salvador</option>
                                                    <option value="GQ">Equatorial Guinea</option>
                                                    <option value="ER">Eritrea</option>
                                                    <option value="EE">Estonia</option>
                                                    <option value="ET">Ethiopia</option>
                                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                                    <option value="FO">Faroe Islands</option>
                                                    <option value="FJ">Fiji</option>
                                                    <option value="FI">Finland</option>
                                                    <option value="FR">France</option>
                                                    <option value="GF">French Guiana</option>
                                                    <option value="PF">French Polynesia</option>
                                                    <option value="TF">French Southern Territories</option>
                                                    <option value="GA">Gabon</option>
                                                    <option value="GM">Gambia</option>
                                                    <option value="GE">Georgia</option>
                                                    <option value="DE">Germany</option>
                                                    <option value="GH">Ghana</option>
                                                    <option value="GI">Gibraltar</option>
                                                    <option value="GR">Greece</option>
                                                    <option value="GL">Greenland</option>
                                                    <option value="GD">Grenada</option>
                                                    <option value="GP">Guadeloupe</option>
                                                    <option value="GU">Guam</option>
                                                    <option value="GT">Guatemala</option>
                                                    <option value="GG">Guernsey</option>
                                                    <option value="GN">Guinea</option>
                                                    <option value="GW">Guinea-Bissau</option>
                                                    <option value="GY">Guyana</option>
                                                    <option value="HT">Haiti</option>
                                                    <option value="HM">Heard Island and McDonald Islands</option>
                                                    <option value="VA">Holy See (Vatican City State)</option>
                                                    <option value="HN">Honduras</option>
                                                    <option value="HK">Hong Kong</option>
                                                    <option value="HU">Hungary</option>
                                                    <option value="IS">Iceland</option>
                                                    <option value="IN" selected="selected">India</option>
                                                    <option value="ID">Indonesia</option>
                                                    <option value="IR">Iran, Islamic Republic of</option>
                                                    <option value="IQ">Iraq</option>
                                                    <option value="IE">Ireland</option>
                                                    <option value="IM">Isle of Man</option>
                                                    <option value="IL">Israel</option>
                                                    <option value="IT">Italy</option>
                                                    <option value="JM">Jamaica</option>
                                                    <option value="JP">Japan</option>
                                                    <option value="JE">Jersey</option>
                                                    <option value="JO">Jordan</option>
                                                    <option value="KZ">Kazakhstan</option>
                                                    <option value="KE">Kenya</option>
                                                    <option value="KI">Kiribati</option>
                                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                                    <option value="KR">Korea, Republic of</option>
                                                    <option value="KW">Kuwait</option>
                                                    <option value="KG">Kyrgyzstan</option>
                                                    <option value="LA">Lao People's Democratic Republic</option>
                                                    <option value="LV">Latvia</option>
                                                    <option value="LB">Lebanon</option>
                                                    <option value="LS">Lesotho</option>
                                                    <option value="LR">Liberia</option>
                                                    <option value="LY">Libya</option>
                                                    <option value="LI">Liechtenstein</option>
                                                    <option value="LT">Lithuania</option>
                                                    <option value="LU">Luxembourg</option>
                                                    <option value="MO">Macao</option>
                                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                    <option value="MG">Madagascar</option>
                                                    <option value="MW">Malawi</option>
                                                    <option value="MY">Malaysia</option>
                                                    <option value="MV">Maldives</option>
                                                    <option value="ML">Mali</option>
                                                    <option value="MT">Malta</option>
                                                    <option value="MH">Marshall Islands</option>
                                                    <option value="MQ">Martinique</option>
                                                    <option value="MR">Mauritania</option>
                                                    <option value="MU">Mauritius</option>
                                                    <option value="YT">Mayotte</option>
                                                    <option value="MX">Mexico</option>
                                                    <option value="FM">Micronesia, Federated States of</option>
                                                    <option value="MD">Moldova, Republic of</option>
                                                    <option value="MC">Monaco</option>
                                                    <option value="MN">Mongolia</option>
                                                    <option value="ME">Montenegro</option>
                                                    <option value="MS">Montserrat</option>
                                                    <option value="MA">Morocco</option>
                                                    <option value="MZ">Mozambique</option>
                                                    <option value="MM">Myanmar</option>
                                                    <option value="NA">Namibia</option>
                                                    <option value="NR">Nauru</option>
                                                    <option value="NP">Nepal</option>
                                                    <option value="NL">Netherlands</option>
                                                    <option value="NC">New Caledonia</option>
                                                    <option value="NZ">New Zealand</option>
                                                    <option value="NI">Nicaragua</option>
                                                    <option value="NE">Niger</option>
                                                    <option value="NG">Nigeria</option>
                                                    <option value="NU">Niue</option>
                                                    <option value="NF">Norfolk Island</option>
                                                    <option value="MP">Northern Mariana Islands</option>
                                                    <option value="NO">Norway</option>
                                                    <option value="OM">Oman</option>
                                                    <option value="PK">Pakistan</option>
                                                    <option value="PW">Palau</option>
                                                    <option value="PS">Palestinian Territory, Occupied</option>
                                                    <option value="PA">Panama</option>
                                                    <option value="PG">Papua New Guinea</option>
                                                    <option value="PY">Paraguay</option>
                                                    <option value="PE">Peru</option>
                                                    <option value="PH">Philippines</option>
                                                    <option value="PN">Pitcairn</option>
                                                    <option value="PL">Poland</option>
                                                    <option value="PT">Portugal</option>
                                                    <option value="PR">Puerto Rico</option>
                                                    <option value="QA">Qatar</option>
                                                    <option value="RE">Réunion</option>
                                                    <option value="RO">Romania</option>
                                                    <option value="RU">Russian Federation</option>
                                                    <option value="RW">Rwanda</option>
                                                    <option value="BL">Saint Barthélemy</option>
                                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                    <option value="KN">Saint Kitts and Nevis</option>
                                                    <option value="LC">Saint Lucia</option>
                                                    <option value="MF">Saint Martin (French part)</option>
                                                    <option value="PM">Saint Pierre and Miquelon</option>
                                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                                    <option value="WS">Samoa</option>
                                                    <option value="SM">San Marino</option>
                                                    <option value="ST">Sao Tome and Principe</option>
                                                    <option value="SA">Saudi Arabia</option>
                                                    <option value="SN">Senegal</option>
                                                    <option value="RS">Serbia</option>
                                                    <option value="SC">Seychelles</option>
                                                    <option value="SL">Sierra Leone</option>
                                                    <option value="SG">Singapore</option>
                                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                                    <option value="SK">Slovakia</option>
                                                    <option value="SI">Slovenia</option>
                                                    <option value="SB">Solomon Islands</option>
                                                    <option value="SO">Somalia</option>
                                                    <option value="ZA">South Africa</option>
                                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                    <option value="SS">South Sudan</option>
                                                    <option value="ES">Spain</option>
                                                    <option value="LK">Sri Lanka</option>
                                                    <option value="SD">Sudan</option>
                                                    <option value="SR">Suriname</option>
                                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                                    <option value="SZ">Swaziland</option>
                                                    <option value="SE">Sweden</option>
                                                    <option value="CH">Switzerland</option>
                                                    <option value="SY">Syrian Arab Republic</option>
                                                    <option value="TW">Taiwan, Province of China</option>
                                                    <option value="TJ">Tajikistan</option>
                                                    <option value="TZ">Tanzania, United Republic of</option>
                                                    <option value="TH">Thailand</option>
                                                    <option value="TL">Timor-Leste</option>
                                                    <option value="TG">Togo</option>
                                                    <option value="TK">Tokelau</option>
                                                    <option value="TO">Tonga</option>
                                                    <option value="TT">Trinidad and Tobago</option>
                                                    <option value="TN">Tunisia</option>
                                                    <option value="TR">Turkey</option>
                                                    <option value="TM">Turkmenistan</option>
                                                    <option value="TC">Turks and Caicos Islands</option>
                                                    <option value="TV">Tuvalu</option>
                                                    <option value="UG">Uganda</option>
                                                    <option value="UA">Ukraine</option>
                                                    <option value="AE">United Arab Emirates</option>
                                                    <option value="GB">United Kingdom</option>
                                                    <option value="US">United States</option>
                                                    <option value="UM">United States Minor Outlying Islands</option>
                                                    <option value="UY">Uruguay</option>
                                                    <option value="UZ">Uzbekistan</option>
                                                    <option value="VU">Vanuatu</option>
                                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                                    <option value="VN">Viet Nam</option>
                                                    <option value="VG">Virgin Islands, British</option>
                                                    <option value="VI">Virgin Islands, U.S.</option>
                                                    <option value="WF">Wallis and Futuna</option>
                                                    <option value="EH">Western Sahara</option>
                                                    <option value="YE">Yemen</option>
                                                    <option value="ZM">Zambia</option>
                                                    <option value="ZW">Zimbabwe</option>
                                                </select>
                                            </div>
                                        </div>
										
										
                                        <div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Number
                                                </label>
                                                <input type="text" class="form-control validate1" name="passportNumberAdt<?php echo $adult; ?>" id="passportNumberAdt<?php echo $adult;?>" placeholder="" aria-label="" 
                                                data-msg="Please enter passport number"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope" required>
                                            </div>
                                        </div>
										
										<div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Expiry
                                                </label>
                                                <input type="text" class="form-control validate1 datepickerfieldexpiry" name="passportExpiryAdt<?php echo $adult; ?>" id="passportExpiryAdt<?php echo $adult;?>" placeholder="" aria-label="" 
                                                data-msg="Please enter expiry Date"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope" required>
                                            </div>
                                        </div>
                                        <!-- End Input -->
										<?php } ?>
								 </div>
								 </div>
										
										<input name="totaladult" type="hidden" value="<?php echo $adult; ?>">
										
										<!---- SSR Detail ---->
										<div style="padding: 10px 20px; background-color: #f4f4f4; font-weight: 700;">Onward - SSR Details (Optional) </div>					
										
<div class="card-body">
<div class="row sliderpaxbox openpaxtabadult<?php echo $adult; ?>">

<?php
if($ssrSeatFlight1!='' && count($Baggages)>0)
{
 ?>
<div class="<?php 
$adults=$_SESSION['ADT'];
if($adults == '1'){ echo "col-sm-3";}else{echo "col-sm-4";} ?> mb-4">

  <div class="js-form-message">

	<label class="form-label"> Sector </label>

	<input class="form-control" type="text" name="asector<?php echo $adult; ?>" value="<?php echo $ssrSeatFlight1; ?>" readonly="yes" >

  </div>

</div>
<?php } ?>

<?php if(count($Baggages)>0){ ?>

<div class="<?php if($adults == '1'){ echo "col-sm-3";}else{echo "col-sm-4";} ?> mb-4">

  <div class="js-form-message">

	<label class="form-label"> Select Excess Baggage </label>

	<select name="abaggage<?php echo $adult; ?>" class="form-control adhendle adultbaggage" style="-moz-appearance: none;" tabindex="16" id="abaggage<?php echo $adult; ?>"  >

	  <option value="">---Select Baggage---</option>

	  <?php foreach($Baggages as $msBag){ ?>

	  <option value="<?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?>"><?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>

<?php } ?>

<?php
if(count($MealDynamic)>0)
{
 ?>
<div class="<?php if($adults == '1'){ echo "col-sm-3";}else{echo "col-sm-4";} ?> mb-4">

  <div class="js-form-message">

	<label class="form-label"> Meal Preferences </label>

	<select name="ameal<?php echo $adult; ?>" class="form-control adhendle adltmealCl" style="-moz-appearance: none;" tabindex="" id="adltmeal<?php echo $adult; ?>" >

	  <option value="">---Meal Preferences---</option>

	  <?php foreach($MealDynamic as $msmeal){ ?>

	  <option value="<?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?>"><?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>
<?php } ?>

<?php if(count($SeatDynamic)>0){ ?>

<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Seat </label>

	<a style="line-height: 1.0; max-width:200px;" class="btn btn-danger border-radius-3 d-flex align-items-center justify-content-center min-height-50 font-weight-bold border-width-2 py-2 w-100 popup-ajax" href="#new-card-dialog" data-effect="mfp-zoom-out" data-id="" data-passenger="seatAdult_<?php echo $adult; ?>">View Seat</a> </div>

</div>

<?php } ?>

</div>
</div>

<input type="hidden" id="seatAdultPrice<?php echo $adult; ?>" name="seatAdultPrice<?php echo $adult; ?>" value="0" />
<input type="hidden" id="seatAdultCode<?php echo $adult; ?>" name="seatAdultCode<?php echo $adult; ?>" value="" />
																				
<!--- End SSR details --->

<!--- Return SSR details --->
<?php
if($_SESSION['tripType']==2 && $_SESSION['isRoundTripInt'] == 0)
{

?> 
<div style="padding: 10px 20px; background-color: #f4f4f4; font-weight: 700;">Return - SSR Details (Optional) </div>
<div class="card-body">
<div class="row sliderpaxbox openpaxtabadult<?php echo $adult; ?>">

<div class="<?php 

$adults=$_SESSION['ADT'];

if($ssrSeatFlight2!="" && count($Baggages2)>0 )
{

if($adults == '1'){ echo "col-sm-3";}else{echo "col-sm-4";} ?> mb-4">

  <div class="js-form-message">

	<label class="form-label"> Sector </label>

	<input class="form-control" type="text" name="asector2<?php echo $adult; ?>" value="<?php echo $ssrSeatFlight2; ?>" readonly="yes" >

  </div>

</div>

<?php } if(count($Baggages2)>0){ ?>

<div class="<?php if($adults == '1'){ echo "col-sm-3";}else{echo "col-sm-4";} ?> mb-4">

  <div class="js-form-message">

	<label class="form-label"> Select Excess Baggage </label>

	<select name="abaggage2<?php echo $adult; ?>" class="form-control adhendle adultbaggageReturn" style="-moz-appearance: none;" tabindex="16" id="abaggage2<?php echo $adult; ?>"  >

	  <option value="">---Select Baggage---</option>

	  <?php foreach($Baggages2 as $msBag){ ?>

	  <option value="<?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?>"><?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>

<?php } ?>

<?php if(count($MealDynamic2)>0) { ?>
<div class="<?php if($adults == '1'){ echo "col-sm-3";}else{echo "col-sm-4";} ?> mb-4">

  <div class="js-form-message">

	<label class="form-label"> Meal Preferences </label>

	<select name="ameal2<?php echo $adult; ?>" class="form-control adhendle adltmealClReturn" style="-moz-appearance: none;" tabindex="" id="ameal2<?php echo $adult; ?>" >

	  <option value="">---Meal Preferences---</option>

	  <?php foreach($MealDynamic2 as $msmeal){ ?>

	  <option value="<?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?>"><?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>

<?php } ?>

<?php if(count($SeatDynamic2)>0){ ?>

<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Seat </label>

	<a style="line-height: 1.0; max-width:200px;" class="btn btn-danger border-radius-3 d-flex align-items-center justify-content-center min-height-50 font-weight-bold border-width-2 py-2 w-100 popup-ajax2" href="#new-card-dialog" data-effect="mfp-zoom-out" data-id="" data-passenger="seatAdult2_<?php echo $adult; ?>">View Seat</a> </div>

</div>

<?php } ?>

</div>
</div>

<input type="hidden" id="seatAdultPrice2<?php echo $adult; ?>" name="seatAdultPrice2<?php echo $adult; ?>" value="0" />
<input type="hidden" id="seatAdultCode2<?php echo $adult; ?>" name="seatAdultCode2<?php echo $adult; ?>" value="" />

<?php } ?>
<!--- Return End SSR details --->

										
										
										
										<?php }
										$totaladultcount=$adult;
										 ?>
										
									
										
										<?php
										for($child=1; $child<=$_SESSION['CHD']; $child++){
										?>
										 				
							 <div class="card-header">Child <?php echo $child; ?> (2 + yrs)</div>
							   			 
						 <div class="card-body">
										
				
										<div class="row">
										
										<div class="col-sm-2 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Title
                                                </label>
												<select class="form-control validate1" name="titleChd<?php echo $child; ?>" id="titleChd<?php echo $child; ?>">
													<option value="">Select</option>
													<option value="Mr">Mr</option>
													<option value="Mrs">Mrs</option>
													<option value="Ms">Ms</option>
													<option value="Mstr">Mstr</option>
													<option value="Miss">Miss</option>
												</select>
                                            </div>
                                        </div>
										
                                        <div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    First Name
                                                </label>

                                                <input type="text" class="form-control validate1" name="firstNameChd<?php echo $child; ?>" id="firstNameChd<?php echo $child; ?>" placeholder="" aria-label="" required
                                                data-msg="Please enter your first name."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope">
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Input -->
                                        <div class="col-sm-3 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Last name
                                                </label>

                                                <input type="text" class="form-control validate1" name="lastNameChd<?php echo $child; ?>" id="lastNameChd<?php echo $child; ?>" required
                                                data-msg="Please enter your last name."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope">
                                            </div>
                                        </div>
										
										<div class="col-sm-3 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    DOB
                                                </label>
												<input class="form-control validate1 datepickerfieldchild"  id="dobChd<?php echo $child; ?>" name="dobChd<?php echo $child; ?>" readonly="readonly">				 
                                            </div>
                                        </div>
										
									<?php if($_SESSION['isdomestic']=='No'){ ?>
                                        <!-- Input -->
										<div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Provided Country
                                                </label>
                                                <select class="form-control js-select selectpicker dropdown-select" id="nationalityChd<?php echo $child; ?>" name="nationalityChd<?php echo $child; ?>"  data-msg="Please select country." data-error-class="u-has-error" data-success-class="u-has-success"
                                                    data-live-search="true"
                                                    data-style="form-control font-size-16 border-width-2 border-gray font-weight-normal">
                                                    <option value="">Select country</option>
                                                    <option value="AF">Afghanistan</option>
                                                    <option value="AX">Åland Islands</option>
                                                    <option value="AL">Albania</option>
                                                    <option value="DZ">Algeria</option>
                                                    <option value="AS">American Samoa</option>
                                                    <option value="AD">Andorra</option>
                                                    <option value="AO">Angola</option>
                                                    <option value="AI">Anguilla</option>
                                                    <option value="AQ">Antarctica</option>
                                                    <option value="AG">Antigua and Barbuda</option>
                                                    <option value="AR">Argentina</option>
                                                    <option value="AM">Armenia</option>
                                                    <option value="AW">Aruba</option>
                                                    <option value="AU">Australia</option>
                                                    <option value="AT">Austria</option>
                                                    <option value="AZ">Azerbaijan</option>
                                                    <option value="BS">Bahamas</option>
                                                    <option value="BH">Bahrain</option>
                                                    <option value="BD">Bangladesh</option>
                                                    <option value="BB">Barbados</option>
                                                    <option value="BY">Belarus</option>
                                                    <option value="BE">Belgium</option>
                                                    <option value="BZ">Belize</option>
                                                    <option value="BJ">Benin</option>
                                                    <option value="BM">Bermuda</option>
                                                    <option value="BT">Bhutan</option>
                                                    <option value="BO">Bolivia, Plurinational State of</option>
                                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                    <option value="BA">Bosnia and Herzegovina</option>
                                                    <option value="BW">Botswana</option>
                                                    <option value="BV">Bouvet Island</option>
                                                    <option value="BR">Brazil</option>
                                                    <option value="IO">British Indian Ocean Territory</option>
                                                    <option value="BN">Brunei Darussalam</option>
                                                    <option value="BG">Bulgaria</option>
                                                    <option value="BF">Burkina Faso</option>
                                                    <option value="BI">Burundi</option>
                                                    <option value="KH">Cambodia</option>
                                                    <option value="CM">Cameroon</option>
                                                    <option value="CA">Canada</option>
                                                    <option value="CV">Cape Verde</option>
                                                    <option value="KY">Cayman Islands</option>
                                                    <option value="CF">Central African Republic</option>
                                                    <option value="TD">Chad</option>
                                                    <option value="CL">Chile</option>
                                                    <option value="CN">China</option>
                                                    <option value="CX">Christmas Island</option>
                                                    <option value="CC">Cocos (Keeling) Islands</option>
                                                    <option value="CO">Colombia</option>
                                                    <option value="KM">Comoros</option>
                                                    <option value="CG">Congo</option>
                                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                                    <option value="CK">Cook Islands</option>
                                                    <option value="CR">Costa Rica</option>
                                                    <option value="CI">Côte d'Ivoire</option>
                                                    <option value="HR">Croatia</option>
                                                    <option value="CU">Cuba</option>
                                                    <option value="CW">Curaçao</option>
                                                    <option value="CY">Cyprus</option>
                                                    <option value="CZ">Czech Republic</option>
                                                    <option value="DK">Denmark</option>
                                                    <option value="DJ">Djibouti</option>
                                                    <option value="DM">Dominica</option>
                                                    <option value="DO">Dominican Republic</option>
                                                    <option value="EC">Ecuador</option>
                                                    <option value="EG">Egypt</option>
                                                    <option value="SV">El Salvador</option>
                                                    <option value="GQ">Equatorial Guinea</option>
                                                    <option value="ER">Eritrea</option>
                                                    <option value="EE">Estonia</option>
                                                    <option value="ET">Ethiopia</option>
                                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                                    <option value="FO">Faroe Islands</option>
                                                    <option value="FJ">Fiji</option>
                                                    <option value="FI">Finland</option>
                                                    <option value="FR">France</option>
                                                    <option value="GF">French Guiana</option>

                                                    <option value="PF">French Polynesia</option>
                                                    <option value="TF">French Southern Territories</option>
                                                    <option value="GA">Gabon</option>
                                                    <option value="GM">Gambia</option>
                                                    <option value="GE">Georgia</option>
                                                    <option value="DE">Germany</option>
                                                    <option value="GH">Ghana</option>
                                                    <option value="GI">Gibraltar</option>
                                                    <option value="GR">Greece</option>
                                                    <option value="GL">Greenland</option>
                                                    <option value="GD">Grenada</option>
                                                    <option value="GP">Guadeloupe</option>
                                                    <option value="GU">Guam</option>
                                                    <option value="GT">Guatemala</option>
                                                    <option value="GG">Guernsey</option>
                                                    <option value="GN">Guinea</option>
                                                    <option value="GW">Guinea-Bissau</option>
                                                    <option value="GY">Guyana</option>
                                                    <option value="HT">Haiti</option>
                                                    <option value="HM">Heard Island and McDonald Islands</option>
                                                    <option value="VA">Holy See (Vatican City State)</option>
                                                    <option value="HN">Honduras</option>
                                                    <option value="HK">Hong Kong</option>
                                                    <option value="HU">Hungary</option>
                                                    <option value="IS">Iceland</option>
                                                    <option value="IN" selected="selected">India</option>
                                                    <option value="ID">Indonesia</option>
                                                    <option value="IR">Iran, Islamic Republic of</option>
                                                    <option value="IQ">Iraq</option>
                                                    <option value="IE">Ireland</option>
                                                    <option value="IM">Isle of Man</option>
                                                    <option value="IL">Israel</option>
                                                    <option value="IT">Italy</option>
                                                    <option value="JM">Jamaica</option>
                                                    <option value="JP">Japan</option>
                                                    <option value="JE">Jersey</option>
                                                    <option value="JO">Jordan</option>
                                                    <option value="KZ">Kazakhstan</option>
                                                    <option value="KE">Kenya</option>
                                                    <option value="KI">Kiribati</option>
                                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                                    <option value="KR">Korea, Republic of</option>
                                                    <option value="KW">Kuwait</option>
                                                    <option value="KG">Kyrgyzstan</option>
                                                    <option value="LA">Lao People's Democratic Republic</option>
                                                    <option value="LV">Latvia</option>
                                                    <option value="LB">Lebanon</option>
                                                    <option value="LS">Lesotho</option>
                                                    <option value="LR">Liberia</option>
                                                    <option value="LY">Libya</option>
                                                    <option value="LI">Liechtenstein</option>
                                                    <option value="LT">Lithuania</option>
                                                    <option value="LU">Luxembourg</option>
                                                    <option value="MO">Macao</option>
                                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                    <option value="MG">Madagascar</option>
                                                    <option value="MW">Malawi</option>
                                                    <option value="MY">Malaysia</option>
                                                    <option value="MV">Maldives</option>
                                                    <option value="ML">Mali</option>
                                                    <option value="MT">Malta</option>
                                                    <option value="MH">Marshall Islands</option>
                                                    <option value="MQ">Martinique</option>
                                                    <option value="MR">Mauritania</option>
                                                    <option value="MU">Mauritius</option>
                                                    <option value="YT">Mayotte</option>
                                                    <option value="MX">Mexico</option>
                                                    <option value="FM">Micronesia, Federated States of</option>
                                                    <option value="MD">Moldova, Republic of</option>
                                                    <option value="MC">Monaco</option>
                                                    <option value="MN">Mongolia</option>
                                                    <option value="ME">Montenegro</option>
                                                    <option value="MS">Montserrat</option>
                                                    <option value="MA">Morocco</option>
                                                    <option value="MZ">Mozambique</option>
                                                    <option value="MM">Myanmar</option>
                                                    <option value="NA">Namibia</option>
                                                    <option value="NR">Nauru</option>
                                                    <option value="NP">Nepal</option>
                                                    <option value="NL">Netherlands</option>
                                                    <option value="NC">New Caledonia</option>
                                                    <option value="NZ">New Zealand</option>
                                                    <option value="NI">Nicaragua</option>
                                                    <option value="NE">Niger</option>
                                                    <option value="NG">Nigeria</option>
                                                    <option value="NU">Niue</option>
                                                    <option value="NF">Norfolk Island</option>
                                                    <option value="MP">Northern Mariana Islands</option>
                                                    <option value="NO">Norway</option>
                                                    <option value="OM">Oman</option>
                                                    <option value="PK">Pakistan</option>
                                                    <option value="PW">Palau</option>
                                                    <option value="PS">Palestinian Territory, Occupied</option>
                                                    <option value="PA">Panama</option>
                                                    <option value="PG">Papua New Guinea</option>
                                                    <option value="PY">Paraguay</option>
                                                    <option value="PE">Peru</option>
                                                    <option value="PH">Philippines</option>
                                                    <option value="PN">Pitcairn</option>
                                                    <option value="PL">Poland</option>
                                                    <option value="PT">Portugal</option>
                                                    <option value="PR">Puerto Rico</option>
                                                    <option value="QA">Qatar</option>
                                                    <option value="RE">Réunion</option>
                                                    <option value="RO">Romania</option>
                                                    <option value="RU">Russian Federation</option>
                                                    <option value="RW">Rwanda</option>
                                                    <option value="BL">Saint Barthélemy</option>
                                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                    <option value="KN">Saint Kitts and Nevis</option>
                                                    <option value="LC">Saint Lucia</option>
                                                    <option value="MF">Saint Martin (French part)</option>
                                                    <option value="PM">Saint Pierre and Miquelon</option>
                                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                                    <option value="WS">Samoa</option>
                                                    <option value="SM">San Marino</option>
                                                    <option value="ST">Sao Tome and Principe</option>
                                                    <option value="SA">Saudi Arabia</option>
                                                    <option value="SN">Senegal</option>
                                                    <option value="RS">Serbia</option>
                                                    <option value="SC">Seychelles</option>
                                                    <option value="SL">Sierra Leone</option>
                                                    <option value="SG">Singapore</option>
                                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                                    <option value="SK">Slovakia</option>
                                                    <option value="SI">Slovenia</option>
                                                    <option value="SB">Solomon Islands</option>
                                                    <option value="SO">Somalia</option>
                                                    <option value="ZA">South Africa</option>
                                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                    <option value="SS">South Sudan</option>
                                                    <option value="ES">Spain</option>
                                                    <option value="LK">Sri Lanka</option>
                                                    <option value="SD">Sudan</option>
                                                    <option value="SR">Suriname</option>
                                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                                    <option value="SZ">Swaziland</option>
                                                    <option value="SE">Sweden</option>
                                                    <option value="CH">Switzerland</option>
                                                    <option value="SY">Syrian Arab Republic</option>
                                                    <option value="TW">Taiwan, Province of China</option>
                                                    <option value="TJ">Tajikistan</option>
                                                    <option value="TZ">Tanzania, United Republic of</option>
                                                    <option value="TH">Thailand</option>
                                                    <option value="TL">Timor-Leste</option>
                                                    <option value="TG">Togo</option>
                                                    <option value="TK">Tokelau</option>
                                                    <option value="TO">Tonga</option>
                                                    <option value="TT">Trinidad and Tobago</option>
                                                    <option value="TN">Tunisia</option>
                                                    <option value="TR">Turkey</option>
                                                    <option value="TM">Turkmenistan</option>
                                                    <option value="TC">Turks and Caicos Islands</option>
                                                    <option value="TV">Tuvalu</option>
                                                    <option value="UG">Uganda</option>
                                                    <option value="UA">Ukraine</option>
                                                    <option value="AE">United Arab Emirates</option>
                                                    <option value="GB">United Kingdom</option>
                                                    <option value="US">United States</option>
                                                    <option value="UM">United States Minor Outlying Islands</option>
                                                    <option value="UY">Uruguay</option>
                                                    <option value="UZ">Uzbekistan</option>
                                                    <option value="VU">Vanuatu</option>
                                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                                    <option value="VN">Viet Nam</option>
                                                    <option value="VG">Virgin Islands, British</option>
                                                    <option value="VI">Virgin Islands, U.S.</option>
                                                    <option value="WF">Wallis and Futuna</option>
                                                    <option value="EH">Western Sahara</option>
                                                    <option value="YE">Yemen</option>
                                                    <option value="ZM">Zambia</option>
                                                    <option value="ZW">Zimbabwe</option>
                                                </select>
                                            </div>
                                        </div>
										
                                        <div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Number
                                                </label>
                                                <input type="text" class="form-control datepickerfieldexpiry" name="passportNumberChd<?php echo $child; ?>" id="passportNumberChd<?php echo $child;?>" placeholder="" aria-label="" 
                                                data-msg="Please enter passport number"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope" required>
                                            </div>
                                        </div>
                                        <!-- End Input -->
										<div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Expiry
                                                </label>
                                                <input type="text" class="form-control" name="passportExpiryChd<?php echo $child; ?>" id="passportExpiryChd<?php echo $child;?>" placeholder="" aria-label="" 
                                                data-msg="Please enter expiry Date"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope" required>
                                            </div>
                                        </div>
										
										<?php } ?>
										
										
										</div>
										
										</div>
                                    <input name="totalchild" type="hidden" value="<?php echo $child; ?>">
									
										
										<!---- SSR Detail ---->
										<div>Onward - SSR Details (Optional) </div>
										

<div class="card-body">
<div class="row sliderpaxbox openpaxtabadult<?php echo $child; ?>">

<?php
if($ssrSeatFlight1!='')
{
 ?>
<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Sector </label>

	<input class="form-control" type="text" name="csector<?php echo $child; ?>" value="<?php echo $ssrSeatFlight1; ?>" readonly="yes" >

  </div>

</div>
<?php } ?>

<?php if($Baggages){ ?>

<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Select Excess Baggage </label>

	<select name="cbaggage<?php echo $child; ?>" class="form-control adhendle adultbaggage" style="-moz-appearance: none;" tabindex="16" id="cbaggage<?php echo $child; ?>"  >

	  <option value="">---Select Baggage---</option>

	  <?php foreach($Baggages as $msBag){ ?>

	  <option value="<?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?>"><?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>

<?php } ?>

<?php
if(count($MealDynamic)>0)
{
 ?>
<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Meal Preferences </label>

	<select name="cmeal<?php echo $child; ?>" class="form-control adhendle adltmealCl" style="-moz-appearance: none;" tabindex="" id="cmeal<?php echo $child; ?>" >

	  <option value="">---Meal Preferences---</option>

	  <?php foreach($MealDynamic as $msmeal){ ?>

	  <option value="<?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?>"><?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>
<?php } ?>

<?php if(count($SeatDynamic)>0){ ?>

<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Seat </label>

	<a style="line-height: 1.0; max-width:200px;" class="btn btn-danger border-radius-3 d-flex align-items-center justify-content-center min-height-50 font-weight-bold border-width-2 py-2 w-100 popup-ajax" href="#new-card-dialog" data-effect="mfp-zoom-out" data-id="" data-passenger="seatChild_<?php echo $child; ?>">View Seat</a> </div>

</div>

<?php } ?>

</div>
</div>


<input type="hidden" id="seatChildPrice<?php echo $child; ?>" name="seatChildPrice<?php echo $child; ?>" value="0" />
<input type="hidden" id="seatChildCode<?php echo $child; ?>" name="seatChildCode<?php echo $child; ?>" value="" />
																				
<!--- End SSR details --->	

<!--- Return SSR details --->
<?php
if($_SESSION['tripType']==2 && $_SESSION['isRoundTripInt'] == 0)
{

?> 
<div>Return - SSR Details (Optional) </div>
<div class="card-body">
<div class="row sliderpaxbox openpaxtabadult<?php echo $child; ?>">

<?php
if($ssrSeatFlight2!="" && count($Baggages2)>0 )
{
 ?>
<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Sector </label>

	<input class="form-control" type="text" name="csector2<?php echo $child; ?>" value="<?php echo $ssrSeatFlight2; ?>" readonly="yes" >

  </div>

</div>
<?php } ?>

<?php if($ssrSeatFlight2!="" && count($Baggages2)>0 )
{ ?>

<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Select Excess Baggage </label>

	<select name="cbaggage2<?php echo $child; ?>" class="form-control adhendle adultbaggageReturn" style="-moz-appearance: none;" tabindex="16" id="cbaggage2<?php echo $child; ?>"  >

	  <option value="">---Select Baggage---</option>

	  <?php foreach($Baggages2 as $msBag){ ?>

	  <option value="<?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?>"><?php echo $msBag['Weight'].'KG' . ' , ' . $msBag['Currency'].' '.$msBag['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>

<?php } ?>

<?php
if(count($MealDynamic2)>0)
{
 ?>
<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Meal Preferences </label>

	<select name="cmeal2<?php echo $child; ?>" class="form-control adhendle adltmealClReturn" style="-moz-appearance: none;" tabindex="" id="cmeal2<?php echo $child; ?>" >

	  <option value="">---Meal Preferences---</option>

	  <?php foreach($MealDynamic2 as $msmeal){ ?>

	  <option value="<?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?>"><?php echo $msmeal['Code']  . ' , ' . $msmeal['AirlineDescription'] . ' ,  ' . $msmeal['Currency'].' '.$msmeal['Price']; ?></option>

	  <?php } ?>

	</select>

  </div>

</div>
<?php } ?>

<?php if(count($SeatDynamic)>0){ ?>

<div class="col-sm-3 mb-4">

  <div class="js-form-message">

	<label class="form-label"> Seat </label>

	<a style="line-height: 1.0;" class="btn btn-outline-primary border-radius-3 d-flex align-items-center justify-content-center min-height-50 font-weight-bold border-width-2 py-2 w-100 popup-ajax2" href="#new-card-dialog" data-effect="mfp-zoom-out" data-id="" data-passenger="seatChild2_<?php echo $child; ?>">View Seat</a> </div>

</div>

<?php } ?>

</div>
</div>

<input type="hidden" id="seatChildPrice2<?php echo $child; ?>" name="seatChildPrice2<?php echo $child; ?>" value="0" />
<input type="hidden" id="seatChildCode2<?php echo $child; ?>" name="seatChildCode2<?php echo $child; ?>" value="" />

<?php } ?>
<!--- Return End SSR details --->



								
									
									
									
										<?php }
										$totalchildcount=$child;
										 ?>
										
										
										
										<?php
										for($infant=1; $infant<=$_SESSION['INF']; $infant++){
										?>
									 				 <div class="card-header">Infant <?php echo $infant; ?> (0 - 2 yrs)</div>
							   			 
						 <div class="card-body">
										
										
   
										<div class="row"  >
										
										<div class="col-sm-2 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Title
                                                </label>
												<select class="form-control validate1" name="titleInf<?php echo $infant; ?>" id="titleInf<?php echo $infant; ?>">
													<option value="">Select</option>
													<option value="Mr">Mr</option>
													<option value="Mrs">Mrs</option>
													<option value="Ms">Ms</option>
													<option value="Mstr">Mstr</option>
													<option value="Miss">Miss</option>
												</select>
                                            </div>
                                        </div>
										
                                        <div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    First Name
                                                </label>

                                                <input type="text" class="form-control" name="firstNameInf<?php echo $infant; ?>" id="firstNameInf<?php echo $infant; ?>" required
                                                data-msg="Please enter your first name."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope">
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Input -->
                                        <div class="col-sm-3 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Last name
                                                </label>

                                                <input type="text" class="form-control" name="lastNameInf<?php echo $infant; ?>" id="lastNameInf<?php echo $infant; ?>" required
                                                data-msg="Please enter your last name."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope">
                                            </div>
                                        </div>
										
										<div class="col-sm-3 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    DOB
                                                </label>
												<input class="form-control validate1 datepickerfieldinfant"  id="dobInf<?php echo $infant; ?>" name="dobInf<?php echo $infant; ?>" readonly="readonly">
											</div>
                                        </div>
                                        <!-- End Input -->
										<?php if($_SESSION['isdomestic']=='No'){ ?>
                                        <!-- Input -->
										<div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passwort Provided Country
                                                </label>
                                                <select class="form-control js-select selectpicker dropdown-select" id="passportNumberInf<?php echo $infant; ?>" name="passportNumberInf<?php echo $infant; ?>"  data-msg="Please select country." data-error-class="u-has-error" data-success-class="u-has-success"
                                                    data-live-search="true"
                                                    data-style="form-control font-size-16 border-width-2 border-gray font-weight-normal">
                                                    <option value="">Select country</option>
                                                    <option value="AF">Afghanistan</option>
                                                    <option value="AX">Åland Islands</option>
                                                    <option value="AL">Albania</option>
                                                    <option value="DZ">Algeria</option>
                                                    <option value="AS">American Samoa</option>
                                                    <option value="AD">Andorra</option>
                                                    <option value="AO">Angola</option>
                                                    <option value="AI">Anguilla</option>
                                                    <option value="AQ">Antarctica</option>
                                                    <option value="AG">Antigua and Barbuda</option>
                                                    <option value="AR">Argentina</option>
                                                    <option value="AM">Armenia</option>
                                                    <option value="AW">Aruba</option>
                                                    <option value="AU">Australia</option>
                                                    <option value="AT">Austria</option>
                                                    <option value="AZ">Azerbaijan</option>
                                                    <option value="BS">Bahamas</option>
                                                    <option value="BH">Bahrain</option>
                                                    <option value="BD">Bangladesh</option>
                                                    <option value="BB">Barbados</option>
                                                    <option value="BY">Belarus</option>
                                                    <option value="BE">Belgium</option>
                                                    <option value="BZ">Belize</option>
                                                    <option value="BJ">Benin</option>
                                                    <option value="BM">Bermuda</option>
                                                    <option value="BT">Bhutan</option>
                                                    <option value="BO">Bolivia, Plurinational State of</option>
                                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                    <option value="BA">Bosnia and Herzegovina</option>
                                                    <option value="BW">Botswana</option>
                                                    <option value="BV">Bouvet Island</option>
                                                    <option value="BR">Brazil</option>
                                                    <option value="IO">British Indian Ocean Territory</option>
                                                    <option value="BN">Brunei Darussalam</option>
                                                    <option value="BG">Bulgaria</option>
                                                    <option value="BF">Burkina Faso</option>
                                                    <option value="BI">Burundi</option>
                                                    <option value="KH">Cambodia</option>
                                                    <option value="CM">Cameroon</option>
                                                    <option value="CA">Canada</option>
                                                    <option value="CV">Cape Verde</option>
                                                    <option value="KY">Cayman Islands</option>
                                                    <option value="CF">Central African Republic</option>
                                                    <option value="TD">Chad</option>
                                                    <option value="CL">Chile</option>
                                                    <option value="CN">China</option>
                                                    <option value="CX">Christmas Island</option>
                                                    <option value="CC">Cocos (Keeling) Islands</option>
                                                    <option value="CO">Colombia</option>
                                                    <option value="KM">Comoros</option>
                                                    <option value="CG">Congo</option>
                                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                                    <option value="CK">Cook Islands</option>
                                                    <option value="CR">Costa Rica</option>
                                                    <option value="CI">Côte d'Ivoire</option>
                                                    <option value="HR">Croatia</option>
                                                    <option value="CU">Cuba</option>
                                                    <option value="CW">Curaçao</option>
                                                    <option value="CY">Cyprus</option>
                                                    <option value="CZ">Czech Republic</option>
                                                    <option value="DK">Denmark</option>
                                                    <option value="DJ">Djibouti</option>
                                                    <option value="DM">Dominica</option>
                                                    <option value="DO">Dominican Republic</option>
                                                    <option value="EC">Ecuador</option>
                                                    <option value="EG">Egypt</option>
                                                    <option value="SV">El Salvador</option>
                                                    <option value="GQ">Equatorial Guinea</option>
                                                    <option value="ER">Eritrea</option>
                                                    <option value="EE">Estonia</option>
                                                    <option value="ET">Ethiopia</option>
                                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                                    <option value="FO">Faroe Islands</option>
                                                    <option value="FJ">Fiji</option>
                                                    <option value="FI">Finland</option>
                                                    <option value="FR">France</option>
                                                    <option value="GF">French Guiana</option>

                                                    <option value="PF">French Polynesia</option>
                                                    <option value="TF">French Southern Territories</option>
                                                    <option value="GA">Gabon</option>
                                                    <option value="GM">Gambia</option>
                                                    <option value="GE">Georgia</option>
                                                    <option value="DE">Germany</option>
                                                    <option value="GH">Ghana</option>
                                                    <option value="GI">Gibraltar</option>
                                                    <option value="GR">Greece</option>
                                                    <option value="GL">Greenland</option>
                                                    <option value="GD">Grenada</option>
                                                    <option value="GP">Guadeloupe</option>
                                                    <option value="GU">Guam</option>
                                                    <option value="GT">Guatemala</option>
                                                    <option value="GG">Guernsey</option>
                                                    <option value="GN">Guinea</option>
                                                    <option value="GW">Guinea-Bissau</option>
                                                    <option value="GY">Guyana</option>
                                                    <option value="HT">Haiti</option>
                                                    <option value="HM">Heard Island and McDonald Islands</option>
                                                    <option value="VA">Holy See (Vatican City State)</option>
                                                    <option value="HN">Honduras</option>
                                                    <option value="HK">Hong Kong</option>
                                                    <option value="HU">Hungary</option>
                                                    <option value="IS">Iceland</option>
                                                    <option value="IN" selected="selected">India</option>
                                                    <option value="ID">Indonesia</option>
                                                    <option value="IR">Iran, Islamic Republic of</option>
                                                    <option value="IQ">Iraq</option>
                                                    <option value="IE">Ireland</option>
                                                    <option value="IM">Isle of Man</option>
                                                    <option value="IL">Israel</option>
                                                    <option value="IT">Italy</option>
                                                    <option value="JM">Jamaica</option>
                                                    <option value="JP">Japan</option>
                                                    <option value="JE">Jersey</option>
                                                    <option value="JO">Jordan</option>
                                                    <option value="KZ">Kazakhstan</option>
                                                    <option value="KE">Kenya</option>
                                                    <option value="KI">Kiribati</option>
                                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                                    <option value="KR">Korea, Republic of</option>
                                                    <option value="KW">Kuwait</option>
                                                    <option value="KG">Kyrgyzstan</option>
                                                    <option value="LA">Lao People's Democratic Republic</option>
                                                    <option value="LV">Latvia</option>
                                                    <option value="LB">Lebanon</option>
                                                    <option value="LS">Lesotho</option>
                                                    <option value="LR">Liberia</option>
                                                    <option value="LY">Libya</option>
                                                    <option value="LI">Liechtenstein</option>
                                                    <option value="LT">Lithuania</option>
                                                    <option value="LU">Luxembourg</option>
                                                    <option value="MO">Macao</option>
                                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                    <option value="MG">Madagascar</option>
                                                    <option value="MW">Malawi</option>
                                                    <option value="MY">Malaysia</option>
                                                    <option value="MV">Maldives</option>
                                                    <option value="ML">Mali</option>
                                                    <option value="MT">Malta</option>
                                                    <option value="MH">Marshall Islands</option>
                                                    <option value="MQ">Martinique</option>
                                                    <option value="MR">Mauritania</option>
                                                    <option value="MU">Mauritius</option>
                                                    <option value="YT">Mayotte</option>
                                                    <option value="MX">Mexico</option>
                                                    <option value="FM">Micronesia, Federated States of</option>
                                                    <option value="MD">Moldova, Republic of</option>
                                                    <option value="MC">Monaco</option>
                                                    <option value="MN">Mongolia</option>
                                                    <option value="ME">Montenegro</option>
                                                    <option value="MS">Montserrat</option>
                                                    <option value="MA">Morocco</option>
                                                    <option value="MZ">Mozambique</option>
                                                    <option value="MM">Myanmar</option>
                                                    <option value="NA">Namibia</option>
                                                    <option value="NR">Nauru</option>
                                                    <option value="NP">Nepal</option>
                                                    <option value="NL">Netherlands</option>
                                                    <option value="NC">New Caledonia</option>
                                                    <option value="NZ">New Zealand</option>
                                                    <option value="NI">Nicaragua</option>
                                                    <option value="NE">Niger</option>
                                                    <option value="NG">Nigeria</option>
                                                    <option value="NU">Niue</option>
                                                    <option value="NF">Norfolk Island</option>
                                                    <option value="MP">Northern Mariana Islands</option>
                                                    <option value="NO">Norway</option>
                                                    <option value="OM">Oman</option>
                                                    <option value="PK">Pakistan</option>
                                                    <option value="PW">Palau</option>
                                                    <option value="PS">Palestinian Territory, Occupied</option>
                                                    <option value="PA">Panama</option>
                                                    <option value="PG">Papua New Guinea</option>
                                                    <option value="PY">Paraguay</option>
                                                    <option value="PE">Peru</option>
                                                    <option value="PH">Philippines</option>
                                                    <option value="PN">Pitcairn</option>
                                                    <option value="PL">Poland</option>
                                                    <option value="PT">Portugal</option>
                                                    <option value="PR">Puerto Rico</option>
                                                    <option value="QA">Qatar</option>
                                                    <option value="RE">Réunion</option>
                                                    <option value="RO">Romania</option>
                                                    <option value="RU">Russian Federation</option>
                                                    <option value="RW">Rwanda</option>
                                                    <option value="BL">Saint Barthélemy</option>
                                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                    <option value="KN">Saint Kitts and Nevis</option>
                                                    <option value="LC">Saint Lucia</option>
                                                    <option value="MF">Saint Martin (French part)</option>
                                                    <option value="PM">Saint Pierre and Miquelon</option>
                                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                                    <option value="WS">Samoa</option>
                                                    <option value="SM">San Marino</option>
                                                    <option value="ST">Sao Tome and Principe</option>
                                                    <option value="SA">Saudi Arabia</option>
                                                    <option value="SN">Senegal</option>
                                                    <option value="RS">Serbia</option>
                                                    <option value="SC">Seychelles</option>
                                                    <option value="SL">Sierra Leone</option>
                                                    <option value="SG">Singapore</option>
                                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                                    <option value="SK">Slovakia</option>
                                                    <option value="SI">Slovenia</option>
                                                    <option value="SB">Solomon Islands</option>
                                                    <option value="SO">Somalia</option>
                                                    <option value="ZA">South Africa</option>
                                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                    <option value="SS">South Sudan</option>
                                                    <option value="ES">Spain</option>
                                                    <option value="LK">Sri Lanka</option>
                                                    <option value="SD">Sudan</option>
                                                    <option value="SR">Suriname</option>
                                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                                    <option value="SZ">Swaziland</option>
                                                    <option value="SE">Sweden</option>
                                                    <option value="CH">Switzerland</option>
                                                    <option value="SY">Syrian Arab Republic</option>
                                                    <option value="TW">Taiwan, Province of China</option>
                                                    <option value="TJ">Tajikistan</option>
                                                    <option value="TZ">Tanzania, United Republic of</option>
                                                    <option value="TH">Thailand</option>
                                                    <option value="TL">Timor-Leste</option>
                                                    <option value="TG">Togo</option>
                                                    <option value="TK">Tokelau</option>
                                                    <option value="TO">Tonga</option>
                                                    <option value="TT">Trinidad and Tobago</option>
                                                    <option value="TN">Tunisia</option>
                                                    <option value="TR">Turkey</option>
                                                    <option value="TM">Turkmenistan</option>
                                                    <option value="TC">Turks and Caicos Islands</option>
                                                    <option value="TV">Tuvalu</option>
                                                    <option value="UG">Uganda</option>
                                                    <option value="UA">Ukraine</option>
                                                    <option value="AE">United Arab Emirates</option>


                                                    <option value="GB">United Kingdom</option>
                                                    <option value="US">United States</option>
                                                    <option value="UM">United States Minor Outlying Islands</option>
                                                    <option value="UY">Uruguay</option>
                                                    <option value="UZ">Uzbekistan</option>
                                                    <option value="VU">Vanuatu</option>
                                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                                    <option value="VN">Viet Nam</option>
                                                    <option value="VG">Virgin Islands, British</option>
                                                    <option value="VI">Virgin Islands, U.S.</option>
                                                    <option value="WF">Wallis and Futuna</option>
                                                    <option value="EH">Western Sahara</option>
                                                    <option value="YE">Yemen</option>
                                                    <option value="ZM">Zambia</option>
                                                    <option value="ZW">Zimbabwe</option>
                                                </select>
                                            </div>
                                        </div>
										
                                        <div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Number
                                                </label>
                                                <input type="text" class="form-control" name="passportNumberInf<?php echo $infant; ?>" id="passportNumberInf<?php echo $infant;?>" placeholder="" aria-label="" 
                                                data-msg="Please enter passport number"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope" required>
                                            </div>
                                        </div>
                                        <!-- End Input -->
										<div class="col-sm-4 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Passport Expiry
                                                </label>
                                                <input type="text" class="form-control datepickerfieldexpiry" name="passportExpiryInf<?php echo $infant; ?>" id="passportExpiryInf<?php echo $infant;?>" placeholder="" aria-label="" 
                                                data-msg="Please enter expiry Date"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope" required>
                                            </div>
                                        </div>
										
										<?php } ?>
										
										</div>
										
										</div>
										    <input name="totalinfant" type="hidden" value="<?php echo $infant; ?>">
										<?php }  
										$totalinfantcount=$infant;
										 ?>

 


</div>

<div class="card cardresult" style="width:100%; margin-top:20px;" id="contactdetailid">








 <div class="card-header">Contact Details</div>
										 
						 <div class="card-body">
						 
						 	 <div class="row"> <!-- Input -->
                                         
										
										<div class="col-sm-6 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Email
                                                </label>

                                                <input type="email" class="form-control validate1" name="email" placeholder="" aria-label="" required
                                                data-msg="Please enter a valid email address."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope" value="<?php echo $LoginUserDetails['email']; ?>">
                                            </div>
                                        </div>
                                        <!-- End Input -->

                                        <!-- Input -->
                                        <div class="col-sm-6 mb-4">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Phone
                                                </label>

                                                <input type="number" class="form-control validate1" name="phone" placeholder="" aria-label="" required
                                                data-msg="Please enter a valid phone number."
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success" autocomplete="nope"  value="<?php echo $LoginUserDetails['phone']; ?>">
                                            </div>
                                        </div>
                                        <!-- End Input -->
										
										<!-- Input -->
                                         
                                        <!-- End Input -->
										</div>
                                        
										<div class="row" style="position:relative;"> 
								<label style="padding-left: 37px; width: 100%; margin-bottom: 30px;"><input name="gst" type="checkbox" value="1" onClick="ifgst();" class="checkbox_check" style="width: 16px; height: 16px; position: absolute; left: 14px; top: 3px;"> I have a GST number (Optional)</label>
								  <div class="col-sm-4 mb-4 showgst">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Company Name
                                                </label>

                                                <input type="text" class="form-control" name="companyName" placeholder=""  autocomplete="nope">
                                            </div>
                                        </div>
										
										<div class="col-sm-4 mb-4 showgst">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    GST No
                                                </label>

                                                <input type="test" class="form-control" name="gstNo" placeholder=""  autocomplete="nope">
                                            </div>
                                        </div>
										
										<div class="col-sm-4 mb-4 showgst">
                                            <div class="js-form-message">
                                                <label class="form-label">
                                                    Email
                                                </label>

                                                <input type="test" class="form-control" name="gstEmail" placeholder=""  autocomplete="nope">
                                            </div>
                                        </div>
										
										
										
										</div>
						 
						 
						 </div>
				<div class="card-footer text-muted hidefooter">
   
    
   <button type="button" class="btn btn-danger btn-sm" style="float:right;" onClick="checkInputs();">Proceed To Pay</button>
   
   
  </div>		
  
  
  
  <div class="card-footer text-muted" id="showfooterpay" style="display:none;">
   
   <button type="button" class="btn btn-danger btn-sm float-left" onClick="$('#steponeflightdetails').hide();$('#steptwopassengerdetails').show();$('.flightreviewbox').removeClass('active');$('#strp2').addClass('active');$('.hidefooter').show();$('#showfooterpay').hide();"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back</button>
 
 <?php
 if($isLCCchk==0)
 {
  ?>
 <div style="text-align:center;">
 								<input name="onholdFlight" type="checkbox" value="1"  > On Hold Flight
</div>	
<?php } ?>							
 
 
   <button type="button" class="btn btn-danger btn-sm" style="float:right;" onClick="checkInputs();$('#steponeflightdetails').hide();$('#steptwopassengerdetails').hide();$('#stepfourpayments').show();$('.flightreviewbox').removeClass('active');$('#strp4').addClass('active');$('.hidefooter').show();$('#showfooterpay').hide();">Proceed To Pay</button>
   
   
   
   
  </div> 
</div>

</div>



<?php $str_arr = explode (",", $res['agfare']);   
	$basefare = explode ("=", $str_arr[2]); 
	  ?>
<div class="col-12" style="position:relative; margin-top:20px; display:none;"  id="stepfourpayments">
<h2>Payments</h2>

<div class="card cardresult" style="width:100%;">
<div class="card-header">
Pay By Wallet
</div>

<div class="row">

<div class="col-4"> 

<div style="padding: 40px 0px; text-align: center;  font-size: 30px; border-bottom-left-radius: 5px; <?php if($totalwalletBalance>=$basefare[1]){?>border-right: 2px solid #41e0d2; background-color: #e4f8ff; color:#02C4B0;<?php } else { ?>border-right: 2px solid #e04159; background-color: #ffe4e4;color:#c4021e;<?php } ?>">
<div style="font-weight:600; ">₹<?php echo round($totalwalletBalance); ?></div>
<div style="font-size:14px; color:#000000; "><strong>Your Wallet Balance</strong></div>
</div> 

</div>


<div class="col-8">
<div class="card-body">
<?php  //if($totalwalletBalance>=($basefare[1]+$totalfareret)){?>

<?php 


if($totalwalletBalanceParent>($basefare[1]+$totalfareret)){

if($ifoffile==0){ 

 $totalwalletBalanceParent;

} ?>


<div style="padding-top:10px; padding-bottom:10px; font-size:14px;"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp; By placing this order, you agree to our Terms Of Use and Privacy Policy</div>

<input name="termsofuse" type="checkbox" value="1" checked disabled="disabled"> I accept <a href="<?php echo $fullurl; ?>terms-conditions" target="_blank" style="text-decoration:underline;">terms & conditions</a>
 
<input name="flightbooking" id="flightbooking" type="hidden" value="0">
<input name="bookingMethod" id="bookingMethod" type="hidden" value="0">


<div style=" font-size:14px; margin-bottom:10px; margin-top:15px;">
<?php if($totalwalletBalance>=($basefare[1]+$totalfareret)){ ?>
<button type="button" id="paynowbtnmain" class="btn btn-danger" onClick="payandbooknow();" >Pay Now ₹<?php  echo ($basefare[1]+$totalfareret); ?></button>

<a href="<?php echo $fullurl; ?>online-recharge?b=1&bamount=<?php echo ($basefare[1]+$totalfareret); ?>"  target="_blank"><button type="button" class="btn btn-danger payonlinebtnmain">Pay Online ₹<?php  echo ($basefare[1]+$totalfareret); ?></button></a>

<?php }else{ ?>

<a href="<?php echo $fullurl; ?>online-recharge?b=1&bamount=<?php echo ($basefare[1]+$totalfareret); ?>" target="_blank"><button type="button" class="btn btn-danger">Pay Online ₹<?php  echo ($basefare[1]+$totalfareret); ?></button></a>

<?php } ?>

</div>


 


<?php }  else { ?>
<div style="padding-top:10px; padding-bottom:10px; font-size:14px; background-color:#FF0000; color:#FFFFFF; font-weight:600; padding:20px; text-align:center;">We have found some technical issue. Please contact administrator!</div>
<?php } ?> 
</div>


</div>


</div>



</div>

</div>

 </div>
 
  </div>

<div class="col-3">
<div class="card">
<div class="card-header">
Fare Summary
</div>
<div class="card-body">

 <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-size:14px; color:#000000;">
  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Base fare</td>
    <td width="50%" align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;">₹<?php  
	
	$str_arr = explode (",", $res['agfare']);  
	$basefare = explode ("=", $str_arr[0]);
	echo $BaseFare= ($basefare[1]+$basefareret); 
	//echo $BaseFare;
	  ?></td>
  </tr>
  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Taxes and fees</td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;">₹<?php 
	$basefare = explode ("=", $str_arr[1]);
	echo $Tax= ($res['totalTax']+$resret['totalTax']);
	
	// echo $Tax;
	
	  ?></td>
  </tr>

  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Baggage Fee :</td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;"><span class="font-weight-medium" id="baggval"></span></td>
  </tr>
  
  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Meal Fee :</td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;"><span class="font-weight-medium" id="mealval"></span></td>
  </tr>  
  
  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Seat : </td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;"><span class="font-weight-medium seatvalTotal" style="display: inline-block;
    margin-right: 68%;" > </span><span class="font-weight-medium seatvalamtTotal" ></span></td>
  </tr>  
  
  
  
 <?php
 if($_SESSION['tripType']==2 && $_SESSION['isRoundTripInt'] == 0)
 {
 ?>  
 
   <tr>
    <td colspan="4" align="center" style="padding:10px 0px; border-bottom:1px solid #ddd;">Return Flight</td>
  </tr> 

  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Baggage Fee :</td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;"><span class="font-weight-medium" id="baggval2"></span></td>
  </tr>
  
  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Meal Fee :</td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;"><span class="font-weight-medium" id="mealval2"></span></td>
  </tr>  
  
  <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Seat : </td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;"><span class="font-weight-medium seatval2Total" style="display: inline-block;
    margin-right: 68%;" > </span><span class="font-weight-medium seatvalamt2Total" ></span></td>
  </tr>   
 
 
<?php } ?>


<tr style="display:none;" id="discountAmtDiv">
	<td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Discount Amount</td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;">- ₹<span id="discountAmt"></span></td>
</tr>
 

    <tr>
    <td colspan="2" align="left" style="padding:10px 0px; border-bottom:1px solid #ddd;">Amount to Pay</td>
    <td align="right" style="padding:10px 0px; border-bottom:1px solid #ddd; font-size:14px;">₹<span id="totalpayAmt">
	<?php 
	$basefare = explode ("=", $str_arr[2]);
	echo ($basefare[1]+$totalfareret);
	?></span></td>
  </tr>
  

  
  
</table>
<?php if($totalwalletBalance>=($basefare[1]+$totalfareret)){} else {?>
<div style="padding-top:10px; padding-bottom:10px; font-size:14px; color:#CC0000;"><i class="fa fa-info-circle" aria-hidden="true"></i>&nbsp; You don't have sufficient balance.</div>
<?php } ?>
</div>

</div>




<?php
$totalFare=round($basefare[1]+$totalfareret);
?>


<div class="card d-none">
	<div class="card-header hd">
		Select a Discount Coupon 
	</div>
	<div class="card-body">
        <style>
			.appliedbtn{
				display:none;
			}
		</style>
        <h5 class="mb-0">
			<div id="manualcouponapply" class="collapse show" aria-labelledby="basicsHeadingFour">
<table width="100%" border="0" cellpadding="0">
	<tr>
		<td><div style="position:relative;"><input type="text" name="couponCodeApply" id="couponCodeApply" placeholder="" style="border: 1px solid #cccaca; padding: 2px; border-radius: 3px;width:95%;" style="border-radius: 4px; border: 1px solid #d5d5d5; background-color: #fff; width: 100%; padding: 10px; color: #000;"><i class="fa fa-times-circle-o appliedbtn" aria-hidden="true" style="position: absolute; right: 10px ; top: 8px ; color: #6a6a6a; font-size: 16px; cursor:pointer;" onClick="removecouponcode();removeapplycop();"></i></div></td>
		<td><button type="button" class="btn btn-danger btn-sm float-left" onClick="applycouponcode();" ><span class="applybtn">Apply</span><span class="appliedbtn" style="color:#00CC33;">Applied&nbsp;&nbsp;</span></button></td>
	</tr>
</table>
<script>
	function applycouponcode(){
		var couponcode = encodeURI($('#couponCodeApply').val());
		//var displayprice = Number($('#finalclientcost_new').val()); 
		if(couponcode!=''){
			$('#discountAmtDiv').show();
			//$('.discountAmtDiv').removeClass('hd'); 
			$('#couponCodeApply').val($('.dottedlinebox').text());
			$('#couponapplied').load('actionpage.php?action=applycopmanual&couponcode='+couponcode+'&bid=<?php echo encode($res["id"]); ?>&displayprice=<?php echo ($totalFare); ?>');
		}
	}
	
	function removecouponcode(){
		$('#discountAmtDiv').hide();
		//$('#discountAmtDiv').addClass('hd'); 
		$('#couponCodeApply').val('');
		$('#discountAmt').text('');
		$('.appliedbtn').hide();
		$('.applybtn').show(); 
		$('#couponCodeApply').val(''); 
	}
</script>
			</div>
		</h5>
	<div>
	</div>

<script>
	function applycop(){
		var couponid = $('input[name="couponid"]:checked').val();
		//var displayprice = Number($('#finalclientcost_new').val()); 
		if(couponid>0){
			$('#couponapplied').load('actionpage.php?action=applycop&id='+couponid+'&bid=<?php echo encode($res["id"]); ?>&displayprice=<?php echo $totalFare; ?>');  
			$('#discountAmtDiv').show();
			//$('.discountapplydiv').removeClass('hd'); 
			$('#couponCodeApply').val($('.dottedlinebox').text());
			$('.appliedbtn').show();
			$('.applybtn').hide(); 
		}
	}
									
	function removeapplycop(){
		var couponid = $('input[name="couponid"]:checked').val();
		//var displayprice = Number($('#finalclientcost_new').val()); 
		$('#couponapplied').load('actionpage.php?action=removeapplycop&id='+couponid+'&bid=<?php echo encode($res["id"]); ?>&displayprice=<?php echo $totalFare; ?>'); 
		$("input[type=radio][name=couponid]").prop('checked', false);
		$('#discountAmtDiv').hide();
		//$('.discountapplydiv').addClass('hd');
		$('#couponCodeApply').val('');
		$('.appliedbtn').hide();
		$('.applybtn').show(); 
		$('#couponCodeApply').val('');
		$('#discountAmt').text('');
	}
</script>

	 
								</div>
							</div>





 </div>
<?php
$arq=($totalFare-$wallet30PercBalance);
$arq=$arq+202565517+202565517;
?>
 <input name="flightone" type="hidden" value="<?php echo encode($_REQUEST['i']); ?>">
 <input name="flighttwo" type="hidden" value="<?php echo encode($_REQUEST['r']); ?>">
 <input type="hidden" name="arq" id="arq" value="<?php echo $arq; ?>">
 
 <input name="baseFareInn"  id="baseFareInn" type="hidden" value="<?php echo $BaseFare; ?>" >
 <input name="TaxAndFeeInn" id="TaxAndFeeInn" type="hidden" value="<?php echo $Tax; ?>" >
 
 <input name="BaggageFeeInn" id="BaggageFeeInn" type="hidden" value="0" >
 <input name="MealFeeInn" id="MealFeeInn" type="hidden" value="0" >
 <input name="SeatFeeInn" id="SeatFeeInn" type="hidden" value="0" >
 
 <input name="BaggageFeeInn2" id="BaggageFeeInn2" type="hidden" value="0" >
 <input name="MealFeeInn2" id="MealFeeInn2" type="hidden" value="0" >
 <input name="SeatFeeInn2" id="SeatFeeInn2" type="hidden" value="0" > 

 <input name="SeatPriceInn" id="SeatPriceInn" type="hidden" value="0" >
 <input name="SeatPriceInn2" id="SeatPriceInn2" type="hidden" value="0" > 
 
 <input name="SeatNoInn" id="SeatNoInn" type="hidden" value="" >
 <input name="SeatNoInn2" id="SeatNoInn2" type="hidden" value="" >  
 

 <input name="totalAmountToPay" id="totalAmountToPay" type="hidden" value="<?php echo ($basefare[1]+$totalfareret); ?>" >

 
 </form>
</div>




<div class="row" id="bookingloading" style="display:none;">
<div class="col-12" style=" text-align:center;">

<div class="card">
<div class="card-body">

<div style="text-align:center; font-size:30px; padding:80px 0px;">
<div style="text-align:center; "><img src="images/loadinggif.gif" width="40"></div>
Wait Please Processing...</div>

</div>
</div>
</div>
 </div> 
 
 
 </div>
 
 
<script>
 
  function checkInputs(){
  var e='';
  var flag = 0;
  $('.validate1').each(function() {
       if($(this).val() == ''){
	    $('.form-control').removeClass('redborderfiled');
	   $(this).addClass('redborderfiled');
	   $(this).focus();
       e=1;
	   return false;
       }
   });
    
   if(e==1){
   alert('Please fill mandatory fields');
   }
   
   if(e!=1){
   $('.form-control').removeClass('redborderfiled');
  $('#steponeflightdetails').show();$('#steptwopassengerdetails').show();$('.flightreviewbox').removeClass('active');$('#strp3').addClass('active');$(window).scrollTop(0);$('.hidefooter').hide();$('#showfooterpay').show();$('#stepfourpayments').hide();
  
  $('#steponeflightdetails').hide();$('#steptwopassengerdetails').show();$('#stepfourpayments').show();$('#contactdetailid').hide();$('.flightreviewbox').removeClass('active');$('#strp4').addClass('active');$('.hidefooter').show();$('#showfooterpay').hide();
  }
  
  }
 
 
 function showfarerule(id){
 var farerulebtn = $('.farerulebtn').text();
 if(farerulebtn=='Show Fare Rules'){
 $('.farerulebtn').html('Hide Fare Rules');
 $('#showfarerule').slideDown();
 } else {
 $('.farerulebtn').html('Show Fare Rules');
 $('#showfarerule').slideUp();
 }
 
  if(farerulebtn=='Show Fare Rules'){
 $('#showfarerule').html('Loading...');
 $('#showfarerule').load('showflightfarerule.php?id='+id);
 }
 }
 
 
  function showfarerule2(id){
 var farerulebtn = $('.farerulebtn2').text();
 if(farerulebtn=='Show Fare Rules'){
 $('.farerulebtn2').html('Hide Fare Rules');
 $('#showfarerule2').slideDown();
 } else {
 $('.farerulebtn2').html('Show Fare Rules');
 $('#showfarerule2').slideUp();
 }
 
  if(farerulebtn=='Show Fare Rules'){
 $('#showfarerule2').html('Loading...');
 $('#showfarerule2').load('showflightfarerule.php?id='+id);
 }
 }
 
 
   $( function() {
    $( ".datepickerfield" ).datepicker(
	{
changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
			dateFormat: 'dd-mm-yy',
			maxDate: new Date()
	
	}
	
	);
  } );
  

 $( function() {
 
 
    $( ".datepickerfieldinfant" ).datepicker(
	{
			changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
			dateFormat: 'dd-mm-yy',
			maxDate:new Date('<?php echo $journeyDate1; ?>'),
			minDate: new Date('<?php echo $infantDateValidate; ?>')
				
	
	}
	
	);
  } );
  
  
  $( function() {
 
 
    $( ".datepickerfieldchild" ).datepicker(
	{
			changeMonth: true,
            changeYear: true,
            yearRange: '-100:+0',
			dateFormat: 'dd-mm-yy',
			maxDate:new Date('<?php echo $journeyDate1; ?>'),
			minDate: new Date('<?php echo $childDateValidate; ?>')
				
	
	}
	
	);
  } ); 
  
  
  $( function() {
    $( ".datepickerfieldexpiry" ).datepicker(
	{
			changeMonth: true,
            changeYear: true,
            yearRange: '-100:+50',
			dateFormat: 'yy-mm-dd',
			minDate: 0
	
	}
	
	);
  } );
 </script>
<?php include "footer.php"; ?>
 
 <script>
 $('#showfarerule').load('showflightfarerule.php?id=<?php echo encode($res['id']); ?>&checkingflightfare=1');
 <?php if($resret['id']!=''){ ?>
 $('#showfarerule2').load('showflightfarerule.php?id=<?php echo encode($resret['id']); ?>&checkingflightfare=1');
 <?php } ?>
 
function payandbooknow(){
$('#flightbooking').val('1');
$('#flightbookingsubmit').submit();
$('#bookingloading').show();
$('#bookingdatainfo').hide();
$('.flightreview').hide();
}
</script>

<iframe id="bookingframe" name="bookingframe" style="display:none;"></iframe>

<link rel="stylesheet" href="css/msastyles.php?c=3F449D&c2=26295e&h=3F449D&f=444444">

<script src="https://davinaxtravels.in/website/assets/js/magnific.js"></script> 

<link rel="stylesheet" type="text/css" href="css/jquery.seat-charts.css" />

<script type="text/javascript" src="css/jquery.seat-charts.min.js"></script>

<script type="text/javascript">

$(document).ready(function ($) {
    $('.test-popup-link').magnificPopup({
    type: 'image',
    gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
          }
    // other options
  });
  });



  

$('.popup-ajax').magnificPopup({ 

    removalDelay: 500,

    closeBtnInside: true,

    callbacks: {

        beforeOpen: function(){

        $("#view-seats").html('<div style="font-size:20px;text-align:center;line-height:120px;width:100%;">Wait Please...</div>');

        var myvalue = this.st.el.attr('data-id');
		console.log('myvalue ' +myvalue);
		
		   var dataPassenger = this.st.el.attr('data-passenger');
		   console.log('dataPassenger ' +dataPassenger);

        var res = myvalue.split("|"); //alert(res);

        var n = res[0];       

        var trid = 'seatlayout'+res[0];

        var sdd = "<?php echo base64_encode($sid);?>";

        tripid = res[1];trvnm = res[2];bloc = res[3];btime = res[4];dloc = res[5];dtime = res[6];fare = res[7];busdesc = res[8];dur = res[9];

        $.ajax({url: "ajax_seat_layout.php?tnm="+trvnm+"&bl="+bloc+"&bt="+btime+"&dl="+dloc+"&dt="+dtime+"&f="+fare+"&bdesc="+busdesc+"&dur1="+dur+"&tid="+tripid+"&tripid="+tripid+"&j="+n+"&dataPassenger="+dataPassenger, success: function(result){

            $("#view-seats").html(result);

            }

          });

            this.st.mainClass = this.st.el.attr('data-effect');

        }

    },

    midClick: true



   });
   
   

 $(".popclose").click(function () {

      $(".mfp-close").click();

 });     
   
 /*********** Return Fligh *********/
 
 $('.popup-ajax2').magnificPopup({ 

    removalDelay: 500,

    closeBtnInside: true,

    callbacks: {

        beforeOpen: function(){

        $("#view-seats").html('<div style="font-size:20px;text-align:center;line-height:120px;width:100%;">Please Wait...<i class="fa fa-spinner user-profile-statictics-icon"></i></div>');

       var myvalue = this.st.el.attr('data-id');
	   var dataPassenger = this.st.el.attr('data-passenger');
	   console.log('dataPassenger ' +dataPassenger);

        var res = myvalue.split("|"); //alert(res);

        var n = res[0];       

        var trid = 'seatlayout'+res[0];

        var sdd = "<?php echo base64_encode($sid);?>";

        tripid = res[1];trvnm = res[2];bloc = res[3];btime = res[4];dloc = res[5];dtime = res[6];fare = res[7];busdesc = res[8];dur = res[9];

        $.ajax({url: "ajax_seat_layout2.php?tnm="+trvnm+"&bl="+bloc+"&bt="+btime+"&dl="+dloc+"&dt="+dtime+"&f="+fare+"&bdesc="+busdesc+"&dur1="+dur+"&tid="+tripid+"&tripid="+tripid+"&j="+n+"&dataPassenger="+dataPassenger, success: function(result){

            $("#view-seats").html(result);

            }

          });

            this.st.mainClass = this.st.el.attr('data-effect');

        }

    },

    midClick: true



   });
   
     



 

</script>


<script type="text/javascript"> 
//var hide = 0;



var sum=0;
var arr_seat=new Array();
function change_icon2(ac,n,am,dataPassenger){	

	
	var totalAdult='<?php echo $_SESSION['ADT']; ?>';
	var totalChild='<?php echo $_SESSION['CHD']; ?>';
	//alert(dataPassenger);
	
	var dataPassArr = dataPassenger.split("_"); 
	var passenterType=dataPassArr[0];
	var passenterValue=dataPassArr[1];


	var seat = 'sl'+ac+'seat'+am+'amt'+n;   //  alert(seat);  
	$('#SeatPriceInn2').val(n);
	$('#SeatNoInn2').val(ac);
	
	// set seat adult wise 
	 if(passenterType=='seatAdult2' && passenterValue!='')
	 {
		 for(i=1;i<=totalAdult;i++)
		 {
		 	if(passenterValue==i)
			{
				$('#seatAdultPrice2'+i).val(n);
				$('#seatAdultCode2'+i).val(ac);
			}
					 
		 } 
		 
	}	
	
	// set seat adult wise 
	console.log('dataPassenger '+dataPassenger+' Total Child aaa passenterType ' +passenterType);
	
	 if(passenterType=='seatChild2' && passenterValue!='')
	 {
		 for(i=1;i<=totalChild;i++)
		 {
		 	if(passenterValue==i)
			{
				$('#seatChildPrice2'+i).val(n);
				$('#seatChildCode2'+i).val(ac);
			}
					 
		 } 
		 
	}		
	
	
	calculate_fare2(ac,n,am);
	allCalCulatedPrice();
}


function change_icon(ac,n,am,dataPassenger){

//alert(dataPassenger);

	var totalAdult='<?php echo $_SESSION['ADT']; ?>';
	var totalChild='<?php echo $_SESSION['CHD']; ?>';
	
	var dataPassArr = dataPassenger.split("_"); 
	var passenterType=dataPassArr[0];
	var passenterValue=dataPassArr[1];
		
	var seat = 'sl'+ac+'seat'+am+'amt'+n;   //  alert(seat); 
	//alert(ac+' '+n+' '+am); 
	$('#SeatPriceInn').val(n);
	$('#SeatNoInn2').val(ac);
	
	//console.log('dataPassenger '+dataPassenger+' Total Adult '+'<?php echo $_SESSION['ADT']; ?>');
	
	// set seat adult wise 
	 if(passenterType=='seatAdult' && passenterValue!='')
	 {
		 for(i=1;i<=totalAdult;i++)
		 {
		 	if(passenterValue==i)
			{
				$('#seatAdultPrice'+i).val(n);
				$('#seatAdultCode'+i).val(ac);
			}
					 
		 } 
		 
	}
	
	
	// set seat adult wise 
	 if(passenterType=='seatChild' && passenterValue!='')
	 {
		 for(i=1;i<=totalChild;i++)
		 {
		 	if(passenterValue==i)
			{
				$('#seatChildPrice'+i).val(n);
				$('#seatChildCode'+i).val(ac);
			}
					 
		 } 
		 
	}	
	
	
	
	calculate_fare(ac,n,am);
	allCalCulatedPrice();
}



function calculate_fare(ac,n,am){ 
	
	var st= 'st'+ac;
	var hid_st= 'hid_st'+am;
	var amt= 'amt'+n;
	var hid_amt= 'hid_amt'+n; 
	var splits = st.split("st"); 
	var splitss = splits[1].split(","); 
	var hid_amt = hid_amt.split("hid_amt"); 

	var hid_amts = hid_amt[1].split(",");  

	$(".seatval").html(splitss);
	$(".seatvalamt").html(hid_amts);
	$('#seatval').val(splitss);
	$('#seatvalamt').val(hid_amts);
	
	//$('#SeatPriceInn').val(parseInt($('#SeatPriceInn').val())+parseInt(hid_amts));
	//$(".seatvalamt").html($('#SeatPriceInn').val());
	//$(".seatval").html($('#SeatNoInn').val()+splitss);
	

 	var totalAdult='<?php echo $_SESSION['ADT']; ?>';
	var totalChild='<?php echo $_SESSION['CHD']; ?>';	
	
	var totalSeatAdultPrice=0;
	var totalSeatAdultCode='';
	for(j=1;j<=totalAdult;j++)
	{
		
		var seatAdultPriceN= document.getElementById('seatAdultPrice'+j).value;
		totalSeatAdultPrice=totalSeatAdultPrice+parseInt(seatAdultPriceN);
		
		totalSeatAdultCode=totalSeatAdultCode+document.getElementById('seatAdultCode'+j).value+",";
		
	}
	
	if(totalChild>0)
	{

		for(j=1;j<=totalChild;j++)
		{
			
			var seatAdultPriceN= document.getElementById('seatChildPrice'+j).value;
			totalSeatAdultPrice=totalSeatAdultPrice+parseInt(seatAdultPriceN);
			totalSeatAdultCode=totalSeatAdultCode+document.getElementById('seatChildCode'+j).value+",";	
		}		
		
	}
	
	
	console.log("Seat Adult Price "+totalSeatAdultPrice);
	$('#SeatPriceInn').val(parseInt(totalSeatAdultPrice));
	//alert(totalSeatAdultPrice);
	$('.seatvalamtTotal').html(totalSeatAdultPrice);	
    $('.seatvalTotal').html(totalSeatAdultCode);


}



function calculate_fare2(ac,n,am){ 

	var st= 'st'+ac;
	var hid_st= 'hid_st'+am;
	var amt= 'amt'+n;
	var hid_amt= 'hid_amt'+n; 
	var splits = st.split("st"); 
	var splitss = splits[1].split(","); 
	var hid_amt = hid_amt.split("hid_amt"); 

	var hid_amts = hid_amt[1].split(",");  

	//$(".seatval2").html(splitss);
	$(".seatvalamt2").html(hid_amts);
	$('#seatval2').val(splitss);
	$('#seatvalamt2').val(hid_amts);
	
	$('#SeatPriceInn2').val(parseInt($('#SeatPriceInn2').val())+parseInt(hid_amts));

 	var totalAdult='<?php echo $_SESSION['ADT']; ?>';
	var totalChild='<?php echo $_SESSION['CHD']; ?>';	
	
	var totalSeatAdultPrice=0;
	var totalSeatAdultCode='';
	for(j=1;j<=totalAdult;j++)
	{
		
		var seatAdultPriceN= document.getElementById('seatAdultPrice2'+j).value;
		totalSeatAdultPrice=totalSeatAdultPrice+parseInt(seatAdultPriceN);
		
		totalSeatAdultCode=totalSeatAdultCode+document.getElementById('seatAdultCode2'+j).value+",";
		
	}
	
	console.log("Return "+totalSeatAdultPrice);
	
	if(totalChild>0)
	{

		for(j=1;j<=totalChild;j++)
		{
			
			var seatAdultPriceN= document.getElementById('seatChildPrice2'+j).value;
			totalSeatAdultPrice=totalSeatAdultPrice+parseInt(seatAdultPriceN);
			totalSeatAdultCode=totalSeatAdultCode+document.getElementById('seatChildCode2'+j).value+",";	
		}	
			
		//console.log("Seat Child return Price "+totalSeatAdultPrice);
		//console.log("Seat Child return code "+totalSeatAdultCode);
	}
	
	
	
	//alert(totalSeatAdultPrice);
	$('#SeatPriceInn2').val(parseInt(totalSeatAdultPrice));
	$('.seatvalamt2Total').html(totalSeatAdultPrice);	
    $('.seatval2Total').html(totalSeatAdultCode);
	


}


function allCalCulatedPrice()
{
	var baseFarePrice=parseInt($('#baseFareInn').val());
	var TaxAndFee=parseInt($('#TaxAndFeeInn').val());
	
	var BaggageFeeInn=parseInt($('#BaggageFeeInn').val());
	var MealFeeInn=parseInt($('#MealFeeInn').val());
	var SeatPriceInn=parseInt($('#SeatPriceInn').val());
	
	var BaggageFeeInn2=parseInt($('#BaggageFeeInn2').val());
	var MealFeeInn2=parseInt($('#MealFeeInn2').val());
	var SeatPriceInn2=parseInt($('#SeatPriceInn2').val());
	
	var totalPricePay=baseFarePrice+TaxAndFee+BaggageFeeInn+MealFeeInn+BaggageFeeInn2+MealFeeInn2+SeatPriceInn+SeatPriceInn2;
	
	console.log('baseFarePrice '+baseFarePrice+ ' TaxAndFee '+TaxAndFee+ 'BaggageFeeInn '+BaggageFeeInn+ ' MealFeeInn '+MealFeeInn+ ' SeatPriceInn '+SeatPriceInn);
	
	$('#totalAmountToPay').val(totalPricePay);
	 $('#coid').val(Number(totalPricePay+<?php echo $randval; ?>));
	 $('#totalpayAmt').html(totalPricePay);
	

}


</script>	

<script>

$(document).ready(function(){ //alert("ghvh");

    $(".ret").click(function(){

        $(".shd").toggle(300);

    });

});

$(document).ready(function(){ //alert("ghvh");

    $(".msshdd").click(function(){

        $(".msshd").toggle(300);

    });

});

</script>

<script type="text/javascript">

$(document).ready(function($) {

    /*$("#adltmeal").change(function() {

        var adltmeal = $(this).val(); 

        var splits = adltmeal.split(","); 

        var mlamt = splits[2]; 

        var splitss = mlamt.split("INR");

        var mlamount = splitss[1].trim();  

        $("#mealval").html(adltmeal);

       var finalclientcost = Number($('#finalclientcost').val()); 

 

      if(mlamount >0){ 

          var finalclientcost = parseInt(finalclientcost);

          var mlamnt= parseInt(mlamount); 

          $('#displayprice').text(Number(finalclientcost+mlamnt)); 

          $('#finalclientcost').val(Number(finalclientcost+mlamnt)); 

      }

  });*/

  $(document).ready(function($) {

    $(".adltmealCl").change(function() {

        var adltmeal = $(this).val(); //alert(search_id); exit;
		
		var totalAdult='<?php echo $_SESSION['ADT']; ?>';
		var totalChild='<?php echo $_SESSION['CHD']; ?>';

         $(".admealbtn").show();
		 var totalAdultMealPrice=0;

		for(i=1;i<=totalAdult;i++)
		{
			var  adlMealData= document.getElementById('adltmeal'+i).value;	
			 if(adlMealData.indexOf('INR') != -1){
				  adltmealArr = adlMealData.split("INR");
				  var adltmeal=adltmealArr[1].trim();
				  //$('#MealFeeInn').val(adltmeal);
				  // $('#MealFeeInn').val(parseInt(adltmeal)+parseInt($('#MealFeeInn').val()));	
				  // $("#mealval").html($('#MealFeeInn').val());
				   totalAdultMealPrice=totalAdultMealPrice+parseInt(adltmeal);			
			 }			
				
		
		}
		
		
		if(totalChild>0)
		{

				for(i=1;i<=totalChild;i++)
				{
					var  adlMealData= document.getElementById('cmeal'+i).value;	
					 if(adlMealData.indexOf('INR') != -1){
						  adltmealArr = adlMealData.split("INR");
						  var adltmeal=adltmealArr[1].trim();
						  //$('#MealFeeInn').val(adltmeal);
						  // $('#MealFeeInn').val(parseInt(adltmeal)+parseInt($('#MealFeeInn').val()));	
						  // $("#mealval").html($('#MealFeeInn').val());
						   totalAdultMealPrice=totalAdultMealPrice+parseInt(adltmeal);			
					 }			
						
				
				}		
		
		
		}
		
			
		
		$("#mealval").html(parseInt(totalAdultMealPrice));
		$('#MealFeeInn').val(parseInt(totalAdultMealPrice));		
		
		/* 
         $("#mealval").html(adltmeal);
		
		 if(adltmeal.indexOf('INR') != -1){
			  adltmealArr = adltmeal.split("INR");
			  var adltmeal=adltmealArr[1].trim();
			  //$('#MealFeeInn').val(adltmeal);
			   $('#MealFeeInn').val(parseInt(adltmeal)+parseInt($('#MealFeeInn').val()));	
			   $("#mealval").html($('#MealFeeInn').val());			
	     }
		 
		 */
		 

		  allCalCulatedPrice();

         

    });
	
	/* return flight */
	
 $(".adltmealClReturn").change(function() {

        var adltmeal = $(this).val(); //alert(search_id); exit;
		
		var totalAdult='<?php echo $_SESSION['ADT']; ?>';
		var totalChild='<?php echo $_SESSION['CHD']; ?>';

         $(".admealbtn").show();
		 var totalAdultMealPrice=0;

		for(i=1;i<=totalAdult;i++)
		{
			var  adlMealData= document.getElementById('ameal2'+i).value;	
			 if(adlMealData.indexOf('INR') != -1){
				  adltmealArr = adlMealData.split("INR");
				  var adltmeal=adltmealArr[1].trim();
				  //$('#MealFeeInn').val(adltmeal);
				  // $('#MealFeeInn').val(parseInt(adltmeal)+parseInt($('#MealFeeInn').val()));	
				  // $("#mealval").html($('#MealFeeInn').val());
				   totalAdultMealPrice=totalAdultMealPrice+parseInt(adltmeal);			
			 }			
				
		
		}
		
		
		if(totalChild>0)
		{

				for(i=1;i<=totalChild;i++)
				{
					var  adlMealData= document.getElementById('cmeal2'+i).value;	
					 if(adlMealData.indexOf('INR') != -1){
						  adltmealArr = adlMealData.split("INR");
						  var adltmeal=adltmealArr[1].trim();
						  //$('#MealFeeInn').val(adltmeal);
						  // $('#MealFeeInn').val(parseInt(adltmeal)+parseInt($('#MealFeeInn').val()));	
						  // $("#mealval").html($('#MealFeeInn').val());
						   totalAdultMealPrice=totalAdultMealPrice+parseInt(adltmeal);			
					 }			
						
				
				}		
		
		
		}
		
			
		
		$("#mealval2").html(parseInt(totalAdultMealPrice));
		$('#MealFeeInn2').val(parseInt(totalAdultMealPrice));		
		
		/* 
         $("#mealval").html(adltmeal);
		
		 if(adltmeal.indexOf('INR') != -1){
			  adltmealArr = adltmeal.split("INR");
			  var adltmeal=adltmealArr[1].trim();
			  //$('#MealFeeInn').val(adltmeal);
			   $('#MealFeeInn').val(parseInt(adltmeal)+parseInt($('#MealFeeInn').val()));	
			   $("#mealval").html($('#MealFeeInn').val());			
	     }
		 
		 */
		 

		  allCalCulatedPrice();

         

    });	
	
	
	/*
	$("#adltmeal2").change(function() {

        var adltmeal = $(this).val(); //alert(search_id); exit;

         $(".admealbtn").show();

         $("#mealval2").html(adltmeal);
		  
		  if(adltmeal.indexOf('INR') != -1){
		  
			  adltmealArr = adltmeal.split("INR");
			  var adltmeal=adltmealArr[1].trim();
			  $('#MealFeeInn2').val(adltmeal);	
		  }
		  allCalCulatedPrice();		 

         

    });
	*/
	

});

     $("#childmeal").change(function() {

        var childmeal = $(this).val(); //alert(search_id); exit;

         $("#mealchildval").html(childmeal);

          $("#cdmealbtn").show();

    });
	
	/* return flight */
	$("#childmeal2").change(function() {

        var childmeal = $(this).val(); //alert(search_id); exit;

         $("#mealchildval2").html(childmeal);

          $("#cdmealbtn").show();

    });
	

});

$(document).ready(function($) {

	
   
    $(".adultbaggage").change(function() {

        var adltbag = $(this).val(); //alert(search_id); exit;
			var totalAdultBaggage=0;
		
			var totalAdult='<?php echo $_SESSION['ADT']; ?>';
			var totalChild='<?php echo $_SESSION['CHD']; ?>';
		
			//alert(totalAdult);
			for(i=1;i<=totalAdult;i++)
			{
			  
			  //	var  baagageData= document.getElementsByName('abaggage'+i)[0].value;
				var  baagageData= document.getElementById('abaggage'+i).value;
			//	alert(baagageData);
				
				 if(baagageData.indexOf('INR') != -1){
				  adltbagArr = baagageData.split("INR");
				  var adltbag=adltbagArr[1].trim();
				// console.log(" selected "+adltbag);
				
				 totalAdultBaggage=totalAdultBaggage+parseInt(adltbag);
				 
				 //alert("total adult "+ totalAdultBaggage);

				 
				 // $('#BaggageFeeInn').val(parseInt(adltbag)+parseInt($('#BaggageFeeInn').val()));
				  
				 // console.log(" added "+$('#BaggageFeeInn').val());
				 
				 // $("#baggval").html($('#BaggageFeeInn').val());
			  	
		        }
				 	 	
			}
			
			if(totalChild>0)
			{
			
				for(i=1;i<=totalChild;i++)
				{
				  
				  //	var  baagageData= document.getElementsByName('abaggage'+i)[0].value;
					var  baagageData= document.getElementById('cbaggage'+i).value;
				//	alert(baagageData);
					
					 if(baagageData.indexOf('INR') != -1){
					  adltbagArr = baagageData.split("INR");
					  var adltbag=adltbagArr[1].trim();
					// console.log(" selected "+adltbag);
					
					 totalAdultBaggage=totalAdultBaggage+parseInt(adltbag);
					 
					
					}
							
				}
							
			}
			
			
				 
		$('#BaggageFeeInn').val(parseInt(totalAdultBaggage));
		$("#baggval").html(parseInt(totalAdultBaggage));
					
			//alert(adltbag);


          $(".adbagbtn").show();
/*		   if(adltbag.indexOf('INR') != -1){
			  adltbagArr = adltbag.split("INR");
			  var adltbag=adltbagArr[1].trim();
			// console.log(" selected "+adltbag);
			  $('#BaggageFeeInn').val(parseInt(adltbag)+parseInt($('#BaggageFeeInn').val()));
			  
			 // console.log(" added "+$('#BaggageFeeInn').val());
			  
			  
			  $("#baggval").html($('#BaggageFeeInn').val());
			  	
		  }*/
		  
		  
		  allCalCulatedPrice();
		  

    });

    $("#childbag").change(function() {

        var childbag = $(this).val(); //alert(search_id); exit;

         $("#baggchildval").html(childbag);

         $("#cdbagbtn").show();

    });
	
	
/* return flight round */


 $(".adultbaggageReturn").change(function() {

        var adltbag = $(this).val(); //alert(search_id); exit;
			var totalAdultBaggage=0;
		
			var totalAdult='<?php echo $_SESSION['ADT']; ?>';
			var totalChild='<?php echo $_SESSION['CHD']; ?>';
		
			//alert(totalAdult);
			for(i=1;i<=totalAdult;i++)
			{
			  
			  //	var  baagageData= document.getElementsByName('abaggage'+i)[0].value;
				var  baagageData= document.getElementById('abaggage2'+i).value;
			//	alert(baagageData);
				
				 if(baagageData.indexOf('INR') != -1){
				  adltbagArr = baagageData.split("INR");
				  var adltbag=adltbagArr[1].trim();
				// console.log(" selected "+adltbag);
				
				 totalAdultBaggage=totalAdultBaggage+parseInt(adltbag);
				 
				 //alert("total adult "+ totalAdultBaggage);

				 
				 // $('#BaggageFeeInn').val(parseInt(adltbag)+parseInt($('#BaggageFeeInn').val()));
				  
				 // console.log(" added "+$('#BaggageFeeInn').val());
				 
				 // $("#baggval").html($('#BaggageFeeInn').val());
			  	
		        }
				 	 	
			}
			
			if(totalChild>0)
			{
			
				for(i=1;i<=totalChild;i++)
				{
				  
				  //	var  baagageData= document.getElementsByName('abaggage'+i)[0].value;
					var  baagageData= document.getElementById('cbaggage2'+i).value;
				//	alert(baagageData);
					
					 if(baagageData.indexOf('INR') != -1){
					  adltbagArr = baagageData.split("INR");
					  var adltbag=adltbagArr[1].trim();
					// console.log(" selected "+adltbag);
					
					 totalAdultBaggage=totalAdultBaggage+parseInt(adltbag);
					 
					
					}
							
				}
							
			}
			
			
				 
		$('#BaggageFeeInn2').val(parseInt(totalAdultBaggage));
		$("#baggval2").html(parseInt(totalAdultBaggage));
					
			//alert(adltbag);


          $(".adbagbtn").show();
/*		   if(adltbag.indexOf('INR') != -1){
			  adltbagArr = adltbag.split("INR");
			  var adltbag=adltbagArr[1].trim();
			// console.log(" selected "+adltbag);
			  $('#BaggageFeeInn').val(parseInt(adltbag)+parseInt($('#BaggageFeeInn').val()));
			  
			 // console.log(" added "+$('#BaggageFeeInn').val());
			  
			  
			  $("#baggval").html($('#BaggageFeeInn').val());
			  	
		  }*/
		  
		  
		  allCalCulatedPrice();
		  

    });


/*
    $("#adltbag2").change(function() {

        var adltbag2 = $(this).val(); //alert(search_id); exit;

         $("#baggval2").html(adltbag2);
         $(".adbagbtn").show();
		 
		  if(adltbag2.indexOf('INR') != -1){
		  
		  adltbagArr = adltbag2.split("INR");
		  var adltbag2=adltbagArr[1].trim();
		  $('#BaggageFeeInn2').val(adltbag2);
		  }	
		  allCalCulatedPrice();		 

    });
*/	

    $("#childbag2").change(function() {

        var childbag2 = $(this).val(); //alert(search_id); exit;

         $("#baggchildval2").html(childbag2);

         $("#cdbagbtn2").show();

    });	
	
	

});



 
 function allBookingSubmit(){
	 $('#bookingMethod').val('0');
	 $("#flightbookingsubmit").submit();
 }
 
 
 $('#steponeflightdetails').hide();
 $('#steptwopassengerdetails').show();
 $('.flightreviewbox').removeClass('active');
 $('#strp2').addClass('active');
 $('.hidefooter').show();
 $('#showfooterpay').hide();
 $('#stepfourpayments').hide();
 
setInterval(function() {
var totalpayAmt = Number($('#totalpayAmt').text());
$('#paynowbtnmain').text('Pay Now ₹'+totalpayAmt);
$('.payonlinebtnmain').text('Pay Online ₹'+totalpayAmt);
}, 500); 
 
 
 
 function bookingFormSubmit(){
	 $("#bookingForm").submit();
 } 
</script>

<div class="mfp-with-anim mfp-hide mfp-dialog" style="max-width: 610px; border-radius: 10px; padding: 10px;" id="new-card-dialog">

  <div id="view-seats"></div>

</div>
