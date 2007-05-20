<?php

/**
 * 
 */
abstract class HTTP {
   /**
    * Return a date and time string that is conformant to RFC 2616
    * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.3
    *
    * @param int $time the unix timestamp of the date we want to return,
    *                empty if we want the current time
    * @return string a date-string conformant to the RFC 2616
    */
   static function getHttpDate($time='') {
      if ($time == '') {
         $time = time();
      }
      /* Use fixed list of weekdays and months, so we don't have to fiddle with locale stuff */
      $months = array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
		      '04' => 'Apr', '05' => 'May', '06' => 'Jun',
		      '07' => 'Jul', '08' => 'Aug', '09' => 'Sep',
		      '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
      $weekdays = array('1' => 'Mon', '2' => 'Tue', '3' => 'Wed',
		      '4' => 'Thu', '5' => 'Fri', '6' => 'Sat',
		      '0' => 'Sun');
      $dow = $weekdays[gmstrftime('%w', $time)];
      $month = $months[gmstrftime('%m', $time)];
      $out = gmstrftime('%%s, %d %%s %Y %H:%M:%S GMT', $time);
      return sprintf($out, $dow, $month);
   }
}

?>