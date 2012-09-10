/**
 * @brief Set of handy JavaScript utility functions
 *
 * @file javaScriptUtilties.js
 * @version 1.0
 * $Revision: 99 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-03-13 14:52:03 #$: Date of last commit
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/scatter/js/javaScriptUtilities.js $
 */


/**
 * @brief Converts an integer to a char
 * 
 * @param  i    Integer to be converted
 * @retval chr  The ascii character equivalent of the integer
 */
function itoa(i) 
{
  var chr = String.fromCharCode(i);
  
  return( chr );
}  // function itoa() 


/**
 * @brief Converts a char to an integer
 * 
 * @param  chr  Character to be converted
 * @retval int  The integer equivalent of the character
 */
function atoi(chr) 
{
  var int = chr.charCodeAt();
  
  return( int );
}  // function atoi() 