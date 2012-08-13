<?php
   /**
    * @brief Wizard page that builds the custom autocomplete plugin code
    *
    * @file wizard.php
    * $Revision: 164 $
    * $Author: fmcclurg $
    * $Date:: 2012-08-13 13:41:05 #$: Date of commit
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

<?php
   /*
   <!-- CSS Style Sheets -->
   <link href="jquery/css/smoothness/jquery-ui-1.8.20.custom.css" type="text/css" rel="stylesheet" />

   <!-- JavaScript Functions -->
   <script src="jquery/js/jquery-1.7.2.min.js" type="text/javascript" type="text/javascript"></script>
   <script src="jquery/js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
   */
?>

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

<?php
   /*
   <script type="text/javascript">
      $(document).ready(function()
      {
         alert( 'jQuery installed' );
      });
   </script>
   */
?>
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
            The location of the autocomplete script on the server.
         </td>
      </tr>

      <tr class="odd">
         <td class="rprt tablePrompt">
            Database Project (Source):
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

      <tr class="even">
         <td class="rprt tablePrompt">
            DB Field Returned (Source):
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
            The variable name of the source database text field 
            that will be used to populate the REDCap target project 
            field.&nbsp; This field contains all the possible values 
            that could be selected for insertion into the target 
            project field.&nbsp; When the field "DB Field Label" 
            (below) is <em><strong>not specified</strong></em>, this 
            field will also serve as the label for the autocompletion 
            list items.
         </td>
      </tr>

      <tr class="odd">
         <td class="rprt tablePrompt">
            DB Field Label, if different from field returned (Source):
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
            Physician's ID).&nbsp;  By specifying the "DB Field 
            Label", you are able to select a physician's name and 
            their corresponding ID will be entered into your 
            project.&nbsp; The "DB Field Label" 
            <strong><em>must be</em></strong> used in conjunction 
            with the "DB Field Returned" field (above).
         </td>
      </tr>

      <tr class="even">
         <td class="rprt tablePrompt">
            Project Name (Target):
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

      <tr class="odd">
         <td class="rprt tablePrompt">
            Project Field (Target):
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
            receive the autocompletion value.
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

      <tr class="odd">
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
            The pop-up help displayed upon hover of the hypertext 
            link.
         </td>
      </tr>

      <tr class="even">
         <td class="rprt tablePrompt">
            Hypertext Icon:
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
            The icon of the hypertext link.
         </td>
      </tr>

      <tr class="even">
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
            The size of the hypertext link icon.
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
      
      $htmlStr = sprintf(
"<input style=\"text\" name=\"%s\" /> <br />

<div id=\"%s\"
     style=\"display: none;\"></div>

   %s<a href=\"javascript:void(0)\"
      title=\"%s\"
      onclick=\"javascript:$('#%s').load('%s?dbfield=%s&dblabel=%s&title=%s&body=%s&dbid=%d&pfield=%s&pid=%d');\">%s%s</a>",
              $_REQUEST['pfield'],
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
            Example:
         </td>
         <td>
            <?php echo $htmlStr ?>
         </td>
      </tr>

      <tr class="even">
         <td class="rprt tablePrompt">
            Code:
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
