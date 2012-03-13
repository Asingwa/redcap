<?php
/**
 * @brief Turn on verbose error message reporting
 *
 * @file errorReporting.php
 * @version 1.0
 * @version $Revision:: 99            $: Revision of commit
 * @author $Author:: fmcclurg         $: Author of commit
 * @author Fred R. McClurg, University of Iowa
 * @date $Date:: 2012-03-13 14:52:03 #$: Date of commit
 * @ref $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/plots/lib/errorReporting.php $
 */

	// Turn off all error reporting
	# error_reporting(0);
	
	// Report all PHP errors (see changelog)
	# error_reporting(E_ALL | E_STRICT);
	# error_reporting(E_ALL);
	
	// Report simple running errors
	# error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	// Reporting E_NOTICE can be good too (to report uninitialized
	// variables or catch variable name misspellings ...)
	# error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	
	// Report all errors except E_NOTICE
	// This is the default value set in php.ini
	error_reporting(E_ALL ^ E_NOTICE);
	
	ini_set("display_errors", 1);
?>
