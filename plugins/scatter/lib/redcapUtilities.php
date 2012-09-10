<?php
/**
 * @brief REDCap specific utilities
 *
 * @file redcapUtilities.php
 * @version 1.0
 * $Revision: 99 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-03-13 14:52:03 #$: Date of last commit
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/scatter/lib/redcapUtilities.php $
 */


/**
 * @brief Performs a REDCap query and returns values from the database
 * 
 * @param  $projectId   Id number of the project
 * @param  $xFieldName  The field name containing x values to retrieve
 * @param  $yFieldName  The field name containing y values to retrieve
 * @retval $valueHash   Hash with the record name as the key and the 
 *                      value is an array containing the value of two 
 *                      fields returned from the database.  For example:
 *                      @code
 *                         $valueHash[$key] = array( $xValue, $yValue );
 *                      @endcode
 */
function GetFieldValues( $projectId, $xFieldName, $yFieldName )
{
   $sql = sprintf( "
		SELECT a.record, a.xvalue, b.yvalue
		FROM (
		   SELECT record, value AS xvalue
		   FROM redcap_data
		   WHERE project_id = %d AND
		         field_name = '%s'
		) AS a
		JOIN (
		   SELECT record, value AS yvalue
		   FROM redcap_data
		   WHERE project_id = %d AND
		         field_name = '%s'
		) AS b ON a.record=b.record
		ORDER BY a.record",
			$projectId, $xFieldName, $projectId, $yFieldName ); 
	
		// execute the sql statement
		$result = mysql_query( $sql );
		
		if ( ! $result )  // sql failed
		{
			die( "Could not execute SQL:
		         <pre>$sql</pre> <br />" .
		         mysql_error() );
		}
		
		$variableHash = array();
		
		while ($record = mysql_fetch_assoc( $result ))
		{
			$key = $record['record'];
			$xValue = $record['xvalue'];
			$yValue = $record['yvalue'];
			$valueHash[$key] = array( $xValue, $yValue );
		}
	
	return( $valueHash );
	
}  // function GetFieldValues()


/**
 * @brief Performs a REDCap query and determines which form the field belongs
 * 
 * @param $projectId   Id number of the project
 * @param $fieldName   The field name containing values to retrieve
 * @retval $formName   The name for the form
 */
function GetFormName( $projectId, $fieldName )
{
   $sql = sprintf( "
	   SELECT form_name
         FROM redcap_metadata
         WHERE redcap_metadata.project_id = %d AND
               redcap_metadata.field_name = '%s' ",
				      $projectId, $fieldName ); 
	
		// execute the sql statement
		$result = mysql_query( $sql );
		
		if ( ! $result )  // sql failed
		{
			die( "Could not execute SQL:
		         <pre>$sql</pre> <br />" .
		         mysql_error() );
		}
		
		while ($record = mysql_fetch_assoc( $result ))
		{
			$key = "form_name";
			$formName = $record[$key];
		}
	
	return( $formName );
	
}  // function GetFormName()


/**
 * @brief Performs a REDCap query and determines the event id
 * 
 * @param $projectId   Id number of the project
 * @param $fieldName   The field name containing values to retrieve
 * @retval $eventId    The event id
 */
function GetDataEventId( $projectId, $fieldName )
{
   $sql = sprintf( "
	   SELECT DISTINCT event_id
		   FROM redcap_data
			   WHERE project_id = %d AND
			         field_name = '%s'
			   ORDER BY event_id",
				   $projectId, $fieldName ); 
	
		// execute the sql statement
		$result = mysql_query( $sql );
		
		if ( ! $result )  // sql failed
		{
			die( "Could not execute SQL:
		         <pre>$sql</pre> <br />" .
		         mysql_error() );
		}
		
		while ($record = mysql_fetch_assoc( $result ))
		{
			$key = "event_id";
			$eventId = $record[$key];
		}
	
	return( $eventId );
	
}  // function GetDataEventId()
