<?php
/**
 * @brief Handy HTML utilities
 *
 * @file htmlUtilities.php
 * $Revision: 196 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-10-11 10:56:54 #$
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/tags/geochart-1.0/lib/htmlUtilities.php $
 */


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
   $onChange = "";

   if ( $isAutoSubmit )
   {
      // $onChange = "onChange='alert(\"Foo!\");'";
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
 * @brief   Sets the value of the text widget to the previous submission value
 * @author  Fred R. McClurg
 * @date    February 6, 2010
 *
 * @param   $requestKey    Name of the text control
 * @param   $defaultValue  Fallback value if previous submission value is undefined (optional).
 * @retval  $returnedValue Returns previously submitted value or the default.
 *
 * An example call statement would be:
 *
 * @code
 * <input type="text"
 *        name="title"
 *        value="<?php echo SetStickyValue( 'title',
 *                                           'Default Title Here' ); ?>" />
 * @endcode
 */
function SetStickyValue( $requestKey, $defaultValue = "" )
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

}  //  function SetStickyValue()


/**
 * @brief   Sets the attribute of the checkbox widget to the previous submission setting
 * @author  Fred R. McClurg
 * @date    September 26, 2012
 *
 * @param   $requestKey    Name of the checkbox control
 * @param   $isChecked     Default value if previous submission value is undefined (optional).
 * @retval  $returnedValue Returns previously submitted value or the default.
 *
 * An example call statement would be:
 *
 * @code
 * <input type="checked"
 *        name="showHide"
 *        <?php echo SetStickyChecked( 'showHide', TRUE ); ?> />
 * @endcode
 *
 */
function SetStickyChecked( $requestKey, $isChecked = FALSE )
{
   $returnedValue = "";

   if ( array_key_exists( $requestKey, $_REQUEST ) )  // form submitted
   {
      // $returnedValue = sprintf( "checked='%s'", $_REQUEST[$requestKey] );
      $returnedValue = "checked='checked'";
   }
   else  // form not submitted
   {
      if ( $isChecked )
      {
         $returnedValue = "checked='checked'";
      }
   }

   return( $returnedValue );

}  //  function SetStickyChecked()


/**
 * @brief   Returns a red star unless all of the required fields have been completed
 * @author  Fred R. McClurg
 * @date    October 5, 2012
 *
 * @param   $keyArray    Array of names of the required fields
 *
 * Possible Scenarios:  "X" means requirement has not been satisfied
 *    X Unsubmitted (button not pressed)
 *    X $_REQUEST['business'] = '';  // value not set
 *    $_REQUEST['business'] = 'Value Set';
 */
function RequiredFieldNotice( $keyArray = array() )
{
   $returnedValue = "";

   foreach ( $keyArray as $requestKey )
   {
      if ( ! array_key_exists( $requestKey, $_REQUEST ) ||  // form not submitted
           strlen( $_REQUEST[$requestKey] ) == 0 )  // option has been set
      {
            $returnedValue = sprintf( "<span style='color: %s;'>*</span>\n", "red" );
            break;  // requirement has not been met
      }
   }

   echo( $returnedValue );

}  //  function RequiredFieldNotice()


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
