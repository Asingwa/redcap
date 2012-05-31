<?php
/**
 * @brief REDCap specific utilities
 *
 * @file redcapFunctions.php
 * @version 1.0
 * $Revision: 135 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-05-25 16:11:25 #$: Date of last commit
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/autocomplete/include/redcapFunctions.php $
 */

// set standard error reporting
require_once( "errorReporting.php");

// debugging functions
require_once( "debugFunctions.php" );


/**
 * @brief Returns a wildcard match of all values of a specified field in a project
 *
 * @param  $fieldName       Variable name of the field.
 * @param  $fieldValue      Value of the wildcard search
 * @param  $pid             Project identifier.  Defaults to the current project.
 * @retval $fieldNameArray  Returns a array of all the unique values.
 */
function GetRedcapWildcardValues( $fieldName, $fieldValue, $pid )
{
      // SELECT DISTINCT value AS %s
   $sql = sprintf( "
      SELECT value AS %s
      FROM redcap_data
      WHERE project_id = %d AND
            field_name = '%s' AND
   		   value LIKE '%%%s%%'
      ORDER BY value",
         $fieldName, $pid, $fieldName, $fieldValue );

   // printf( "<pre>%s</pre>", $sql );
   
   // execute the sql statement
   $result = mysql_query( $sql );

   // let me know if something went wrong
   if ( ! $result )  // sql failed
   {
      WhereAmi();  // which sql error message
      die( "Could not execute SQL:
            <pre>$sql</pre> <br />" .
            mysql_error() );
   }

   $fieldNameArray = array();

   // retrieve a unique list of records
   while ( $record = mysql_fetch_assoc( $result ) )
   {
      $fieldData = $record[$fieldName];
      // printf( "\$fieldValue: %s<br />", $fieldValue );
      array_push( $fieldNameArray, $fieldData );
   }

   return( $fieldNameArray );

}  // function GetRedcapWildcardValues()


/**
 * @brief Returns a wildcard match of the auto completion labels and 
 *        values of a specified field in a project
 *
 * @param  $fieldLabel      Variable name of labels to display on the list.
 * @param  $fieldData       Variable name of value to return from the list.
 * @param  $fieldSearch     Value of the wildcard search
 * @param  $pid             Project identifier.  Defaults to the current project.
 * @retval $fieldNameArray  Returns a array of all the unique values.
 */
function GetRedcapWildcardMultiValues( $fieldLabel, 
		                                 $fieldData, 
		                                 $fieldSearch, 
		                                 $pid )
{
   $sql = sprintf( "
		SELECT a.label,
		       b.data
		FROM
		   (SELECT record, value AS label
		      FROM redcap_data
		      WHERE project_id = %d AND
		            field_name = '%s' AND
		   		   value LIKE '%%%s%%' ) a
		JOIN
		   (SELECT record, value AS data
		      FROM redcap_data
		      WHERE project_id = %d AND
		            field_name = '%s' AND
		            value IS NOT NULL ) b
		ON a.record = b.record
		ORDER BY a.label",
         $pid, $fieldLabel, $fieldSearch, $pid, $fieldData );
   
   // printf( "<pre>%s</pre>", $sql );
   
   // execute the sql statement
   $result = mysql_query( $sql );

   // let me know if something went wrong
   if ( ! $result )  // sql failed
   {
      WhereAmi();  // which sql error message
      die( "Could not execute SQL:
            <pre>$sql</pre> <br />" .
            mysql_error() );
   }

   $fieldNameArray = array();

   // retrieve a unique list of records
   while ( $record = mysql_fetch_assoc( $result ) )
   {
	   $dataRecord = array();
	   
      $dataRecord['label'] = $record['label'];
      $dataRecord['value'] = $record['data'];
      
      // printf( "\$fieldValue: %s<br />", $fieldValue );
      array_push( $fieldNameArray, $dataRecord );
   }

   return( $fieldNameArray );

}  // function GetRedcapWildcardMultiValues()