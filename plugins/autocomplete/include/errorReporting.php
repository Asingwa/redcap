<?php
/**
 * @brief Set error reporting to verbose
 *
 * @file errorReporting.php
 * $Revision: 126 $
 * $Author: fmcclurg $
 * $Date:: 2012-05-15 15:22:10 #$
 * @since 2011-12-27
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/tags/autocomplete-1.3/include/errorReporting.php $
 */

# Report all PHP errors (see changelog)
ini_set("display_errors", '1');
ini_set("display_startup_errors", '1');
error_reporting(E_ALL ^ E_NOTICE);

?>
