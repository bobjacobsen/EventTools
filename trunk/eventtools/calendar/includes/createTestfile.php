<?php
/**
 * tinycal v2.2
 *
 * copyright (c) 2008-2009 Kjell-Inge Gustafsson kigkonsult
 * www.kigkonsult.se/tinycal/index.php
 * ical@kigkonsult.se
 * updated 20090106
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * create test iCal file, eight days from today, each with three events
 *
**/
$starttime = microtime( TRUE );
            /* include iCalcreator */
require_once 'includes'.DIRECTORY_SEPARATOR.'iCalcreator.class.php';
            /* site unique id */
DEFINE( 'UNIQUE',   'ical.net' );
            /* folder for test file, opt. using previous settings in getdata.php */
$caldir = ( isset( $setup['TCCALDIR'] )) ? $setup['TCCALDIR'] : 'calendars';
DEFINE( 'CALDIR',   $caldir );
            /* name of calendar file */
DEFINE( 'TESTFILE', 'testFile.ics' );
            /* startdate */
DEFINE( 'THISDATE',  date( 'Ymd' ));
//DEFINE( 'THISDATE',  date( 'Ymd', mktime( 0, 0, 0, 12, 27, 2008 ))); // new year test
            /* Some (MS) calendar definitions */
DEFINE( 'METHOD',   'PUBLISH' );
DEFINE( 'CALNAME',  'testFile' );
DEFINE( 'CALDESC',  'Calendar test file' );
DEFINE( 'TIMEZONE', 'Europe/Stockholm' );

$dirFile = CALDIR.DIRECTORY_SEPARATOR.TESTFILE;
            /* get errorlevel using previous settings in getdata.php */
$errLevel = ( isset( $GLOBALS['setup']['LOGLEVEL'] )) ? $GLOBALS['setup']['LOGLEVEL'] : 0;
            /* initiate log */
$logmessage = array( array( $errLevel, date( 'H:i:s Y-m-d', $starttime ).' createTestFile start' ));
            /* add messages to log */
function addLogEntry( $errLevel, $logEntry, $starttime=FALSE, $endtime=FALSE ) {
  $delta = ( $starttime && $endtime ) ? ' '.number_format(( $endtime - $starttime ), 4 ).' sec' : null;
  if( isset( $GLOBALS['setup']['LOGLEVEL'] ) && 
       ( 0 < $GLOBALS['setup']['LOGLEVEL'] ) && 
           ( $GLOBALS['setup']['LOGLEVEL'] >= $errLevel ))
    $GLOBALS['logmessage'][] = array( $errLevel, "$logEntry$delta" );
}
            /* logging result, using previous settings in getdata.php */
function writeLog() {
  if( isset( $GLOBALS['setup']['LOGLEVEL'] )   && ( 0  < $GLOBALS['setup']['LOGLEVEL'] )) {
    if(( 2 == count( $GLOBALS['logmessage'] )) && ( 1 == $GLOBALS['setup']['LOGLEVEL'] )) return;
    if( isset( $GLOBALS['setup']['LOGFILE'] )  && ( $fp = fopen( $GLOBALS['setup']['LOGFILE'], 'a' ))) {
      foreach( $GLOBALS['logmessage'] as $m ) {
        if( $GLOBALS['setup']['LOGLEVEL'] >= $m[0] )
          fwrite( $fp, $m[1]."\n" );
      }
    }
    fclose( $fp );
  }
}
            /* create and save to disk, a calendar with 3 events every day for eight days from today */
function createTestFile() {
  $dirFile = CALDIR.DIRECTORY_SEPARATOR.TESTFILE;
  $calendar = new vcalendar();
  $calendar->setConfig(   'unique_id',     UNIQUE );
  if( !$calendar->setConfig('directory',   CALDIR )) {
    addLogEntry( 1, '  ERROR (11) when setting directory \''.CALDIR.'\', check directory/file permissions!!' );
    return FALSE;
  }
  elseif( !$calendar->setConfig('filename',  TESTFILE )) {
    addLogEntry( 1, "  ERROR (12) when setting directory/file '$dirFile', check directory/file permissions!!" );
    return FALSE;
  }
  $calendar->setProperty( 'METHOD',        METHOD );
  $calendar->setProperty( 'X-WR-CALNAME',  CALNAME );
  $calendar->setProperty( 'X-WR-CALDESC',  CALDESC );
  $calendar->setProperty( 'X-WR-TIMEZONE', TIMEZONE );
  $date       = mktime( 0, 0, 0, (int) substr( THISDATE, 4, 2 ), (int) substr( THISDATE, 6, 2 ), (int) substr( THISDATE, 0, 4 ));
  $stopDate   = $date + (7*24*3600);
  $eventCount = 1;
            // random priority, 1 to 9. HIGH (1-4), MEDIUM (5), LOW (6-9)
            // reversed prio; HIGH: weight 1, MEDIUM weight 4, LOW: weight 8
  $prioArr = array();
  for( $r=1; $r<=9; $r++) {
    $weight = ( 5 < $r ) ? 1 : ( 5 > $r ) ? 8 : 4;
    for( $r1=1; $r1<=$weight; $r1++)
      $prioArr[] = $r;
  }
  mt_srand();
  shuffle( $prioArr );
            // array to randomly select a summary from
  $summaries = array( 'Duis ac dui sit amet ante auctor euismod.'
                    , 'Suspendisse_pellentesque_velit_in_tortor.'
                    , 'Mauris vulputate.'
                    , 'Nulla sapien pede, dapibus sed.'
                    , 'Maecenas tristique, pede_id_sollicitudin_posuere, enim nibh mollis odio.'
                    , 'Lorem ipsum dolor sit amet, consectetuerAdipiscingElit.' );
  while( $date <= $stopDate ) {
    $dayCount  = 1;
    while( $dayCount < 4 ) {
      $event   = new vevent();
      $eventdate = $date + ( mt_rand( 7, 18 ) * 3600 );           // random start hour, 7 to 18
      $event->setProperty( 'DTSTART',    array( 'timestamp' => $eventdate ));
            // random duration, 1-4 hours or 2 days
      if( 9 > mt_rand( 1, 9 ))
        $event->setProperty( 'DURATION', 0, 0, mt_rand( 1, 4 )); // 1-4 hours duration
      else
        $event->setProperty( 'DURATION', 0, 2);                  // 2 days duration
      $event->setProperty( 'SUMMARY',    "Event #$eventCount. ".$summaries[mt_rand( 0, 5 )] );
      $event->setProperty( 'CATEGORIES', "Category #$eventCount" );
      $event->setProperty( 'LOCATION',   "Location #$eventCount" );
      $event->setProperty( 'RESOURCES',  "Resource #$eventCount" );
      $event->setProperty( 'ORGANIZER',  "chair.$eventCount@".UNIQUE );
      $event->setProperty( 'CONTACT',    "contact.$eventCount@".UNIQUE );
      $event->setProperty( 'DESCRIPTION', 'Lorem ipsum dolor sit amet, '
                                         .'consectetuer adipiscing elit. '
                                         .'Mauris vulputate. Suspendisse '
                                         .'pellentesque velit in tortor. '
                                         .'Nulla sapien pede, dapibus sed.' );
            // random priority, 1 to 9. HIGH (1-4), MEDIUM (5), LOW (6-9)
      $event->setProperty( 'PRIORITY',   $prioArr[mt_rand( 0, ( count( $prioArr ) - 1 ))]);
            // two attendees every event
      $event->setProperty( 'ATTENDEE',   'attendee.'.$eventCount.'.1@'.UNIQUE );
      $event->setProperty( 'ATTENDEE',   'attendee.'.$eventCount.'.2@'.UNIQUE );
            // two comments every event
      $event->setProperty( 'COMMENT',    'Duis ac dui sit amet ante auctor euismod. Sed vulputate.' );
      $event->setProperty( 'COMMENT',    'Maecenas tristique, pede id sollicitudin posuere, enim nibh mollis odio.' );
      $event->setProperty( 'CREATED',    array( 'timestamp' => ($eventdate - (2*24*3600)))); // fake two days before event startdate
      $event->setProperty( 'LAST-MODIFIED', array( 'timestamp' => ($eventdate - (23*3600)))); // fake 25 hours before event startdate
      if( 5 > $eventCount ) {
        $event->setProperty( 'RRULE',  array( 'FREQ'     => 'DAILY' // every third day, i.e. also day 3, 6, 9
                                            , 'COUNT'    => 4
                                            , 'INTERVAL' => 3 ));
        $event->setProperty( 'EXRULE', array( 'FREQ'     => 'DAILY' // exclude 3rd occurence, i.e. day 6
                                            , 'COUNT'    => 2
                                            , 'INTERVAL' => 6 ));
      }
      $event->setProperty( 'URL', 'http://www.kigkonsult.se/tinycal/index.php' );
      if( FALSE === $calendar->setComponent( $event ))
        error_log('setComponent error');
      $eventCount++;
      $dayCount++;
    }
    $date += (24*3600);
  }
  $calendar->sort();
  if( FALSE === $calendar->saveCalendar())
    addLogEntry( 1, "  ERROR (13) saving calendar file '$dirFile'" );
}
            /* check if testfile is to be recreated (after 24h) */
$createFileStatus = FALSE;
if( TRUE === ( $createFileStatus = !is_file( $dirFile )))
  addLogEntry( 3, "  '$dirFile' file is missing" );
else {
  $calendar = new vcalendar();
  $calendar->setConfig(   'unique_id',      UNIQUE );
  if( !$calendar->setConfig('directory',    CALDIR ))
    addLogEntry( 1, '  ERROR (1) when setting directory \''.CALDIR.'\', check directory/file permissions!!' );
  elseif( !$calendar->setConfig('filename', TESTFILE ))
    addLogEntry( 1, "  ERROR (2) when setting directory/file '$dirFile', check directory/file permissions!!" );
  else {
    $starttime2 = microtime( TRUE );
    $createFileStatus = !$calendar->parse();
    $exec = array( ', Parse exec time:', $starttime2, microtime( TRUE ));
    if( FALSE !== $createFileStatus)
      addLogEntry( 2, '  FALSE from calendar->parse, i.e. file missing or empty'.$exec[0], $exec[1], $exec[2] );
    else {
      addLogEntry( 3, '  TRUE from calendar->parse'.$exec[0], $exec[1], $exec[2] );
      $starttime2 = microtime( TRUE );
      $comp = $calendar->getComponent( 'vevent', 1 );
      $exec = array( ', exec time:', $starttime2, microtime( TRUE ));
      if( FALSE === $comp ) {
        $createFileStatus = TRUE;
        addLogEntry( 2, '  FALSE from calendar->getComponent, no vevent in file'.$exec[0], $exec[1], $exec[2] );
      }
      else {
        addLogEntry( 3, '  TRUE from calendar->getComponent'.$exec[0], $exec[1], $exec[2] );
        $starttime2 = microtime( TRUE );
        $dtstart = $comp->getProperty( 'dtstart' );
        $exec = array( ', exec time:', $starttime2, microtime( TRUE ));
        if( FALSE === ( $dtstart = $comp->getProperty( 'dtstart' ))) {
          $createFileStatus = TRUE;
          addLogEntry( 2, '  FALSE from (first) getProperty(DTSTART), no DTSTART in vevent component'.$exec[0], $exec[1], $exec[2] );
        }
        else {
          $fileDtstartDate = sprintf("%04d%02d%02d", $dtstart['year'], $dtstart['month'], $dtstart['day'] );
          addLogEntry( 3, "  $fileDtstartDate = (first) getProperty(DTSTART)".$exec[0], $exec[1], $exec[2] );
          if( THISDATE > $fileDtstartDate ) {
            $createFileStatus = TRUE;
            addLogEntry( 2, "  Date in dtstart:$fileDtstartDate older than ".THISDATE.' (startdate)' );
          }
          else
            addLogEntry( 3, "  Date in dtstart:$fileDtstartDate >= ".THISDATE.' (startdate) = OK' );
        }
      }
    }
  }
}
addLogEntry( 2, " init + file '$dirFile' evaluation exec time:", $starttime, microtime( TRUE ));
if( FALSE !== $createFileStatus ) {
  $starttime2 = microtime( TRUE );
  createTestFile();
  addLogEntry( 1, " create file '$dirFile' exec time:", $starttime2, microtime( TRUE ));
}
addLogEntry( $errLevel, 'createTestFile exec time:', $starttime, microtime( TRUE ));
writeLog();
?>