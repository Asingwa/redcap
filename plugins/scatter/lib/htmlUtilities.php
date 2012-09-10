<?php
/**
 * @brief Handy HTML utilities
 *
 * @file htmlUtilities.php
 * @version 1.0
 * $Revision: 176 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-09-10 14:25:01 #$: Date of last commit
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/scatter/lib/htmlUtilities.php $
 */


/**
 * @brief Builds a drop down list from an associative array
 * 
 * @param $listName     Name of the dropdown list control
 * @param $dropdownHash Associative array with the key used for the value 
 *                      and a value used for the label
 * @retval $htmlStr     HTML of the drop-down list
 */
function BuildDropDownList( $listName, $dropdownHash )
{
	$htmlStr = sprintf( "<select name=\"%s\">\n", $listName );
	
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
 * @brief   Sets the value of the text widget to the previous submission value
 * @author  Fred R. McClurg
 * @date    February 6, 2010
 *
 * @param   $requestKey    Value user entered from previous submission.  
 *                         An example call statement would be:
 * 
 * @code 
 * <input type="text" 
 *        name="title" 
 *        value="<?php echo SetStickeyValue( 'title', 'Default Title Here' ); ?>"
 * @endcode
 * 
 * @param   $requestKey    Name of the text control
 * @param   $defaultValue  Fallback value if previous submission value is undefined (optional).
 * @retval  $returnedValue Returns previously submitted value or the default.
 */
function SetStickeyValue( $requestKey, $defaultValue = "" )
{
   if ( array_key_exists( $requestKey, $_REQUEST ) )  // form submitted
   {
      $returnedValue = $_REQUEST[$requestKey];
   }
   else  // form not submitted
   {
      $returnedValue = $defaultValue;
	}

	return( $returnedValue );

}  //  function SetStickeyValue()


/**
 * @brief Builds a query string from an array of $_REQUEST[] keys
 * 
 * @param  $keyArray     Array of $_REQUEST[] keys
 * @retval $queryString  Returns "&" delimited key=value pairs
 */
function BuildQueryString( $keyArray )
{
	$hash = array();
	
	foreach ( $keyArray as $key )
	{
		$hash[$key] = $_REQUEST[$key];
	}
	
	$queryString = http_build_query( $hash );

	return( $queryString );
	
}  // function BuildQueryString()


/**
 * @brief Dumps the value of a variable
 * 
 * @param  $name   The variable name
 * @param  $value  The contents of the variable
 */
function PrintDebug( $name, $value )
{
	printf( "Variable %s: ", $name );
	printf( "<pre>%s</pre>", print_r( $value ) );
	
}  // function PringDebug()

?>
