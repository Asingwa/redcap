<?php
   /**
    * @brief Wizard page that builds the custom autocomplete plugin code
    *
    * @file wizard.php
    * $Revision: 167 $
    * $Author: fmcclurg $
    * $Date:: 2012-08-15 13:49:39 #$: Date of commit
    * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/trunk/autocomplete/wizard.php $
    */

   // Call the REDCap Connect file in the main "redcap" directory
   require_once( "../redcap_connect.php" );

   // error message reporting
   require_once( "include/errorReporting.php" );

   // debug utilities
   require_once( "include/debugFunctions.php" );

   // local HTML functions
   require_once( "include/htmlFunctions.php" );

   // local REDCap functions
   require_once( "include/redcapFunctions.php" );

   // variable initialization
   // $pid = $_REQUEST['pid'];

   $title = "REDCap Autocomplete Plugin Wizard";

   // OPTIONAL: Display the project header
   require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

   // html page content to follow

?>
<head>
   <title>
      <?php echo $title ?>
   </title>

   <!-- CSS Style Sheets -->
   <link href="jquery/css/smoothness/jquery-ui-1.8.20.custom.css" type="text/css" rel="stylesheet" />

   <!-- JavaScript Functions -->
   <script src="jquery/js/jquery-1.7.2.min.js" type="text/javascript" type="text/javascript"></script>
   <script src="jquery/js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

   <style>
      td
      {
         padding: 5px;
         vertical-align: top;
      }

      .tableHeader
      {
         text-align: center;
      }

      .tablePrompt
      {
         text-align: right;
         font-weight: bold;
      }
   </style>

   <script type="text/javascript">
      function HighlightRow( idName )
      {
         $( idName ).fadeTo(1000, 0.0).fadeTo(1000, 1.0);
      }
   </script>
</head>

<body>
   <h1 style="text-align: center;">
      <?php echo $title ?>
   </h1>

   <p> This form can assist you in building the custom autocomplete code. </p>

   <p /> <br />

   <form action="<?php echo $_SERVER['PHP_SELF'] ?>"
         method="get" name="wizard">

   <table class="dt2" style="font-family:Verdana;font-size:11px;">
      <tbody>
      <tr class="grp2">
         <td colspan="4" style="text-align: center;">
            Autocomplete Fields
         </td>
      </tr>
      <tr class="hdr2">
         <td class="tableHeader">
            Query String Key
         </td>
         <td class="tableHeader">
            Query String Value
         </td>
         <td class="tableHeader">
            Status
         </td>
         <td class="tableHeader">
            Description
         </td>
      </tr>

      <tr class="even">
         <td class="rprt tablePrompt">
            Script Name:
         </td>
         <td>
<?php
   $scriptName = sprintf( "%s/%s", dirname( $_SERVER['PHP_SELF'] ),
                                   "index.php" );
?>
            <input type="text"
                   name="script"
                   onblur="this.form.submit()"
                   value="<?php echo IntializeTextDefaultValue( $scriptName,
                                                                'script' ); ?>"
                   size="30" />
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( 'script', "Required" );
            ?>
         </td>
         <td>
            The location of the autocomplete script on the server
            (not usually modified).
         </td>
      </tr>

      <tr class="grp2" style="background: #CEC3D5;">
         <td colspan="4" style="text-align: center;">
            Source Database Fields
         </td>
      </tr>
      
      <tr class="odd" id="sourceDatabaseProject">
         <td class="rprt tablePrompt">
            Source Database Project:
         </td>
         <td>
            <?php
               echo BuildDropDownList( 'dbid',
                                       GetRedcapProjectNames(),
                                       TRUE );
            ?>
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( 'dbid', "Required" );
            ?>
         </td>
         <td>
            The project of the source database.&nbsp; This is the 
            REDCap database that contains the list of records that 
            comprise the all autocompletion items.
         </td>
      </tr>

      <tr class="even" id="sourceDBFieldReturned">
         <td class="rprt tablePrompt">
            Source DB Field Returned:
         </td>
         <td>
            <?php
               echo BuildDropDownList( 'dbfield',
                                       GetRedcapFieldNames( $_REQUEST['dbid'] ),
                                       TRUE );
            ?>
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( 'dbfield', "Required" );
            ?>
         </td>
         <td>
            The name of the source database text field 
            that will be used to populate the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#targetProjectField');">Target Project Field</a>
                
            (below).&nbsp; This field contains all the possible values 
            that could be selected for insertion into the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#targetProjectField');">Target Project Field</a>.&nbsp;
                
            This field will also serve as the label 
            for the autocompletion list items when the field 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#sourceDBFieldLabel');">Source DB Field Label</a>
                
            (below) is <em><strong>not specified</strong></em>.&nbsp; 
            This field is completely dependant upon the 
            current selection of the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#sourceDatabaseProject');">Source Database Project</a>
                
            (above).
         </td>
      </tr>

      <tr class="odd" id="sourceDBFieldLabel">
         <td class="rprt tablePrompt">
            Source DB Field Label 
            (if different from the Source DB Field Returned):
         </td>
         <td>
            <?php
               echo BuildDropDownList( 'dblabel',
                                       GetRedcapFieldNames( $_REQUEST['dbid'] ),
                                       TRUE );
            ?>
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( 'dblabel', "Optional" );
            ?>
         </td>
         <td>
            Specify this field only when you need to perform an 
            autocompletion match against one database field (e.g. 
            Physician's Name) and the value that is stored in the 
            project comes from a different database field (e.g. 
            Physician's ID).&nbsp;  By specifying a value for this field,
            you are able to select a physician's name and 
            their corresponding ID will be entered into your 
            project.&nbsp; This field
            <strong><em>must be</em></strong> used in conjunction 
            with the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#sourceDBFieldReturned');">Source DB Field Returned</a>
                
            (above).&nbsp;
            This field is also completely dependant upon the current 
            selection of the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#sourceDatabaseProject');">Source Database Project</a>
                
            (above).
         </td>
      </tr>

      <tr class="grp2" style="background: #CEC3D5;">
         <td colspan="4" style="text-align: center;">
            Target Project Fields
         </td>
      </tr>
      
      <tr class="even" id="targetProjectName">
         <td class="rprt tablePrompt">
            Target Project Name:
         </td>
         <td>
            <?php
               echo BuildDropDownList( 'pid',
                                       GetRedcapProjectNames(),
                                       TRUE );
            ?>
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( 'pid', "Required" );
            ?>
         </td>
         <td>
            The target project.&nbsp; This is the REDCap project
            that will implement the plugin and receive the 
            autocompletion items as input.
         </td>
      </tr>

      <tr class="odd" id="targetProjectField">
         <td class="rprt tablePrompt">
            Target Project Field:
         </td>
         <td>
            <?php
               echo BuildDropDownList( 'pfield',
                                       GetRedcapFieldNames( $_REQUEST['pid'] ),
                                       TRUE );
            ?>
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( 'pfield', "Required" );
            ?>
         </td>
         <td>
            The variable name of the target project text field that will
            receive the autocompletion value.&nbsp;
            This field is completely dependant upon the 
            current selection of the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#targetProjectName');">Target Project Name</a>.&nbsp;
                
            (above).
         </td>
      </tr>

      <tr class="grp2" style="background: #CEC3D5;">
         <td colspan="4" style="text-align: center;">
            Dialog Annotation
         </td>
      </tr>
      
      <tr class="even">
         <td class="rprt tablePrompt">
            Dialog Title:
         </td>
         <td>
<?php
   $titleText = "Autocomplete Dialog";
?>
            <input type="text"
                   name="title"
                   onblur="this.form.submit()"
                   value="<?php echo IntializeTextDefaultValue( $titleText,
                                                                'title' ); ?>"
                   size="30" />
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( 'title', "Optional (but recommended)" );
            ?>
         </td>
         <td>
            The dialog title.
         </td>
      </tr>

      <tr class="odd">
         <td class="rprt tablePrompt">
            Dialog Body Text:
         </td>
         <td>
<?php
   $bodyText = "Begin typing value to display list:";
?>
            <input type="text"
                   name="body"
                   onblur="this.form.submit()"
                   value="<?php echo IntializeTextDefaultValue( $bodyText,
                                                                'body' ); ?>"
                   size="30" />
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( "body", "Optional" );
            ?>
         </td>
         <td>
            The text of the dialog.
         </td>
      </tr>

      <tr class="grp2" style="background: #CEC3D5;">
         <td colspan="4" style="text-align: center;">
            Link Annotation
         </td>
      </tr>
      
      <tr class="even">
         <td class="rprt tablePrompt">
            Field Note Text:
         </td>
         <td>
<?php
   $fieldNoteText = "For value lookup, ";
?>
            <input type="text"
                   name="fieldNote"
                   onblur="this.form.submit()"
                   value="<?php echo IntializeTextDefaultValue( $fieldNoteText,
                                                                'fieldNote' ); ?>"
                   size="30" />
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( "fieldNote", "Optional" );
            ?>
         </td>
         <td>
            The text of the field note.
         </td>
      </tr>

      <tr class="odd">
         <td class="rprt tablePrompt">
            Hypertext Link Text:
         </td>
         <td>
<?php
   $hyperText = "see autocomplete dialog ";
?>
            <input type="text"
                   name="hyperText"
                   onblur="this.form.submit()"
                   value="<?php echo IntializeTextDefaultValue( $hyperText,
                                                                'hyperText' ); ?>"
                   size="30" />
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( "hyperText", "Optional" );
            ?>
         </td>
         <td>
            The text of the hypertext link.
         </td>
      </tr>

      <tr class="even">
         <td class="rprt tablePrompt">
            Hypertext Hover Help:
         </td>
         <td>
<?php
   $hoverText = "Click for listing dialog";
?>
            <input type="text"
                   name="hoverText"
                   onblur="this.form.submit()"
                   value="<?php echo IntializeTextDefaultValue( $hoverText,
                                                                'hoverText' ); ?>"
                   size="30" />
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( "hoverText", "Optional" );
            ?>
         </td>
         <td>
            The pop-up help displayed upon hover of the hypertext link
            (browser dependant).
         </td>
      </tr>

      <tr class="odd" id="iconType">
         <td class="rprt tablePrompt">
            Hypertext Icon Type:
         </td>
         <td>
            <?php
               $icons = array( "balloon.png" => "Balloon",
                               "binoculars.png" => "Binoculars",
                               "caption.png" => "Caption",
                               "dialog.png" => "Dialog",
                               "gear.png" => "Gear",
                               "info.png" => "Info",
                               "lightning.png" => "Lightning",
                               "list.png" => "List",
                               "find.png" => "Search",
                               "window.png" => "Window" );
               echo BuildDropDownList( 'icon',
                                       $icons,
                                       TRUE );
            ?>
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( "icon", "Optional" );
            ?>
         </td>
         <td>
            The icon type of the hypertext link.&nbsp; If this option 
            is used, the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#iconSize');">Icon Size</a>
                
            (below) must also be specified.
         </td>
      </tr>

      <tr class="even" id="iconSize">
         <td class="rprt tablePrompt">
            Hypertext Icon Size:
         </td>
         <td>
            <?php
               $sizes = array( "16x16" => "16 x 16",
                               "24x24" => "24 x 24",
                               "32x32" => "32 x 32" );
               echo BuildDropDownList( 'size',
                                       $sizes,
                                       TRUE );
            ?>
         </td>
         <td class="tableHeader">  <!-- Status -->
            <?php
               TextOrIconStatus( "size", "Optional" );
            ?>
         </td>
         <td>
            The size of the hypertext link icon.&nbsp; If this option
            is used, the 
            
            <a href="javascript:void(0);" 
                onclick="HighlightRow('#iconType');">Icon Type</a>
                
            (above) must also be specified.
         </td>
      </tr>

      </tbody>
   </table>

   </form>

   <p /> <br />

<?php
   if ( strlen( $_REQUEST['script'] ) &&
        strlen( $_REQUEST['dbid'] ) &&
        strlen( $_REQUEST['dbfield'] ) &&
        strlen( $_REQUEST['pid'] ) &&
        strlen( $_REQUEST['pfield'] ) )  // required fields
   {
      $imageStr = "";

      if ( strlen( $_REQUEST['icon'] ) &&
           strlen( $_REQUEST['size'] ) )  // image specified
      {
         $imageStr = sprintf( "<img src=\"%s/images/%s/%s\"
      border=\"0\" />",
              dirname( $_REQUEST['script'] ),
              $_REQUEST['size'],
              $_REQUEST['icon'] );
      }

      $dblabelKeyValue = "";
      
      if ( strlen( $_REQUEST['dblabel'] ) > 0 )
      {
         $dblabelKeyValue = sprintf( "&dblabel=%d", $_REQUEST['dblabel'] );
      }
      
      $textStr = sprintf(
"<input style=\"text\" name=\"%s\" /> <br />", 
              $_REQUEST['pfield'] );

      $htmlStr = sprintf(
"<div  id=\"%s\"
      style=\"display: none;\"></div>

   %s<a href=\"javascript:void(0)\"
      title=\"%s\"
      onclick=\"javascript:$('#%s').load('%s?dbfield=%s&dblabel=%s&title=%s&body=%s&dbid=%d&pfield=%s&pid=%d');\">%s%s</a>",
              $_REQUEST['pfield'],
              $_REQUEST['fieldNote'],
              $_REQUEST['hoverText'],
              $_REQUEST['pfield'],
              $_REQUEST['script'],
              $_REQUEST['dbfield'],
              // $dblabelKeyValue,
              $_REQUEST['dblabel'],
              // urlencode( $_REQUEST['title'] ),
              // urlencode( $_REQUEST['body'] ),
              rawurlencode( $_REQUEST['title'] ),
              rawurlencode( $_REQUEST['body'] ),
              $_REQUEST['dbid'],
              $_REQUEST['pfield'],
              $_REQUEST['pid'],
              $_REQUEST['hyperText'],
              $imageStr );
?>
   <form name="display">

   <table class="dt2" style="font-family:Verdana;font-size:11px;">
      <tbody>
      <tr class="grp2" colspan="2">
         <td class="rprt tablePrompt">
            &nbsp;
         </td>
         <td style="text-align: center;">
            Autocomplete Code Results
         </td>
      </tr>

      <tr class="odd">
         <td class="rprt tablePrompt">
            Autocomplete Preview:
         </td>
         <td>
            <?php echo $textStr ?>
            <?php echo $htmlStr ?>
         </td>
      </tr>

      <tr class="even">
         <td class="rprt tablePrompt">
            Copy and Paste Code:
         </td>
         <td>
            <textarea name="body"
                      cols="120"
                      rows="10"
                      readonly="readonly"><?php echo $htmlStr ?></textarea>
         </td>
      </tr>
      </tbody>
   </table>

   </form>

   <p /> <br />
<?php
   }
?>

<?php
   // Display the project footer
   require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';
?>
