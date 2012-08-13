<?php
/**
 * @brief General purpose PHP utilities
 *
 * @file debugFunctions.php
 * $Revision: 135 $
 * $Author: fmcclurg $
 * $Date:: 2012-05-25 16:11:25 #$
 * @since 2011-12-27
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/tags/autocomplete-1.3/include/debugFunctions.php $
 */

	// standard error reporting
	require_once( "errorReporting.php");

	/**
	 * @brief Retrieves the function name of the calling function
	 * 
	 * @return Name of the function
	 */
	function GetFunctionName()
	{
		$backtrace = debug_backtrace();
		
		# echo "<pre>";
		# var_dump( $backtrace );
		# echo "</pre>";
		
		return( $backtrace[2]['function'] );
	}  // GetFunctionName()
	
	
	/**
	 * @brief Retrieves the arguments passed to the calling function
	 * 
	 * @return Argument values of the function
	 */
	function GetFunctionArgs()
	{
		$backtrace = debug_backtrace();
		
		# echo "<pre>";
		# var_dump( $backtrace );
		# echo "</pre>";
		
		$argArray = $backtrace[1]['args'];
		
		$args = implode( ", ", $argArray );
		
		return( $args );
	}  // GetFunctionArgs()
	
	
	/**
	 * @brief Displays the line number of the calling function
	 * 
	 * @return Line number
	 */
	function  GetLineNumber()
	{
		$backtrace = debug_backtrace();
		
		# echo "<pre>";
		# var_dump( $backtrace );
		# echo "</pre>";
		
		return( $backtrace[1]['line'] );
	}  // GetLineNumber()
	
	
	/**
	 * @brief Displays the filename of the calling function
	 * 
	 * @return Basename of the file
	 */
	function GetFileName()
	{
		$backtrace = debug_backtrace();
		
		# echo "<pre>";
		# var_dump( $backtrace );
		# echo "</pre>";
		
		$file = basename( $backtrace[1]['file'] );
		return( $file );
	}  // GetFileName()
	
	
	/**
	 * @brief Displays function, arguments, line number, and file of the calling function
	 */
	function WhereAmI()
	{
		$func = GetFunctionName();
		$args = GetFunctionArgs();
		$line = GetLineNumber();
		$file = GetFileName();
		
		printf( "Function: %s(%s) &nbsp; Line: %d &nbsp; File: %s<br />\n", 
		        $func, $args, $line, $file );
	}  // WhereAmI()
	
	
	/**
	 * @brief Displays formatted string and the value of the variable
	 * 
	 * @param $variableName  String equivalent of variable name
	 * @param $allVariables  An array containing all variables
	 * 
	 * @code
	 *    PrintVar( "variableName", get_defined_vars() );
	 * @endcode
	 */
	function PrintVar( $variableName, $allVariables )
	{
		// print_r( array_keys( $allVariables ) );
		
		// strip out global variables
		$localVariables = array_diff( $allVariables, array( array() ) );
		
		printf( "$%s: %s<br />", 
			$variableName, $localVariables[$variableName] );
			
	}  // PrintVar()
	
	
	/**
	 * @brief Displays array values
	 * 
	 * @param $arrayValues   Array to display
	 */
	function PrintArray( $arrayValues )
	{
		printf( "<ul>" );
		
		foreach ( $arrayValues as $value )
		{
			printf( "<li> %s", $value );
		}
			
		printf( "</ul>" );
		
	}  // PrintArray()
	
?>
