<?php
/**
 * @brief Page that queries the database and generates output in JSON format
 *
 * @file index.php
 * @version 1.0
 * $Revision: 99 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-03-13 14:52:03 #$: Date of last commit
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/scatter/generateJSON.php $
 */

	// Call the REDCap Connect file in the main "redcap" directory
	require_once( '../redcap_connect.php' );

   // printf( "File: %s  Line: %d<br />", __FILE__, __LINE__ );
   
	// Display verbose error reporting
	require_once('lib/errorReporting.php');

   // printf( "File: %s  Line: %d<br />", __FILE__, __LINE__ );
   
	// Debug functions
	require_once('lib/debugUtilities.php');

	// Math functions
	require_once('lib/mathUtilities.php');

	// WhereAmI();
   
	// REDCap utility functions
	require_once('lib/redcapUtilities.php');

	// WhereAmI();
   
	// Restrict this plugin to a specific REDCap project (in case user's randomly find the plugin's URL)
	if ( PROJECT_ID != $_REQUEST['pid'] )
	{
		$exitMessage = sprintf( "This script is only accessible to users from project \"%s\".", app_title );
		exit( $exitMessage );
	}

	// WhereAmI();
   
	// printf( '{"cols":[{"id":"","label":"X","pattern":"","type":"number"},{"id":"","label":"height_cm / weight_kg","pattern":"","type":"number"}],"rows":[{"c":[{"v":0.4298169630311588,"f":null},{"v":0.1909039756471571,"f":null,"p":{"URL":"http://google.com?q=0"}}]},{"c":[{"v":6.604207806416018,"f":null},{"v":0.46118872971489566,"f":null,"p":{"URL":"http://google.com?q=1"}}]},{"c":[{"v":4.525013159778463,"f":null},{"v":0.14738265543080775,"f":null,"p":{"URL":"http://google.com?q=2"}}]},{"c":[{"v":2.5277136198539782,"f":null},{"v":0.035855860758471914,"f":null,"p":{"URL":"http://google.com?q=3"}}]},{"c":[{"v":4.6356276573028055,"f":null},{"v":0.9018075826514426,"f":null,"p":{"URL":"http://google.com?q=4"}}]},{"c":[{"v":5.597871019742481,"f":null},{"v":0.9125492252051849,"f":null,"p":{"URL":"http://google.com?q=5"}}]},{"c":[{"v":4.850741790899175,"f":null},{"v":0.21751957942043898,"f":null,"p":{"URL":"http://google.com?q=6"}}]},{"c":[{"v":5.323058427590539,"f":null},{"v":0.04547234786739118,"f":null,"p":{"URL":"http://google.com?q=7"}}]},{"c":[{"v":1.825595881162403,"f":null},{"v":0.05753383785129518,"f":null,"p":{"URL":"http://google.com?q=8"}}]},{"c":[{"v":6.81350869996821,"f":null},{"v":0.19515777437233328,"f":null,"p":{"URL":"http://google.com?q=9"}}]}],"p":null}' );
	
   /*
	printf( '
	{
	   "cols":[
	      {"id":"","label":"X","pattern":"","type":"number"},
	      {"id":"","label":"height_cm / weight_kg","pattern":"","type":"number"}],
	   "rows":[
	      {"c":[{"v":0.4298169630311588,"f":null},{"v":0.1909039756471571,"f":null,"p":{"URL":"http://google.com?q=0"}}]},
	      {"c":[{"v":6.604207806416018,"f":null},{"v":0.46118872971489566,"f":null,"p":{"URL":"http://google.com?q=1"}}]},
	      {"c":[{"v":4.525013159778463,"f":null},{"v":0.14738265543080775,"f":null,"p":{"URL":"http://google.com?q=2"}}]},
	      {"c":[{"v":2.5277136198539782,"f":null},{"v":0.035855860758471914,"f":null,"p":{"URL":"http://google.com?q=3"}}]},
	      {"c":[{"v":4.6356276573028055,"f":null},{"v":0.9018075826514426,"f":null,"p":{"URL":"http://google.com?q=4"}}]},
	      {"c":[{"v":5.597871019742481,"f":null},{"v":0.9125492252051849,"f":null,"p":{"URL":"http://google.com?q=5"}}]},
	      {"c":[{"v":4.850741790899175,"f":null},{"v":0.21751957942043898,"f":null,"p":{"URL":"http://google.com?q=6"}}]},
	      {"c":[{"v":5.323058427590539,"f":null},{"v":0.04547234786739118,"f":null,"p":{"URL":"http://google.com?q=7"}}]},
	      {"c":[{"v":1.825595881162403,"f":null},{"v":0.05753383785129518,"f":null,"p":{"URL":"http://google.com?q=8"}}]},
	      {"c":[{"v":6.81350869996821,"f":null},{"v":0.19515777437233328,"f":null,"p":{"URL":"http://google.com?q=9"}}]} ],
	   "p":null
   }' );
	 */
	
	/*
	 * {
	 *    "cols":[
	 *       {"id":"","label":"","pattern":"","type":"number"},
	 *       {"id":"","label":"Value","pattern":"","type":"number"},
	 *       {"id":"","label":"Median","pattern":"","type":"number"}],
	 *    "rows":[
	 *       {"c":[{"v":193,"f":"193\n\n"},{"v":0.85,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":172.6,"f":"172.6\n\n"},{"v":0.78,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.45,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":185.4,"f":"185.4\n\n"},{"v":0.88,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.47,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":165,"f":"165\n\n"},{"v":0.58,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.29,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.41,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162,"f":"162\n\n"},{"v":0.81,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":180.3,"f":"180.3\n\n"},{"v":0.81,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":172.7,"f":"172.7\n\n"},{"v":0.69,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":172.7,"f":"172.7\n\n"},{"v":0.58,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":167,"f":"167\n\n"},{"v":0.32,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":168,"f":"168\n\n"},{"v":0.56,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177,"f":"177\n\n"},{"v":0.27,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":172.7,"f":"172.7\n\n"},{"v":0.22,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":182.9,"f":"182.9\n\n"},{"v":0.66,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.81,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.11,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":175,"f":"175\n\n"},{"v":0.43,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":163,"f":"163\n\n"},{"v":0.85,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":172.7,"f":"172.7\n\n"},{"v":0.39,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":166,"f":"166\n\n"},{"v":0.63,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":183,"f":"183\n\n"},{"v":0.9,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.17,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":165.1,"f":"165.1\n\n"},{"v":0.69,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":170.2,"f":"170.2\n\n"},{"v":0.62,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.34,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":175.3,"f":"175.3\n\n"},{"v":0.18,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":170.2,"f":"170.2\n\n"},{"v":0.75,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":182.9,"f":"182.9\n\n"},{"v":0.85,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":170.2,"f":"170.2\n\n"},{"v":0.13,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":180.3,"f":"180.3\n\n"},{"v":0.62,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":190.5,"f":"190.5\n\n"},{"v":0.39,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":180.3,"f":"180.3\n\n"},{"v":0.1,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":152.4,"f":"152.4\n\n"},{"v":0.19,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":180,"f":"180\n\n"},{"v":0.88,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":188,"f":"188\n\n"},{"v":0.3,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.51,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.78,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":170.2,"f":"170.2\n\n"},{"v":0.2,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":160,"f":"160\n\n"},{"v":0.3,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":167.6,"f":"167.6\n\n"},{"v":0.46,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.43,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.76,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.63,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.55,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":162.6,"f":"162.6\n\n"},{"v":0.52,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":157.5,"f":"157.5\n\n"},{"v":0.53,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":160,"f":"160\n\n"},{"v":0.57,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":180,"f":"180\n\n"},{"v":0.85,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":155,"f":"155\n\n"},{"v":0.48,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":173,"f":"173\n\n"},{"v":0.86,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":188,"f":"188\n\n"},{"v":0.57,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":180.3,"f":"180.3\n\n"},{"v":0.47,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":167.6,"f":"167.6\n\n"},{"v":0.12,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":191,"f":"191\n\n"},{"v":0.36,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":154,"f":"154\n\n"},{"v":0.18,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":175,"f":"175\n\n"},{"v":0.36,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":175.3,"f":"175.3\n\n"},{"v":0.44,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":170,"f":"170\n\n"},{"v":0.83,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":158,"f":"158\n\n"},{"v":0.3,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":175,"f":"175\n\n"},{"v":0.47,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":170,"f":"170\n\n"},{"v":0.54,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":182,"f":"182\n\n"},{"v":0.6,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":170,"f":"170\n\n"},{"v":0.48,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":180,"f":"180\n\n"},{"v":0.64,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":165,"f":"165\n\n"},{"v":0.58,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":168,"f":"168\n\n"},{"v":0.68,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":167.6,"f":"167.6\n\n"},{"v":0.24,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":157,"f":"157\n\n"},{"v":0.46,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":165,"f":"165\n\n"},{"v":0.78,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":177.8,"f":"177.8\n\n"},{"v":0.44,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":175.3,"f":"175.3\n\n"},{"v":0.82,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":175.3,"f":"175.3\n\n"},{"v":0.3,"f":"Click plot point to go to this record /"},{"v":null,"f":null}]},
	 *       {"c":[{"v":172.7,"f":"172.7\n\n"},{"v":null,"f":null},{"v":0.5,"f":"Median value /"}]}],"p":null}
	 */
   
   $json = sprintf( "
   {
      \"cols\":[
	      {\"id\":\"\",\"label\":\"X\",\"pattern\":\"\",\"type\":\"number\"},
	      {\"id\":\"\",\"label\":\"(%s, %s)\",\"pattern\":\"\",\"type\":\"number\"},
	      {\"id\":\"\",\"label\":\"Median Values\",\"pattern\":\"\",\"type\":\"number\"} ],
      \"rows\":[", $_REQUEST['x_axis'], $_REQUEST['y_axis'] );
   
   $hash['cols'] = array();  // initialize array
   
   $xyHash = GetFieldValues( $_REQUEST['pid'], 
                             $_REQUEST['x_axis'], 
                             $_REQUEST['y_axis'] );
   
	$formName = GetFormName( $_REQUEST['pid'], $_REQUEST['x_axis'] );
	$eventId = GetDataEventId( $_REQUEST['pid'], $_REQUEST['x_axis'] );
	
	$jsonRows = "";
	
	$xArray = array();
	$yArray = array();
	
	foreach ( $xyHash as $key => $xyPairs )
	{
		list( $xColumn, $yColumn ) = $xyPairs;
		
		if ( ( $xColumn != null ) && ( $yColumn != null ) )
		{
			array_push( $xArray, $xColumn );
			array_push( $yArray, $yColumn );
			
			if ( $jsonRows )  // not the first row
	      {
		      $jsonRows .= ",";
	      }
	   
		   $url = sprintf( "/redcap/redcap_v%s/DataEntry/index.php?pid=%d&id=%s&event_id=%d&page=%s",
		                   $redcap_version, $_REQUEST['pid'], $key, $eventId, $formName );
		
	      $jsonRows .= sprintf( "
         {\"c\":[{\"v\":%s,\"f\":\"(%s, %s)\"},{\"v\":%s,\"f\":\"%s\",\"p\":{\"URL\":\"%s\"}}]}",
            $xColumn, $xColumn, $yColumn, $yColumn, $key, $url );
	   }
	}  // foreach
	
	// echo Median(array(1, 5, 2, 8, 7)) . "<br />";  // 5
	// echo Median(array(1, 6, 2, 8, 7, 2)) . "<br />";  // 4
	// echo Median(array(44,12,34,21,34,55,77,54)) . "<br />";  // 39
	// echo Median(array(1,1,1,2,2,3,3,3)) . "<br />";  // 2
	// echo Median(array(4.1, 5.6, 7.2, 1.7, 9.3, 4.4, 3.2)) . "<br />";  // 4.4
   // echo Median(array(4.1, 7.2, 1.7, 9.3, 4.4, 3.2)) . "<br />";       // 4.25

	$xMedian = Median( $xArray );
	$yMedian = Median( $yArray );
	
	$noopUrl = "javascript:void(0)";
   $jsonRows .= sprintf( ",
         {\"c\":[{\"v\":%s,\"f\":null},{\"v\":null,\"f\":\"null\"},{\"v\":%s,\"f\":null,\"p\":{\"URL\":\"%s\"}}]}", 
            $xMedian, $yMedian, $noopUrl );
   
	$json .= $jsonRows;
   $json .= sprintf( " ],
      \"p\":null
   }", $_REQUEST['x_axis'], $_REQUEST['y_axis'] );
   
	// printf( "<pre>" );
	printf( "%s", $json );
	// printf( "</pre>" );
?>
