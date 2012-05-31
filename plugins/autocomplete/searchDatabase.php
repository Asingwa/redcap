<?php
	/**
	 * @brief Extracts the data from a REDCap database in JSON format
	 *
	 * @file searchDatabase.php
	 * $Revision: 131 $
	 * $Author: fmcclurg $
	 * $Date:: 2012-05-18 13:48:33 #$: Date of commit
	 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/autocomplete/searchDatabase.php $
	 */

	// Call the REDCap Connect file in the main "redcap" directory
	require_once( "../redcap_connect.php" );
	
	// error message reporting
	require_once( "include/errorReporting.php" );

	// debug utilities
	require_once( "include/debugFunctions.php" );

	// local REDCap functions
	require_once( "include/redcapFunctions.php" );

	// variable initialization
	$pid = $_REQUEST['pid'];
	$dbPid = $_REQUEST['dbid'];
	$dbFieldLabel = $_REQUEST['dblabel'];
	$dbFieldData = $_REQUEST['dbfield'];
	$pFieldName = $_REQUEST['pfield'];
	$fieldSearch = $_REQUEST['term'];
	
	// Restrict this plugin to a specific REDCap project (in case user's randomly find the plugin's URL)
	if ( $pid != PROJECT_ID )
	{
	   $exitMessage = sprintf( "This plugin is only accessible to users from project \"%s\".", app_title );
	   exit( $exitMessage );
	}
	
	if ( isset( $_REQUEST['dblabel'] ) )
	{
		// display one value and store another
		$values = GetRedcapWildcardMultiValues( $dbFieldLabel,
				                                  $dbFieldData, 
				                                  $fieldSearch, 
				                                  $dbPid );
	}
	else
	{
		// display the same value and as the one stored
		$values = GetRedcapWildcardValues( $dbFieldData, 
				                             $fieldSearch, 
				                             $dbPid );
	}
	
	// convert array to JSON format
	printf( "%s", json_encode( $values ) );
?>