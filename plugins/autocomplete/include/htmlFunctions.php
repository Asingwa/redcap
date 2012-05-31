<?php
/**
 * @brief General purpose HTML functions
 *
 * @file htmlFunctions.php
 * $Revision: 135 $
 * $Author: fmcclurg $
 * $Date:: 2012-05-25 16:11:25 #$
 * @since 2011-12-28
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/autocomplete/include/htmlFunctions.php $
 */

// standard error reporting
require_once( "errorReporting.php");

/**
 * @brief  Combines the array of passed keys to build a query string
 *
 * @param   $request      $_REQUEST variable
 * @retval  $queryString  String in the format: key=$_REQUEST['key']&key=$_REQUEST['key']
 */
function BuildQueryString( $request )
{
   $keyValueArray = array();

   foreach ( $request as $key => $value )
   {
      $keyValue = sprintf( "%s=%s", $key, $value );
      array_push( $keyValueArray, $keyValue );
   }

   $queryString = implode( "&", $keyValueArray );

   return( $queryString );

}  // PageRedirection();

?>
