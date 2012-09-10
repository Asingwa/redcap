<?php
/**
 * @brief Performs a page redirection to project page
 *
 * @file index.php
 * @version $Revision:: 90            $: Revision of commit
 * @author $Author:: fmcclurg         $: Author of commit
 * @date $Date:: 2012-03-03 10:23:31 #$: Date of commit
 * @ref $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/scatter/redirect.php $
 */

	header("Location: home.php?pid=28");  /* redirect browser to main project page */
	
	/* Make sure that code below does not get executed when we redirect. */
   exit;
?>
