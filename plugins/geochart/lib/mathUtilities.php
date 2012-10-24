<?php
/**
 * @brief General purpose math utilities
 *
 * @file mathUtilities.php
 * $Revision: 196 $
 * $Author: fmcclurg $
 * @author Fred R. McClurg, University of Iowa
 * $Date:: 2012-10-11 10:56:54 #$
 * @see http://rosettacode.org/wiki/Averages/Median#PHP
 * $URL: https://srcvault.icts.uiowa.edu/repos/REDCap/REDCap/tags/geochart-1.0/lib/mathUtilities.php $
 */

/**
 * @brief Calculates the median value of an array of values
 *
 * @param  $numbers Array of values
 * @retval $median The median value of the array
 */
function Median( $numbers )
{
   sort( $numbers );
   $count = count( $numbers );  //count the number of values in array
   $middleVal = floor(($count - 1) / 2); // find the middle value, or the lowest middle value

   if ($count % 2)  // odd number, middle is the median
   {
      $median =  $numbers[$middleVal];
   }
   else  // even number, calculate avg of 2 medians
   {
      $low =  $numbers[$middleVal];
      $high =  $numbers[$middleVal + 1];
      $median = (($low + $high) / 2);
   }

   return( $median );

}  // Median()
