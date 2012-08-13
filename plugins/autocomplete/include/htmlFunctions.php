<?php
/**
 * @brief General purpose HTML functions
 *
 * @file htmlFunctions.php
 * $Revision: 159 $
 * $Author: fmcclurg $
 * $Date:: 2012-07-26 16:31:41 #$
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
      $keyValue = sprintf( "%s=%s", $key, urlencode( $value ) );
      array_push( $keyValueArray, $keyValue );
   }

   $queryString = implode( "&", $keyValueArray );

   return( $queryString );

}  // BuildQueryString();


/**
 * @brief Builds a drop down list from an associative array
 * 
 * @param $listName      Name of the dropdown list control
 * @param $dropdownHash  Associative array with the key used for the value 
 *                       and a value used for the label
 * @param $isAutoSubmit  If true, submit form upon change
 * @retval $htmlStr      HTML of the drop-down list
 */
function BuildDropDownList( $listName, 
                            $dropdownHash,
                            $isAutoSubmit = FALSE )
{
   if ( $isAutoSubmit )
   {
      $onChange = "onChange='this.form.submit()'";
   }
   
	$htmlStr = sprintf( "<select name='%s' %s>\n", $listName, $onChange );
	
   // the first menu option is blank
	$htmlStr .= "<option value=''></option>\n";
            
	foreach ( $dropdownHash as $key => $label )
	{
		if ( $_REQUEST[$listName] == $key )  // option already selected
		{
			// make item "sticky" and remember previous submission
			$htmlStr .= sprintf( "<option value=\"%s\" selected=\"selected\">%s</option>\n", 
			   $key, $label );
		}
		else
		{
			$htmlStr .= sprintf( "<option value=\"%s\">%s</option>\n", 
			   $key, $label );
		}
	}
	
	$htmlStr .= sprintf( "</select>\n" );

	return( $htmlStr );
	
}  // function BuildDropDownList()


/**
 * @brief Displays text or an icon if the value has been set
 * 
 * @param $listName      Name of the dropdown list control
 * @param $textStatus    Text to display prior to icon
 */
function TextOrIconStatus( $listName, $textStatus )
{
   if ( strlen( $_REQUEST[$listName] ) != 0 )
   {
      printf( "<img src='images/check.16x16.png' />");
   }
   else
   {
      printf( "%s", $textStatus );
   }
   
}  // function BuildDropDownList()


/**
 * @brief Displays text or an icon if the value has been set
 * 
 * @param  $initValue     Initial value of text control
 * @param  $keyName       Name of text control
 * @retval $defaultValue  Default text value returned
 */
function IntializeTextDefaultValue( $initValue, $keyName )
{
   if ( isset( $_REQUEST[$keyName] ) )
   {
      $defaultValue = $_REQUEST[$keyName];
   }
   else
   {
      $defaultValue = $initValue;
   }
   
   return( $defaultValue );
   
}  // function IntializeTextDefaultValue()

?>
