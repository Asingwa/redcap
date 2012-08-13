<?php
   /**
    * @brief Performs autocompletion on an external REDCap project
    *
    * @file  jQueryDialogAutoComplete.php
    * $Revision: 159 $
    * $Author: fmcclurg $
    * $Date:: 2012-07-26 16:31:41 #$
    * @since 2012-05-14
    * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/autocomplete/index.php $
    */

   // Call the REDCap Connect file in the main "redcap" directory
   require_once( "../redcap_connect.php" );

	// common utility functions
	require_once( "include/htmlFunctions.php" );

   // $dirName = dirname( $_SERVER['SCRIPT_NAME'] );
   $dirName = dirname( $_SERVER['PHP_SELF'] );

   if ( ! isset( $_REQUEST['pid'] ) ||
        $_REQUEST['pid'] != PROJECT_ID )
   {
?>
      <script type="text/javascript">
         window.alert( 'You do not have access to this project.' );
      </script>
<?php
   
      die( 'You do not have access to this project.' );
   }
?>

   <!-- CSS Style Sheets -->
   <link href="<?php echo $dirName ?>/jquery/css/smoothness/jquery-ui-1.8.20.custom.css" type="text/css" rel="stylesheet" />

   <style>
      .ui-autocomplete-loading { background: white url('<?php echo $dirName ?>/jquery/development-bundle/demos/autocomplete/images/ui-anim_basic_16x16.gif') right center no-repeat; }
   </style>

   <!-- JavaScript Functions -->
   <script src="<?php echo $dirName ?>/jquery/js/jquery-1.7.2.min.js" type="text/javascript" type="text/javascript"></script>
   <script src="<?php echo $dirName ?>/jquery/js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

<?php
   $pfield = $_REQUEST['pfield'];
   $title = $_REQUEST['title'];
   $body = $_REQUEST['body'];

   $scriptName = sprintf( "%s/searchDatabase.php", $dirName );
	// $queryString = BuildQueryString( $_REQUEST );
	$queryString = $_SERVER['QUERY_STRING'];
   $searchScript = sprintf( "%s?%s", $scriptName, $queryString );
?>

   <script type="text/javascript">
      var title = $('#<?php echo $pfield ?>').attr('title');
      var text = $('#<?php echo $pfield ?>').html();
      $(function() {
         $( "#<?php echo $pfield ?>" ).dialog({
            modal: true,
            title: "<?php echo $title ?>",
            // autoOpen: false,
            buttons: {
               Ok: function() {
                     $( "input[name='<?php echo $pfield ?>']" ).val( $( "#<?php echo $pfield ?>_auto" ).val() );
                     $( this ).dialog( "close" );
               }
            }
         });

         $( "#<?php echo $pfield ?>_auto" ).autocomplete({
            source: "<?php echo $searchScript ?>",
            select: function( event, ui ) {
               // $( "input[name='<?php echo $pfield ?>']" ).val( ui.item.value );  // auto value insertion upon item selection
               $( this ).dialog( "close" );  // auto close upon item selection (does not work)
            }
         });
      });
   </script>

   <?php echo $body ?>
   <form>
      <input type="text" id="<?php echo $pfield ?>_auto" />
   </form>
