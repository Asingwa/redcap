<?php
/**
 * @brief REDCap plugin that generates a google graph of data
 *
 * @file index.php
 * @version 1.0
 * $Revision: 176 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-09-10 14:25:01 #$: Date of last commit
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/scatter/index.php $
 * @ref $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/scatter/index.php $
 */

# Display verbose error reporting
require_once('lib/errorReporting.php');

# HTML utility functions
require_once('lib/htmlUtilities.php');

// Call the REDCap Connect file in the main "redcap" directory
require_once( '../redcap_connect.php' );

// Display the stand-along page header
# $HtmlPage = new HtmlPage();
# $HtmlPage->PrintHeaderExt();

// Restrict this plugin to a specific REDCap project (in case user's randomly find the plugin's URL)
if ( PROJECT_ID != $_REQUEST['pid'] )
{
	$exitMessage = sprintf( "This plugin is only accessible to users from project \"%s\".", app_title );
	exit( $exitMessage );
}
// allowProjects( $_REQUEST["pid"] );

// OPTIONAL: Display the project header
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

// Your HTML page content goes here
?>

<!-- Call the charts javascript library -->
<script type="text/javascript" src="<?php echo APP_PATH_WEBROOT ?>Graphical/charts.js"></script>

<!-- jQuery needed by Ajax call  --> 
<script type="text/javascript" src="jquery/jquery-1.7.1.js"></script>

<!-- JavaScript required by the following graph to image utilities -->
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script> 
<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script>
    
<!-- JavaScript functions to convert google charts to images --> 
<script type="text/javascript" src="js/googleGraphUtilities.js"></script>

<!-- Handy JavaScript utility functions --> 
<script type="text/javascript" src="js/javaScriptUtilities.js"></script>

<!-- local CSS customization -->
<link rel="stylesheet" type="text/css" href="css/style.css" />

<!-- Create your custom javascript code to construct the scatter plot on this page -->
<script type="text/javascript">
   // Load the Visualization API and the piechart package.
   google.load('visualization', '1', {'packages':['corechart']});
     
   // Set a callback to run when the Google Visualization API is loaded.
   google.setOnLoadCallback(drawChart);
     
   function drawChart() 
   {
      // var jasonUrl = 'generateJSONTestData.php';
      // var jasonUrl = 'generateJSON.php?pid=<?php echo $_REQUEST["pid"] ?>&x_axis=<?php echo $_REQUEST["x_axis"] ?>&y_axis=<?php echo $_REQUEST["y_axis"] ?>';
      var jasonUrl = 'generateJSON.php?<?php echo BuildQueryString( array( "pid", "x_axis", "y_axis" ) ); ?>';
      jasonUrl = jasonUrl.replace( /&/g, itoa( 38 ) );  // revert auto conversion
      
      // alert( "jasonUrl: " + jasonUrl );
      
      var jsonData = $.ajax({
         url: jasonUrl,
         dataType: "json",
         async: false
         }).responseText;

      // Create and populate the scatter chart
      var data = new google.visualization.DataTable(jsonData);
      // var data = new google.visualization.DataTable();
      
      // popup balloon label
      // data.addColumn('number', 'X');
      // data.addColumn('number', '<?php echo $_REQUEST["x_axis"] ?> / <?php echo $_REQUEST["y_axis"] ?>');  // popup balloon label
   
      // for (var i = 0; i < 10; ++i) 
      // {
         // data.addRow([Math.random()*10, Math.random()]);
         // data.setProperty( i, 1, "URL", "http://gty.org?q=" + i );
      // }
   
      // alert( "data.toJSON(): " + data.toJSON() );
      
      var chart = new google.visualization.ScatterChart(document.getElementById('plotBuilder'));
      var options = 
         {
            title: '<?php echo $_REQUEST["plot_title"] ?>',
            hAxis: {title: '<?php echo $_REQUEST["x_label"] ?>'}, 
            vAxis: {title: '<?php echo $_REQUEST["y_label"] ?>'}, 
            legend: 'none', 
            width: <?php echo $_REQUEST["width"] ?>, 
            height: <?php echo $_REQUEST["height"] ?>, 
         };
      
      chart.draw(data, options);
   
   google.visualization.events.addListener(chart, 'select', 
      function selectScatterPoint()
      {
         var selection = chart.getSelection()[0];  // only one point selected
   
         // for (var i = 0; i < selection.length; i++) 
         // {
           // var item = selection[i];
           var property = data.getProperty( selection.row, 
                                            selection.column, 
                                            "URL" );
           
           // alert( "row: " + selection.row + "\n" + 
                  // "column: " + selection.column + "\n" + 
                  // "property: " + property );

           // redirect page to specfic record
           window.location = property;
           
         /*
         var selection = chart.getSelection();
   
         if ( selection.length < 1 ) return;
   
         var message = '';
   
         for ( var i = 0; i < selection.length; i++ )
         {
            var itemRow = selection[i].row;
   
            if ( itemRow != null && recordEvent[itemRow] != null ) 
            {
               window.open(app_path_webroot + 'DataEntry/index.php?pid=' + pid + '&page=' + form + recordEvent[itemrow] + '&fldfocus=' + field + '#' + field + '-tr','_blank');
               return;
            }
         }
         */
      } );
   }  // function drawChart()
</script>

   
<!-- Display main page -->
<h1 style="color: #800000; text-align: center;">
	Plot Builder
</h1>

<p>
	Plot builder can generate a custom graph of your data.&nbsp; 
   Enter the graph parameters and the select the fields to plot.
</p>

<!-- Div that will contain the scatter plot -->
<div id="plotBuilder" style="text-align: center"></div>


<?php
	// build the sql statement to display the calc and text variables
	# a_a_o2_rank	apache	calc	(A-a)O2 Rank:	<null>
   # albumin	entry	text	Albumin:	float
   # current_age	apache	text	Current Age:	int

	$sql = sprintf( "
	   SELECT field_name, form_name, element_type, 
	          element_label, element_validation_type
         FROM redcap_metadata
         WHERE project_id = %d AND
            ( ( element_type = 'calc' ) OR
               ( ( element_type = 'text' ) AND
                  ( ( element_validation_type = 'float' ) OR 
                    ( element_validation_type = 'int' ) ) ) )
         ORDER BY field_name", 
	                    $_REQUEST['pid'] ); 
	
	// execute the sql statement
	$result = mysql_query( $sql );
	
	if ( ! $result )  // sql failed
	{
		die( "Could not execute SQL:
	         <pre>$sql</pre> <br />" .
	         mysql_error() );
	}
	
	$variableHash = array();
	
	while ($record = mysql_fetch_assoc( $result ))
	{
		$key = $record['field_name'];
		
		$value = sprintf( "%s, %s (%s)", 
		            $record['field_name'], 
		            $record['element_label'],
		            $record['form_name'] );
		            
		$variableHash[$key] = $value;
	}
?>
<!DOCTYPE script PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get">

<table cellspacing="0" align="center">
   <tr>
      <th>
         <table class="dt2 plotTable" width="100px">
            <tr class="odd">
               <th class="fieldHeader">Plot Title:</th>
               <td colspan="3">
                  <input type="text" name="plot_title" size="30" 
                         value="<?php echo $_REQUEST['plot_title'] ?>" />
               </td>
            </tr>

            <tr class="even">
               <th class="fieldHeader">X Label:</th>
               <td class="grp2">
                  <input type="text" name="x_label" size="10" 
                         value="<?php echo $_REQUEST['x_label'] ?>" />
               </td>
               
               <th class="fieldHeader">Y Label:</th>
               <td>
                  <input type="text" name="y_label" size="10" 
                         value="<?php echo $_REQUEST['y_label'] ?>" />
               </td>
            </tr>

            <tr class="odd">
               <th class="fieldHeader">Width:</th>
               <td class="grp2">
                  <input type="text" name="width" size="2" 
                         value="<?php echo SetStickeyValue( 'width', 600); ?>" />
               </td>
               
               <th class="fieldHeader">Height:</th>
               <td>
                  <input type="text" name="height" size="2" 
                         value="<?php echo SetStickeyValue('height', 400); ?>" />
               </td>
            </tr>

            <tr class="even">
               <th class="fieldHeader">X Axis:</th>
               <td colspan="3">
                  <?php echo BuildDropDownList( "x_axis", $variableHash ); ?>
               </td>
            </tr>

            <tr class="odd">
               <th class="fieldHeader">Y Axis:</th>
               <td colspan="3">
                  <?php echo BuildDropDownList( "y_axis", $variableHash ); ?>
               </td>
            </tr>

            <tr class="even">
               <th colspan="5">
                  <hr size="1" />
               </th>
            </tr>

            <tr class="odd">
               <th colspan="6" style="text-align: center; padding: 10px;" nowrap>
                  <input type="submit" name="submit" value="Generate Plot" class="buttonClass" />
               
<?php 
   if ( isset( $_REQUEST['width'] ) && isset( $_REQUEST['height'] ) )
   {
?>
	               <button type="button" onclick="javascript:saveAsImg(document.getElementById('plotBuilder'))" class="buttonClass">Download PNG Image</button>
               
	               <button type="button" onclick="container = document.getElementById('plotBuilder'); javascript:toImg(container, container);" class="buttonClass">Display Plot as Image</button>
<?php 
   }
?>
               </th>
            </tr>
         </table>
      </th>
   </tr>
</table>

   <!-- remember current project upon next submission -->
   <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>" />
</form>

<?php
	// OPTIONAL: Display the project footer
	require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
?>
