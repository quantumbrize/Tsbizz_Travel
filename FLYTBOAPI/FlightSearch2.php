<?php
/*include dirname(__FILE__).'/APIConstants.php';
include dirname(__FILE__).'/RestApiCaller.php';
header("Content-Type: application/json");*/
$auth=array();
//$wsdl = APISEDL;

function  createTimeArrDept($time)
{

	$finalDate=date('H:i',strtotime(date('Y-m-d')." ".$time));
	return $finalDate;

}

$auth["SiteName"]="";
$auth["AccountCode"]="";
$auth["UserName"]=APIUSERNAME;
$auth["Password"]=APIPASSWORD;
$Rurl= APISEARCH;

//$cabinclass=$data['class'];
 
 

if($_REQUEST['PC']=='Economy'){
$PC=2;
}

if($_REQUEST['PC']=='Premium Economy'){
$PC=3;
}

if($_REQUEST['PC']=='Business'){
$PC=4;
}

if($_REQUEST['PC']=='First Class'){
$PC=6;
}

$FlightCabinClass=$PC;


$TokenId=$_SESSION['tbotokenId'];
/*$origin=$_POST['origin'];
$destination=$_POST['destination'];
$departdate=$_POST['departdate'];
$returndate=$_POST['returndate'];
$adult=$_POST['adult'];
$child=$_POST['child'];
$infant=$_POST['infant'];
$agentId=$_POST['agentId'];*/

$type=$_POST['journeyType'];
//$mod=$_POST['mod'];
$dirflt='true';
$oneflt='false';
$prefer="";

$type = 'OneWay';

$ip=$_SERVER['REMOTE_ADDR'];
$_SESSION['EndUserIp']=$ip;




if($tripType=='1'){ 
	 $journeyDate = date('Y-m-d',strtotime($journeyDateOne));
	 $returnDate = '';
}else{ 
	 
	 $journeyDate = date('Y-m-d',strtotime($journeyDateOne));
	 $returnDate = date('Y-m-d',strtotime($journeyDateRound));
}

$fromdestexp = explode('-',$fromDestinationFlight);
$todestexp = explode('-',$toDestinationFlight);

$adult=$ADT;
$child=$CHD;
$infant=$INF;
$toalPax=($adult+$child+$infant);
if($_SESSION['directflight']==1){
$dirflt='true';
} else {
$dirflt='false';
}
$oneflt='false';
$prefer=""; 
$departdate=$journeyDate;
$returnDate=$returnDate;
$origin=$fromdestexp[0];
$destination=$todestexp[0];

$toalPaxFinal=$ADT+$CHD+$INF;
$toalPax=$ADT+$CHD;
 

if($type == 'OneWay')
{
    
/*$FSS = $dbFunction->master->fetchSingle("wig_flight_search_setting", array("id"=> '1')); 
if($FSS['DirectFlight']){$dirflt=$FSS['DirectFlight'];}else{$dirflt='false';}
if($FSS['OneStopFlight']){$oneflt=$FSS['OneStopFlight'];}else{$oneflt='false';}
if($FSS['PreferredAirlines']){$prefer=$FSS['PreferredAirlines'];}else{$prefer='';}*/




 $opta = array( 
               "EndUserIp" => $ip,
               "TokenId" => $TokenId,
               "AdultCount" => $adult,
               "ChildCount" => $child,
               "InfantCount" => $infant,
               "DirectFlight" => $dirflt,
               "OneStopFlight" => $oneflt,
               "JourneyType" => "1",
               "PreferredAirlines" => $prefer,
               "Segments" => array (array (
                            "Origin" => $origin,
                           "Destination" => $destination,
                           "FlightCabinClass" => $FlightCabinClass,
                           "PreferredDepartureTime" => $departdate.'T00:00:00',
                           "PreferredArrivalTime" => $departdate.'T00:00:00'
            				)
				)
         );

} else{
    
    $opta = array( 
               "EndUserIp" => $ip,
               "TokenId" => $TokenId,
               "AdultCount" => $adult,
               "ChildCount" => $child,
               "InfantCount" => $infant,
               "JourneyType" => "2",
               "PreferredAirlines" => "",
               "Segments" => array (array (
                            "Origin" => $origin,
                           "Destination" => $destination,
                           "FlightCabinClass" => $FlightCabinClass,
                           "PreferredDepartureTime" => $departdate.'T00:00:00',
                           "PreferredArrivalTime" => $departdate.'T00:00:00',
            				),
            				array (
                           "Origin" => $destination,
                           "Destination" => $origin,
                           "FlightCabinClass" => $FlightCabinClass,
                           "PreferredDepartureTime" => $returndate.'T00:00:00',
                           "PreferredArrivalTime" => $returndate.'T00:00:00'
            				)
				)
         );
}


 	
			

$search_result=array();




		

try
{
$req=str_replace('\/','/',json_encode($opta));
$req=file_put_contents("FLYTBOJSON/SearchReq2.txt","$req");
$postdata = file_get_contents("FLYTBOJSON/SearchReq2.txt","$req"); //Take JSON input from Postman Client
//echo $postdata; //exit;
$header = array('Content-Type: application/json', 'Accept-Encoding: gzip');
$restCaller = new RestApiCaller();
$flightRes = $restCaller->post($Rurl, $postdata, $header);

$result=file_put_contents("FLYTBOJSON/".$agentId."_TBOSearchRes2.txt","$flightRes");
$search_result = json_decode($flightRes,true);


 

//$traceId = $search_result->TraceId;
//$BaseFare = $search_result->Fare->BaseFare;
}
catch(Exception $e)
{
    $errhdng="Technical Error !!";
    $errmsg="Sorry Due To Some Technical Issue, Flights Result Are Not Found.";
   // include dirname(dirname(__FILE__)).'/error.php';
    exit;
}


//echo '<pre>'; print_r($search_result); exit;

// insert query

					$publishFareInner2=0;
					$NetFareInner2=0;
					$fareTypeInnerDetail='';
					$flightTypeStatus=1; // for TBO
					
		 //	print_r($search_result);
	
			    			if (is_array($search_result['Response']['Results'][0])){

        			    				$WSResult[] = $search_result['Response']['Results'][0];
        						}else{
										
										 $WSResult =  $search_result;
								}
					
					
					$_SESSION['search_result']= $search_result;
					$TraceId=$search_result["Response"]["TraceId"];
					$_SESSION['TraceId'] = $TraceId;    
					

			   $nnn=1;
        			foreach($WSResult[0] as $valArray){   

				if($nnn==1){
				$newbaggage=$valArray['Segments'][0][0]['Baggage'];
				$newcabin=$valArray['Segments'][0][0]['CabinBaggage'];
				}
        			    unset($outbound); unset($inbound); unset($seg);



        			    if(is_array($valArray['Segments'][0][0]['Baggage']))



                          $seg[] = $valArray['Segments'][0];



                        else 



                          $seg = $valArray['Segments'][0];                         
						   foreach($seg as $segment){  



                          $outbound[] = $segment;



                        } 



                        $ResultIndex= $valArray['ResultIndex'];
						$IsLCC= $valArray['IsLCC'];



                        $ResultFareType= $valArray['ResultFareType'];



                        $flight_seg= $valArray['Segments'][0]; $flight_segt= count($flight_seg); $flight_segment = $flight_segt - 1;



                        $refstatuss= $valArray['IsRefundable'];



                        if($refstatuss == '1'){$refstatus= "REFUNDABLE";}else{$refstatus= "NON REFUNDABLE";}



                       



                        $remarks = $valArray['AirlineRemark'];



                        if($remarks){$remarks=$remarks;}else{$remarks='';}



                        



                         $i = 0;



                        foreach($outbound as $obound)



                        {



                            if($i == 0)



                        	{



                        	    



                        	$NoOfSeatAvailable = $obound['NoOfSeatAvailable'];  



                            $Baggage = $obound['Baggage'];



                            $CabinBaggage = $obound['CabinBaggage'];



                            $airline = $obound['Airline']['AirlineName'];



                        	$haveAirline = $airlinecode = $obound['Airline']['AirlineCode'];



                        	$fno= $obound['Airline']['FlightNumber'];



                        	$airlinenum = $airlinecode."-".$obound['Airline']['FlightNumber']."-".$obound['Airline']['FareClass']; 



                        	$fclass= $obound['Airline']['FareClass'];



                        	$deptime = $obound['Origin']['DepTime']; 



                        	$depcity = $obound['Origin']['Airport']['CityName'].", ".$obound['Origin']['Airport']['CountryName']."(".$obound['Origin']['Airport']['AirportCode'].")";



                        	$depcityy = $obound['Origin']['Airport']['CityName'];



                        	$deptitle = $obound['Origin']['Airport']['AirportName']." Airport";     



                        	$stopcity = $obound['Origin']['Airport']['CityName'];



                        	$conflt = $airlinecode."-".$obound['Airline']['FlightNumber'];



                        	$dep_code= $obound['Origin']['Airport']['AirportCode'];



                        	



                        	}else{



                        	    



                        	    $dep_codee = $obound['Origin']['Airport']['AirportCode'];



                        		$stopcity .= " &rarr; ".$obound['Origin']['Airport']['CityName'];



                        		$conflt .= " &rarr; ".$airlinecode."-".$obound['Airline']['FlightNumber'];



                        		$haveAirline .= ",".$obound['Airline']['AirlineCode'];



                        	}



                            
							$AccumulatedDuration = $obound['Duration'];


                           if($i == count($outbound) - 1)



                        	{



                        	    $arrtime = $obound['Destination']['ArrTime'];
								
								if($obound['AccumulatedDuration']!=''){
                        	    $AccumulatedDuration = $obound['AccumulatedDuration'];
								}



                        	    $arr_codee = $obound['Destination']['Airport']['AirportCode'];



                        	    $arrcity = $obound['Destination']['Airport']['CityName'].", ".$obound['Destination']['Airport']['CountryName']."(".$obound['Destination']['Airport']['AirportCode'].")";



                        	    $arrcityy = $obound['Destination']['Airport']['CityName'];



                        		$arrtitle = $obound['Destination']['Airport']['AirportName']." Airport";



                        	    $stopcity .= " &rarr; ".$obound['Destination']['Airport'];



                        	   



                        	}



                        	$allAirlines[] = $obound['Airline']['AirlineCode'];



                        	if($obound['Airline']['AirlineName'] != '')



                        	$allAirlinesName[] =$obound['Airline']['AirlineName'];



                        	$i++;



                        	



                        }



                       $S_CODE= $dep_code .'-'. $arr_codee;



                       $CN_CODE= $airlinecode . ' ' . $fno; 



                        //I5 775,I5 472



                        



                        ################### TIME CALCULATION #####################



                        $msdate1= $arrtime;



                        $msdate1= explode('T',$msdate1); 



                        $msdateaxp1= $msdate1['0'].' '.$msdate1['1']; 



                        



                        $msdate2= $deptime;



                        $msdate2= explode('T',$msdate2); 



                        $msdateaxp2= $msdate2['0'].' '.$msdate2['1']; 



                        $seconds = strtotime($msdateaxp1) - strtotime($msdateaxp2);



                        



                        $hours   = floor($seconds / 3600); 



                        $minutes = floor(($seconds - ($hours * 3600))/60); 



                        



                        ################### TIME CALCULATION #####################

						

                        $BaseFare= round($valArray['Fare']['BaseFare']);



                        $Tax= round($valArray['Fare']['Tax']);



                        $PublishedFare= round($valArray['Fare']['PublishedFare']);



                        $OfferedFare= round($valArray['Fare']['OfferedFare']); //offered fare 



                        



                        //$FareBasisCode= $valArray['FareRules']['0']['FareBasisCode'];



                        $FareBasisCode= $valArray['FareClassification']['Color']; 



                        



                        if($NoOfSeatAvailable){$NoOfSeatAvailable=$NoOfSeatAvailable;}else{$NoOfSeatAvailable='9';}



                        if($FareBasisCode){$FareBasisCode=$FareBasisCode;}else{$FareBasisCode='';}


 



        			    $FLIGHT_INFO= $refstatus.'|3,1|Baggage:Cabin='.$CabinBaggage.',Checkin='.$Baggage.'|Cancellation=ASAP Airlines,Reschedule=ASAP Airlines|CLASS='.$fclass;
						$FLIGHT_INFO=$newbaggage.','.$newcabin;


                        $TAX_BREAKUP= 'ab='.$BaseFare.',ay=0,at='.$Tax; 


						/* TBO Markup */
						
							 	 
								 $fareTypeInnerDetail=$FareBasisCode; //PCC
								 $flightTypeStatus=2;
								 $FIXEDID='';
								 $api= "TBO";
								// $ft= 'DOM';
						
						// End TBO
        			
        			   $insertData = array(



        			        "ResultIndex" => $ResultIndex,



        			        "AirlineRemark" => $remarks,



                            "kafila_id" => '',



                            "UID" => '',



                            "TID" => '',



                            "FIXEDID" => '',



                            "ORG_CODE" => $dep_code,



                            "ORG_NAME" => $depcityy,



                            "DES_CODE" => $arr_codee,



                            "DES_NAME" => $arrcityy,



                            "DEP_DATE" => $msdate2['0'],



                            "DEP_TIME" => createTimeArrDept($msdate2['1']),



                            "ARRV_DATE" => $msdate1['0'],



                            "ARRV_TIME" => createTimeArrDept($msdate1['1']),



                            "FLIGHT_CODE" => trim($airlinecode),



                            "FLIGHT_NAME" => $airline,



                            "FLIGHT_NO" => trim($fno),



                         



                            "FLIGHT_LOGO" => '',



                            "FARE_TYPE" => $ResultFareType,



                            "SEAT" => $NoOfSeatAvailable,



                            "STOP" => $flight_segment,



                            "AMT" => $PublishedFare,



                            "DUR" => $hours.'H '.$minutes.'M',



                            "S_CODE" => $S_CODE,



                            "CN_CODE" => $CN_CODE,



                            "OI" => '',



                            "PCC" => $FareBasisCode,



                            "TAX_BREAKUP" => $TAX_BREAKUP,



                            "FLIGHT_INFO" => $FLIGHT_INFO,



                            "NET_FARE" => $OfferedFare,



                            "F_CLASS" => $ResultFareType,



                            "partnerid" => '',



                            "PNR" => '',



                            "status" => '1'
	



                        );



 
$flightType='D';


		$i = 1;
			$baseFare=0;	
			$surcharge=0;	 
			 
			if($FareBasisCode=='RosyBrown')
			{
				$totalPaxFare=$PublishedFare;
			}
			else
			{
				$totalPaxFare=round($BaseFare);
			}	
			$totalTax=round($Tax);
			
			 
			
			$getCalCost=calculateflightcost(encode($agentid),trim(str_replace(' ','',$airline)),$flightType,$FareBasisCode,$toalPax,$totalPaxFare,$totalTax);
			$getCalCost2=calculateflightcostForAgent(encode($agentid),trim(str_replace(' ','',$airline)),$flightType,$FareBasisCode,$toalPax,$totalPaxFare,$totalTax);
			$getAgentTaxonly=calculateflightcostForAgentMarkup(encode($agentid),trim(str_replace(' ','',$airline)),$flightType,$FareBasisCode,$toalPax,$totalPaxFare,$totalTax);
			

/*			echo "<br>*****************************<br>";
			
			echo encode($agentid)."------".$airline."-------".$flightType."-------".$ResultFareType."--------".$ResultFareType."----------".$toalPax."-------".$totalPaxFare."-----".$totalTax;
			echo "<br>*****************Result************<br>";
			
			print_r($getCalCost2);
			die;	*/		
			
		
		 if($totalPaxFare==getAgentCommission($totalPaxFare,trim(str_replace(' ','',$airline)),$ResultFareType)){ 
		 
		 $netamount = round($getCalCost[1]);
		 
		  }else{ 
		  
		  $netamount = round($getCalCost2[1]-(getAgentCommission($totalPaxFare,trim(str_replace(' ','',$airline)),$ResultFareType)));
		  
		   } 
			
			$flightinfodata = explode('|', $flightList->FLIGHT_INFO);
			
			
				$rsAPI=GetPageRecord('*','sys_userMaster','id=1');  
				$getapimarkup=mysqli_fetch_array($rsAPI); 
				
				if($getapimarkup['tboApiMarkup']!='' && $getapimarkup['tboApiMarkup']>0){ 
				$apimarkup=round($getCalCost2[1]*$getapimarkup['tboApiMarkup']/100);
				} else { 
				$apimarkup=0;
				}

			
			 $adfare='baseFare='.$totalPaxFare.',tax='.$totalTax.',totalFare='.($totalPaxFare+$totalTax);
			 $agfare='baseFare='.$getCalCost2[2].',tax='.($getCalCost2[0]+$apimarkup).',totalFare='.($getCalCost2[1]+$apimarkup);
			 $clfare='baseFare='.$getCalCost2[2].',tax='.$getCalCost2[0].',totalFare='.$getCalCost2[1];
			 $nefareamountnew=round($OfferedFare+$getCalCost['3']);
			 
			   $adminMarkup=($getCalCost2[1]-($totalPaxFare+$totalTax));
			  $totaldisplayTax=($getCalCost2[0]+$adminMarkup+$apimarkup)-($getAgentTaxonly[0]);
			 
			 
	 $getNetFare=calculateflightcostForAgentNetFare(encode($agentid),trim(str_replace(' ','',$airline)),$flightType,$FareBasisCode,$toalPax,$OfferedFare,$totalTax); 
		   $netamount = $getNetFare[0];
	 
	 $commissiondeff=round(($totalPaxFare+$totalTax)-$OfferedFare);
		 
 if(getBlockFlights($agentId,trim(str_replace(' ','',$airline)),$FareBasisCode)!=1){
 
 
 				$netFareBeforecomm=(($getCalCost2[1]+$apimarkup)-$commissiondeff);

if($commissiondeff>0){
$tds=round($commissiondeff*5/100);
$gst=round($commissiondeff*18/100); 
$netFareBeforecomm=($netFareBeforecomm+$tds+$gst);
}	
 
 
 $agentFixedMakup=round(agentfixmarkup(encode($agentid),trim(str_replace(' ','',$airline)),$flightType,$FareBasisCode,$toalPax,$getCalCost2[1],$totalTax));


$AccumulatedDuration=intdiv($AccumulatedDuration, 60).'H :'. ($AccumulatedDuration % 60).' M'; 


 $namevalue ='UID="",TID="",ResultIndex="'.$ResultIndex.'",ORG_CODE="'.$dep_code.'",apiType="tbo",ORG_NAME="'.$depcityy.'",DES_CODE="'.$arr_codee.'",DES_NAME="'.$arrcityy.'",DEP_DATE="'.$msdate2['0'].'",DEP_TIME="'.createTimeArrDept($msdate2['1']).'",ARRV_DATE="'.$msdate1['0'].'",ARRV_TIME="'.createTimeArrDept($msdate1['1']).'",FLIGHT_CODE="'.trim($airlinecode).'",FLIGHT_NAME="'.$airline.'",FLIGHT_NO="'.trim($fno).'",FARE_TYPE="'.$ResultFareType.'",SEAT="'.$NoOfSeatAvailable.'",STOP="'.$flight_segment.'",AMT="'.$getCalCost2[1].'",DISPLAY_AMT="'.$PublishedFare.'",DUR="'.$AccumulatedDuration.'",S_CODE="'.$S_CODE.'",CN_CODE="'.$CN_CODE.'",OI="",PCC="'.$FareBasisCode.'",TAX_BREAKUP="0",FLIGHT_INFO="'.$FLIGHT_INFO.'",NET_FARE="'.$OfferedFare.'",refundyes="'.$refstatuss.'",AirlineRemark="",F_CLASS="'.$PC.'",SECTOR="",adfare="'.$adfare.'",agfare="'.$agfare.'",clfare="'.$clfare.'",CON_DETAILS="",PARAM_DATA="'.addslashes(serialize($valArray)).'",agentId="'.$_SESSION['agentUserid'].'",searchJson="",tripType=1,acom="'.$commissiondeff.'",IsLCC="'.$IsLCC.'",netFareBeforecomm="'.(($getCalCost2[1]+$apimarkup)-$commissiondeff).'",agentMarkup="'.$getAgentTaxonly[0].'",adminMarkup="'.$adminMarkup.'",totalTax="'.$totaldisplayTax.'",uniqueSessionId="'.$_SESSION['uniqueSessionId'].'",finalPayAmount="'.$netFareBeforecomm.'",agentFixedMakup="'.$agentFixedMakup.'"';
		 

				
addlistinggetlastid('wig_flight_json_bkp',$namevalue); 

}
                         // $dbFunction->partner->insert("wig_flight_json", $insertData);  


        			    $ns++;


 $nnn++;
       			
					
					}






?>