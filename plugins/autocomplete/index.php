<?php
   /**
    * @brief Performs autocompletion on an external REDCap project
    *
    * @file  jQueryDialogAutoComplete.php
    * $Revision: 135 $
    * $Author: fmcclurg $
    * $Date:: 2012-05-25 16:11:25 #$
    * @since 2012-05-14
    * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/autocomplete/index.php $
    */

   // Call the REDCap Connect file in the main "redcap" directory
   require_once "../redcap_connect.php";

	// common utility functions
	require_once( "include/htmlFunctions.php" );
?>

<?php
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
   <link href="/redcap/autocomplete/jquery/css/smoothness/jquery-ui-1.8.20.custom.css" type="text/css" rel="stylesheet" />

   <style>
      .ui-autocomplete-loading { background: white url('/redcap/autocomplete/jquery/development-bundle/demos/autocomplete/images/ui-anim_basic_16x16.gif') right center no-repeat; }
   </style>

   <!-- JavaScript Functions -->
   <script src="/redcap/autocomplete/jquery/js/jquery-1.7.2.min.js" type="text/javascript" type="text/javascript"></script>
   <script src="/redcap/autocomplete/jquery/js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
   <script src="/redcap/autocomplete/js/jsFunctions.js" type="text/javascript"></script>

<?php
   $pfield = $_REQUEST['pfield'];
   $title = $_REQUEST['title'];
   $body = $_REQUEST['body'];

   $scriptName = "/redcap/autocomplete/searchDatabase.php";
	$queryString = BuildQueryString( $_REQUEST );
   $searchScript = sprintf( "%s?%s", $scriptName, $queryString );
?>

   <script type="text/javascript">
      var title = $('#<?= $pfield ?>').attr('title');
      var text = $('#<?= $pfield ?>').html();
      $(function() {
         $( "#<?= $pfield ?>" ).dialog({
            modal: true,
            title: "<?= $title ?>",
            // autoOpen: false,
            buttons: {
               Ok: function() {
                     $( "input[name='<?= $pfield ?>']" ).val( $( "#<?= $pfield ?>_auto" ).val() );
                     $( this ).dialog( "close" );
               }
            }
         });

         $( "#<?= $pfield ?>_auto" ).autocomplete({
            source: "<?= $searchScript ?>",
            select: function( event, ui ) {
               // $( "input[name='<?= $pfield ?>']" ).val( ui.item.value );  // auto value insertion upon item selection
               $( this ).dialog( "close" );  // auto close upon item selection (does not work)
            }
         });
      });
   </script>

   <?= $body ?>
   <form>
      <input type="text" id="<?= $pfield ?>_auto" />
   </form>
