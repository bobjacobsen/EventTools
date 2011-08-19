<?php
/**
 * tinycal v2.2
 *
 * copyright (c) 2008-2009 Kjell-Inge Gustafsson kigkonsult
 * www.kigkonsult.se/tinycal/index.php
 * ical@kigkonsult.se
 * updated 20081222
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
 * Ajax backend PHP script
 *
**/
            /** set backend setup (required) parameters */
$setup = array(
  'LOGLEVEL'       => 1           // 0 (none)                                                        // default
                                  // 1 (only error msg, if any)                                      // catch opt. errors only
                                  // 2 (every call = input_params+output_params+exec_time + err msg) // check usage
                                  // 3 (dito + action reports)                                       // TESTING
                                  // 4 (all)                                                         // T E S T I N G ! !
 ,'LOGFILE'        =>  'tinycal.log' // if LOGLEVEL > 0, a fixed log file name
                                  // date( 'Ymd' ).'.log'  // ex. a daily log file name
                                  // date( 'YW' ).'.log'   // ex. a weekly log file name
                                  // date( 'Ym' ).'.log'   // ex. a monthly log file name
 ,'TCCALDIR'       => 'calendars' // folder for all local calendar files
 ,'TCCACHE'        => 'cache'     // cache directory, write grants needed; speeding up webcal
 ,'TCTIMEOUT'      => 3600        // seconds, renew cached remote files efter TCTIMEOUT sec (i.e. delete old files)
 ,'TCUNIQUEID'     => 'ical.net'  // site unique id, used when parsing calendar files
 ,'TCALLOWGZIP'    => TRUE        // if request headers accepts gzip, output is gziped (TRUE) to increase performance
                                  //  otherwise, or if web server don't allow gzip, set to FALSE
 ,'TCLOCALOFFSET'  => date( 'Z' ) // used when converting UTC datetimes to local datetimes (dtstart, dtend, due)
);

            /** include requirements */
//require_once 'includes'.DIRECTORY_SEPARATOR.'createTestfile.php'; // opt., check existing and/or create a new test file
require_once 'includes'.DIRECTORY_SEPARATOR.'iCalcreator.class.php';
require_once 'includes'.DIRECTORY_SEPARATOR.'ajaxBackend.class.php';
            /** execute */
$backEnd = new ajaxBackend( $_REQUEST, $setup );
exit()
?>
