<?php
/**
 * @brief REDCap specific utilities
 *
 * @file redcapFunctions.php
 * @version 1.0
 * $Revision: 159 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-07-26 16:31:41 #$: Date of last commit
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/tags/autocomplete-1.3/include/redcapFunctions.php $
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


/**
 * @brief Obtains the REDCap variables of a project
 *
 * @param  $pid             Project identifier.  Defaults to the current project.
 * @retval $fieldNameHash   Returns a hash of variable names.
 */
function GetRedcapFieldNames( $pid = PROJECT_ID )
{
   // build the sql statement to display the text variables
   $sql = sprintf( "
      SELECT field_name, form_name, element_type, 
             element_label, element_validation_type
         FROM redcap_metadata
         WHERE project_id = %d AND
            element_type = 'text'
         ORDER BY field_name", $pid ); 
   
   // execute the sql statement
   $result = mysql_query( $sql );
   
   if ( ! $result )  // sql failed
   {
      die( "Could not execute SQL:
            <pre>$sql</pre> <br />" .
            mysql_error() );
   }
   
   $fieldNameHash = array();
   
   while ($record = mysql_fetch_assoc( $result ))
   {
      $key = $record['field_name'];
      
      $fieldNameHash[$key] = $key;
   }

   return( $fieldNameHash );

}  // function GetRedcapWildcardValues()


/**
 * @brief Obtains the REDCap project names
 *
 * @retval $projectNameHash   Returns a hash of project names and ids.
 */
function GetRedcapProjectNames()
{
   if ( SUPER_USER ) 
   {
      $sql = "SELECT project_id, app_title
              FROM redcap_projects
              ORDER BY TRIM(app_title), project_id";
   } 
   else 
   {
      $sql = sprintf( "SELECT p.project_id, TRIM(p.app_title)
                       FROM redcap_projects p, redcap_user_rights u
                       WHERE p.project_id = u.project_id and u.username = '%s'
                       ORDER BY TRIM(p.app_title), p.project_id", USERID );
   }
   
   $query = mysql_query($sql);
   
   if ( ! $query )  // sql failed
   {
      die( "Could not execute SQL:
            <pre>$sql</pre> <br />" .
            mysql_error() );
   }
   
   $projectNameHash = array();
   
   while ($row = mysql_fetch_assoc($query))
   {
      $value = strip_tags(label_decode($row['app_title']));
      $key = $row['project_id'];
      
      if (strlen($value) > 80) 
      {
         $value = trim(substr($value, 0, 70)) . " ... " . 
                             trim(substr($value, -15));
      }
      
      if ($value == "") 
      {
         $value = "[Project title missing]";
      }
      
      $projectNameHash[$key] = $value;
   }
   
   return( $projectNameHash );

}  // function GetRedcapWildcardValues()