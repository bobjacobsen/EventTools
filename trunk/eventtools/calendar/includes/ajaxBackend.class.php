<?php
/**
 * tinycal v2.2
 *
 * copyright (c) 2008-2009 Kjell-Inge Gustafsson kigkonsult
 * www.kigkonsult.se/tinycal/index.php
 * ical@kigkonsult.se
 * updated 20090120
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
 * Ajax PHP backend class
 *
**/
class ajaxBackend {
  var $config;
  var $calendar;
  var $error;
  var $message;
  var $param;
  var $starttime;
  var $total;
  function ajaxBackend ( & $input, $setup=array( 'LOGLEVEL' => 0 )) {
            /** initiate */
    $this->starttime = microtime( TRUE );
    $this->error     = FALSE;
    $this->message   = array();
    $this->config    = $setup;
    if( $this->config['TCALLOWGZIP'] &&
        isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) && 
      ( FALSE !== strpos( strtolower( $_SERVER['HTTP_ACCEPT_ENCODING'] ), 'gzip' )))
      $this->config['HTTP_ACCEPT_ENCODING'] = $_SERVER['HTTP_ACCEPT_ENCODING'];
    ksort( $this->config );
    $message         = 'call identifier: '.$input['tcid']."\n                    from:".$_SERVER['REMOTE_ADDR'];
    if( 3 <= $this->config['LOGLEVEL'] ) $message .= ' ('.gethostbyaddr( $_SERVER['REMOTE_ADDR'] ).")\n                    ".$_SERVER['HTTP_USER_AGENT'];
    $this->addMessage( date( 'H:i:s Y-m-d', $this->starttime ), "tinycal getdata Server class, $message" );
    if( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  setup: ', $this->config);
    if( empty( $input['tcid'] )) {
      $this->addMessage( ' missing call identifier!!!' );
      $this->error   = TRUE;
      $this->logg();
      exit();
    }
            /** get all parameters */
    $this->fixinput( $input );
    $this->total       = 0;
    $this->calendar    = new vcalendar();
    if( !empty( $this->config['TCUNIQUEID'] ))
      $this->calendar->setConfig( 'unique_id', $this->config['TCUNIQUEID'] );
    if( $this->error ) {
      $this->export();
      exit();
    }
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' __exec: '.number_format(( microtime( TRUE ) - $this->starttime ), 6 ), 'totInit end' );
            /** manage the call */
    if( 3 <= $this->config['LOGLEVEL'] ) $fcnStart3 = microtime( TRUE );
    if( ctype_digit( $this->param['tcid'] )) {
            /** load a new calendar from FILE in input parameter list And TCCALDIR folder */
      if( isset( $this->config['TCCALDIR'] ) && is_dir( $this->config['TCCALDIR'] )) {
        if(     !$this->calendar->setConfig( 'directory', $this->config['TCCALDIR'] )) {
          $this->addMessage( ' ERROR (1) when setting directory, check permissions!! '.$this->config['TCCALDIR'] );
          $this->error = TRUE;
        }
        elseif( !$this->calendar->setConfig( 'filename', $this->param['d1'] )) {
          $this->addMessage( ' ERROR (2) when setting file, check permissions!! '.$this->param['d1'] );
          $this->error = TRUE;
        }
        elseif( !$this->calendar->parse()) {
          $this->addMessage( ' ERROR (3) in parse!! ' );
          $this->error = TRUE;
        }
      }
      else {
        $this->addMessage( ' ERROR (4) missing calendar directory!! ' );
        $this->error = TRUE;
      }
    }
    elseif( in_array( substr( $this->param['tcid'], 0, 1 ), array( 'r', 'w' ))) {
            /** load a webcal/remote calendar; protocol (webcal:// or) http:// */
      $external = $this->param['d1'];
      $external = str_replace('HTTP',   'http', $external );
      $external = str_replace('WEBCAL', 'http', $external );
      $external = str_replace('webcal', 'http', $external );
      if( 'http://' != strtolower( substr( $external, 0, 7 )))
        $external = 'http://'.$external;
      $this->fixwebcal( $external );
      if( !$this->error && !$this->calendar->setConfig( 'directory', $this->config['TCCACHE'] )) {
        $this->addMessage( ' ERROR (6) when setting cache directory, check permissions!! '.$this->config['TCCACHE'] );
        $this->error = TRUE;
      }
      else
        $filename = basename( $this->param['d1'] );
      if( !$this->error && !$this->calendar->setConfig( 'filename', $filename )) {
        $this->addMessage( ' ERROR (7) when setting cache file name, check permissions!! '.$this->param['d1'] );
        $this->error = TRUE;
      }
      if( !$this->error && !$this->calendar->parse()) {
        $this->addMessage( ' ERROR (8) in parse!! ' );
        $this->error = TRUE;
      }
    }
    else {
      $this->addMessage( ' ERROR (9) unvalid call identifier: '.$this->param['tcid'] );
      $this->error = TRUE;
    }
    if( $this->error ) {
      $this->logg();
      exit();
    }
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' ..exec: '.number_format(( microtime( TRUE ) - $fcnStart3 ), 6 ), 'calendar file read and parse' );
    if( '1' == $this->param['download'] ) {
      $this->download();
      exit();
    }
    $this->componentSelect();
    if( isset( $this->param['download'] )) {
      $this->download();
      exit();
    }
    $this->export();
    exit();
  }
  function addMessage( $content, $data=null, $extra='' ) {
            /** add data to message array */
    if( is_array( $data )) {
      foreach( $data as $k => $v ) {
        if( !empty( $v ))
          $content .= "$k='$v' ";
      }
    }
    elseif( !empty( $data ))
      $content .= " $data";
    $this->message[] = $content.$extra;
  }
  function componentFix( & $comp ) {
    if(( FALSE !== ( $date = $comp->getProperty( 'dtstart' ))) &&  isset( $date['tz'] ) &&  ( 'Z' == $date['tz'] ))
      $this->componentFixDate( $comp, 'dtstart', $date );
    if(( FALSE !== ( $date = $comp->getProperty( 'dtend' )))   &&  isset( $date['tz'] ) &&  ( 'Z' == $date['tz'] ))
      $this->componentFixDate( $comp, 'dtend', $date );
    if(( FALSE !== ( $date = $comp->getProperty( 'due' )))     &&  isset( $date['tz'] ) &&  ( 'Z' == $date['tz'] ))
      $this->componentFixDate( $comp, 'due', $date );
            /** remove alarm components, can't fix alarm components in the javascript.. . */
    $valarmcnt = 0;
    while( $comp->deleteComponent( 'valarm' )) {
      $valarmcnt++;
      if( 3 <= $this->config['LOGLEVEL'] ) {
        $uid = $comp->getProperty( 'uid' );
        $this->addMessage( '   del alarm '."($valarmcnt) ".$uid );
      }
    }
  }
  function componentFixDate( & $comp, $propName, $indate ) {
            /** conv. UTC datetime to local datetime */
    $localdate = mktime( $indate['hour'], $indate['min'], ($indate['sec'] + $this->config['TCLOCALOFFSET']), $indate['month'], $indate['day'], $indate['year'] );
    $comp->setProperty( $propName, array( 'timestamp' => $localdate ));
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( '    cnvDate :', implode('-',$indate).' - > '.date("Y-m-d-H-m-s",$localdate).', (Z='.date('Z')." $propName)");
  }
  function componentSelect() {
    if( 3 <= $this->config['LOGLEVEL'] ) $fcnStart = microtime( TRUE );
    if( !empty( $this->param['UID'] )) {
      $uidHitDate = mktime ( 0, 0, 0, $this->param['frM'], ($this->param['frD'] ), $this->param['frY'] );
      if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' uidHitDate: ', $this->param['UID'].' '.date("Ymd", $uidHitDate ));
    }
            /** count totals */
    $compsinfo = $this->calendar->getConfig( 'compsinfo');
    $dtstart   = FALSE;
    $this->calendar->sort();
    foreach( $compsinfo as $cix => $compinfo) {
            /** remove wrong component type */
      if( $this->param['comptype'] != $compinfo['type'] ) {
        $this->calendar->deleteComponent( $compinfo['type'], $compinfo['ordno'] );
        if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' compdel ', $compinfo['type'].' '.$compinfo['ordno'] );
        continue;
      }
      $this->total++;
            /** if previous, get first component dtstart */
      if( !$dtstart && !empty( $this->param['prev'] )) {
        if( FALSE === ( $comp = $this->calendar->getComponent( $compinfo['uid'] ))) {
          if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' geterr2 ', $compinfo['uid']." ordno=$ordno" );
          continue; // ????????????
        }
        $dtstart = $comp->getProperty( 'dtstart' );
        $this->param['frY'] = $dtstart['year'];
        $this->param['frM'] = $dtstart['month'];
        $this->param['frD'] = $dtstart['day'];
        ksort( $this->param );
        if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage('prevstDate: ',$this->param['frY'].'-'.$this->param['frM'].'-'.$this->param['frD']);
      }
    }
    if( !isset( $this->param['download'] )) {
            /** if NOT download, set order and max parameter as x-property */
      $ordno     = 0;
      $compsinfo = $this->calendar->getConfig( 'compsinfo');
      foreach( $compsinfo as $cix => $compinfo) {
        if( FALSE === ( $comp = $this->calendar->getComponent( $compinfo['uid'] ))) {
          if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' geterr2 WARN (21) ', $compinfo['uid']." ordno=$ordno" );
          continue; // ????????????
        }
        $ordno += 1;
        $comp->setProperty( 'X-SORT', $ordno.'*'.$this->total);
        $this->calendar->setComponent( $comp, $compinfo['uid'] );
      }
    }
            /** fix search parameters */
    if(( !empty( $this->param['next'] ) || !empty( $this->param['prev'] )) && empty( $this->param['cnt'] ))
       $this->param['cnt'] = 1;
    if( !empty( $this->param['cnt'] ) || !empty( $this->param['UID'] )) {
      $this->param['toY']     = $this->param['frY'] + 1;
      $this->param['toM']     = $this->param['frM'];
      $this->param['toD']     = $this->param['frD'];
      ksort( $this->param );
    }
    if( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' SEL-fix ', $this->param );
            /** select all hits within time and split them */
/* iCalcreator feature/bug.. . ??
    $components = $this->calendar->selectComponents( $this->param['frY'], $this->param['frM'], $this->param['frD']
                                                   , $this->param['toY'], $this->param['toM'], $this->param['toD'] );
*/
    $sfr = mktime( 0, 0, 0, (int) $this->param['frM'], ((int) $this->param['frD']) - 1, (int) $this->param['frY'] );
    $sto = mktime( 0, 0, 0, (int) $this->param['toM'], ((int) $this->param['toD']) + 1, (int) $this->param['toY'] );
    $components = $this->calendar->selectComponents( (int) date( 'Y', $sfr ), (int) date( 'm', $sfr ), (int) date( 'd', $sfr )
                                                   , (int) date( 'Y', $sto ), (int) date( 'm', $sto ), (int) date( 'd', $sto ));
            /** if empty result, return */
    if( empty( $components )) {
      while( $this->calendar->deleteComponent( $this->param['comptype'] ))
        continue;
      if( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' SELout1 ', 'tot=0 out-cnt=0');
      if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' .exec1: '.number_format(( microtime( TRUE ) - $fcnStart ), 6 ), 'componentSelect End' );
      return;
    }
            /** set up a new, empty calendar */
    $directory      = $this->calendar->getConfig( 'directory' );
    $filename       = $this->calendar->getConfig( 'filename' );
    if( !empty( $this->param['download'] )) {
      if( FALSE === ( $publish = $this->calendar->getProperty( 'publish' )))
        $publish    = 'PUBLISH';
      $xprops = array();
      while( $xprop = $this->calendar->getProperty())
        $xprops[$xprop[0]] = $xprop[1];
    }
    $this->calendar = new vcalendar();
    if( !empty( $this->config['TCUNIQUEID'] ))
      $this->calendar->setConfig( 'unique_id', $this->config['TCUNIQUEID'] );
    $this->calendar->setConfig( 'directory', $directory);
    $this->calendar->setConfig( 'filename',  $filename);
    if( !empty( $this->param['download'] )) {
      $this->calendar->setProperty( 'publish', $publish );
      foreach( $xprops as $key => $value )
        $this->calendar->setProperty( $key, $value );
    }
            /** fix hour order within day */
    foreach( $components as $yix => $year_arr ) {
     if( 3 <= $this->config['LOGLEVEL'] ) $s = '';
     foreach( $year_arr as $mix => $month_arr ) {
      foreach( $month_arr as $dix => $day_arr ) {
       if( 3 <= $this->config['LOGLEVEL'] ) $s .= ( empty( $s )) ? " ymd-Hno  $yix M$mix D$dix=" : "\n ymd Hno  $yix M$mix D$dix=";
       $hour_arr = array();
       foreach( $day_arr as $comp ) {
         if( $dtstart = $comp->getProperty( 'x-current-dtstart' ))
           $dtstart = $comp->_date_time_string( $dtstart[1] );
         else
           $dtstart = $comp->getProperty( 'dtstart' );
         if(( $dtstart['year'] != $yix ) || ( $dtstart['month'] != $mix ) || ( $dtstart['day'] != $dix ) || empty( $dtstart['hour'] ))
           $hour = 0; // i.e. not startdate
         else
           $hour = (int) $dtstart['hour'];
         if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage(' hourOrd ',"$yix.$mix.$dix.$hour (".implode('.', $dtstart).') '.substr($comp->getProperty('summary'),0,10));
         $hour_arr[$hour][] = $comp;
       } // end day_arr
       ksort( $hour_arr );
       $components[$yix][$mix][$dix] = $hour_arr;
       if( 3 <= $this->config['LOGLEVEL'] ) foreach($hour_arr as $k=>$v) $s.="H$k:".count($v).' ';
      } // end month_arr
      if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( $s );
     }  // end year_arr
    }   // end components
            /** exact select of components */
    $dateStart = mktime ( 0, 0, 0, $this->param['frM'], $this->param['frD'], $this->param['frY'] );
    $dateEnd   = mktime ( 0, 0, 0, $this->param['toM'], $this->param['toD'], $this->param['toY'] );
    $nextA  = $prevA = array();
    $next   = $prev = $cnt  = FALSE;
    foreach( $components as $yix => $year_arr ) {
     foreach( $year_arr as $mix => $month_arr ) {
      foreach( $month_arr as $dix => $day_arr ) {
       $currentDay = date("Ymd", mktime ( 0, 0, 0, $mix, $dix, $yix ));
        if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage( "      currentDay: $currentDay" );
       foreach( $day_arr as $hix => $hour_arr ) {
        foreach( $hour_arr as $comp ) {
         if( $currentDay < date("Ymd", $dateStart)) {
           if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' low-del ', date("Ymd",$dateStart)." > $currentDay ".substr($comp->getProperty('summary'),0,10));
           continue;
         }
         elseif( $currentDay > date("Ymd",$dateEnd)) {
           if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' highdel ', date("Ymd",$dateEnd)." < $currentDay ".substr($comp->getProperty('summary'),0,10));
           break 5;
         }
         $comp->setProperty( 'X-CURRENT-DAY', $currentDay );
         $dtstart = mktime ( 0, 0, 0, $mix, $dix, $yix );
            // if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' date ok ', $uid );
            /** stop if reached date + break point */
         if( empty( $this->param['UID'] ) && !empty( $this->param['cnt'] ) &&
            ( !$cnt || ( $cnt && $cnt < $this->param['cnt'] ))) {
           $cnt += 1;
           if( 3 <= $this->config['LOGLEVEL'] ) $uid = $comp->getProperty( 'uid' );
           $this->componentFix( $comp );
           $this->calendar->setComponent( $comp );
           if( 3 <= $this->config['LOGLEVEL'] )   $this->addMessage( '  cntSel ', "cnt=$cnt $uid $currentDay ".substr($comp->getProperty('summary'),0,10) );
           if( $cnt >= $this->param['cnt'] )
             break 5;
           continue;
         }
         if( $this->param['UID'] ) {
            /** get first component with UID as key and the next ($cnt - 1) components */
           if( !empty( $this->param['cnt'] ) && empty( $this->param['prev'] ) && empty( $this->param['next'] )) {
             $uid = $comp->getProperty( 'uid' );
             //  if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' UIDcCHK ', $this->param['UID'].' - '.$uid. " cnt=$cnt" );
             if(( $cnt && $cnt < $this->param['cnt'] ) || ( !$cnt && $this->param['UID'] == $uid )) {
               $cnt += 1;
               $this->componentFix( $comp );
               $this->calendar->setComponent( $comp );
               if( 3 <= $this->config['LOGLEVEL'] )   $this->addMessage( ' UIDcSel ', $uid." cnt=$cnt ".substr($comp->getProperty('summary'),0,10) );
               continue;
             }
             elseif( $cnt && $cnt >= $this->param['cnt'] ) { // skip all other components
               if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' UIDcBrk ', $compinfo['uid']." cnt=$cnt ".substr($comp->getProperty('summary'),0,10) );
               break 5;
             }
             continue;
           }
            /** get exact/prev/next component(-s) with UID as match key */
           if( $next ) {
             $uid = $comp->getProperty( 'uid' );
             $nextA[] = array( $comp, $uid, $dtstart );
             if ( count( $nextA ) < ( 2 * $this->param['cnt'] ))
              continue;
             else
               break 5;
           } // --end-- if( $next )
           $uid = $comp->getProperty( 'uid' );
           if(( $uidHitDate == $dtstart ) && ( $this->param['UID'] == $uid )) { // match on UID -AND- UID dtstart date
             if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' UID+date match', $uid.' '.date("Ymd", $dtstart ).' '.substr($comp->getProperty('summary'),0,10));
             if( $this->param['next'] ) {
               $next = TRUE;
               $nextA[] = array( $comp, $uid, $dtstart );
               continue;
             }
             elseif( !empty( $this->param['prev'] )) { // if prev is set, then read from $prevA and leave
               $cnt = 0;
               while( $comp = array_shift( $prevA )) {
                 $cnt++;
                 $this->componentFix( $comp[0] );
                 $this->calendar->setComponent( $comp[0] );
                 if( 3 <= $this->config['LOGLEVEL'] )   $this->addMessage( ' prevSel ', "cnt=$cnt ".$comp[1].' '.date("Ymd", $comp[2] ).' '.substr($comp[0]->getProperty('summary'),0,10));
               }
               break 5;
             }
             else {
               $this->componentFix( $comp );
               $this->calendar->setComponent( $comp );
               if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage('  UIDsel ',$uid.' '.date("Ymd", $dtstart).' '.substr($comp->getProperty('summary'),0,10));
               break 5;
             }
           }
           if( !empty( $this->param['prev'] )) { // ev. save previous components
             $prevA[] = array( $comp, $uid, $dtstart );
             while(( !empty( $this->param['cnt'] )) && ( count( $prevA ) > $this->param['cnt'] )) // remove pre-previous components
               array_shift( $prevA );
           }
         }
         else {
           if( 3 <= $this->config['LOGLEVEL'] ) $uid = $comp->getProperty( 'uid' );
           $this->componentFix( $comp );
           $this->calendar->setComponent( $comp );
           if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage(' normSel ', $uid.' '.date("Ymd", $dtstart).' '.substr($comp->getProperty('summary'),0,10));
         }
        } // end hour_arr
       }  // end day_arr
      }   // end month_arr
     }    // end year_arr
    }    // end components
            /** if next is TRUE, read from nextA array */
    if( $next && ( $this->param['cnt'] < count( $nextA ))) {
      for( $cnt = 0; $cnt < $this->param['cnt']; $cnt++ )
        $comp = array_shift( $nextA );
      $cnt = 0;
      while( $comp = array_shift( $nextA )) {
        $cnt++;
        $this->componentFix( $comp[0] );
        $this->calendar->setComponent( $comp[0] );
        if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage(' nextSel ',"cnt=$cnt ".$comp[1].' '.date("Ymd", $comp[2]).' '.substr($comp[0]->getProperty('summary'),0,10));
      }
    } // --end-- unload $nextA
//       if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage( '   comp  '.serialize( $comp )); // test ###
    if( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' SELout2 ', 'tot='.$this->total.' out-cnt='.count($this->calendar->components));
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' ..exec: '.number_format(( microtime( TRUE ) - $fcnStart ), 6 ), 'componentSelect End' );
  }
  function download() {
    if( 3 <= $this->config['LOGLEVEL'] ) $fcnStart = microtime( TRUE );
           /** remove duplicates (opt, from componentSelect) */
    if( '1' != $this->param['download'] ) { // not total download
      $dupCheck  = $remArray = array();
      $cntComps  = $remComps = $tremProps = 0;
      $compsinfo = $this->calendar->getConfig( 'compsinfo');
      foreach( $compsinfo as $cix => $compinfo) {
        $uidOrdno = $compinfo['uid']." ordno=".$compinfo['ordno'];
        $cntComps++;
        if( in_array( $compinfo['uid'], $dupCheck )) {
/*4*/     if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  dlddel MSG1    ', $uidOrdno );
          $remArray[$compinfo['ordno']] = $compinfo['uid'];
          continue;
        }
        else { // no duplicate, save uid in array
          $dupCheck[] = $compinfo['uid'];
          if( FALSE === ( $comp = $this->calendar->getComponent( $compinfo['ordno'] ))) { // get on ordno parameter
            if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  dldget ERR (31)', $uidOrdno );
          }
          elseif( !empty( $compinfo['props']['X-PROP'] )) { // fetched, remove opt. x-props
/*4*/       if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  dldget MSG2    ', "$uidOrdno x-props=".$compinfo['props']['X-PROP'] );
            $remProps = 0;
            if( $comp->deleteProperty( 'X-CURRENT-DAY' ))
              ++$remProps;
            if( $comp->deleteProperty( 'X-CURRENT-DTSTART' ))
              ++$remProps;
            if( $comp->deleteProperty( 'X-CURRENT-DTEND' ))
              ++$remProps;
            if( $comp->deleteProperty( 'X-CURRENT-DUE' ))
              ++$remProps;
/*4*/       if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  dldset MSG3    ', "$uidOrdno remProps=$remProps" );
            if( 0 < $remProps ) {
              $tremProps += $remProps;
              $this->calendar->setComponent( $comp, $compinfo['ordno'] );  // set on ordno parameter (=replace)
            }
          }
        }
      } // end compsinfo
      if( !empty( $remArray )) {
        krsort( $remArray );
        foreach( $remArray as $ordno => $uid ) {
/*4*/     if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  dlddel MSG4    ', $uid." ordno=".$ordno );
          if( FALSE === $this->calendar->deleteComponent( $ordno )) // delete on ordno parameter in reverse (!) order
            if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  dlddel ERR (33)', $uid." ordno=".$ordno );
        }
        $remComps = count( $remArray );
        $this->calendar->sort();
      }
      if( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage(" dldcnt: comps in=$cntComps, rem=$remComps out=".($cntComps - $remComps)." (rem x-current=$tremProps)");
    }
    if( 4 <= $this->config['LOGLEVEL'] ) {
      $output   = $this->calendar->createCalendar();
      $this->addMessage( $output );
    }
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' ..exec: '.number_format(( microtime( TRUE ) - $fcnStart ), 6 ), ' download function End' );
    if( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage( 'download:'.number_format(( microtime( TRUE ) - $this->starttime ), 6 ), 'tinycal getdata Server class');
    $this->logg();
           /** return data */
    $filename = $this->calendar->getConfig( 'filename' );
    $output   = $this->calendar->createCalendar();
    $filezise = strlen( $output );
    if( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] )) {
      $output   = gzencode( $output, 9 );
      $filezise = strlen( $output );
      header( 'Content-Encoding: gzip');
      header( 'Vary: *');
    }
    header( 'Content-Type: text/calendar; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
    header( 'Cache-Control: max-age=10' );
    header( 'Content-Length: '.$filezise );
    echo $output;
    exit();
  }
  function export() {
    if( 3 <= $this->config['LOGLEVEL'] ) $fcnStart = microtime( TRUE );
           /** check scope and, if scope not is 'c' (comp), remove properties  */
    if( !empty( $this->calendar->components )) {
      if( 2 <= $this->config['LOGLEVEL'] ) $fcnStart2 = microtime( TRUE );
      if( 'c' == $this->param['scope'] ) { // component view
           /** if scope is 'c' (component), store url as x-url, can't fix ENTITY in javascript + fix duration->dtend */
        if( FALSE === ( $comp = $this->calendar->getComponent())) { // get the only component within the scope
          if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' exp-url ERR (41)' );
        }
        else {
          if( FALSE !== ( $url = $comp->getProperty( 'url' ))) // reset url as x-prop
            $comp->setProperty( 'X-URL', $url );
          if((( FALSE === ( $dstart = $comp->getProperty( 'x-current-dtend' ))) &&
              ( FALSE === ( $dstart = $comp->getProperty( 'x-current-due' )))) &&
              ( $dur = $comp->getProperty( 'duration' ))) { // reset duration as dtend/due
            if( $dtstart = $comp->getProperty( 'x-current-dtstart' ))
              $dtstart = $comp->_date_time_string( $dtstart[1] );
            else
              $dtstart = $comp->getProperty( 'dtstart' );
            $endDate = $comp->duration2date( $dtstart, $dur );
            $comp->setProperty( 'x-current-dtend', $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['min'].':'.$endDate['sec'] );
            $comp->deleteProperty( 'duration' );
          }
          $this->calendar->setComponent( $comp, 1 ); // set on ordno parameter (=replace)
          if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' exp-url ', "url=$url, uid= ".$comp->getProperty( 'uid' ) );
        }
      }
      else { // 'c' != $this->param['scope']
           /** if scope is not 'c' (comp), remove properties  */
        $okProps = array( 'UID', 'DTSTAMP', 'DTSTART', 'X-PROP' );
        if( 'm' != $this->param['scope'] )
          $okProps = array_merge( $okProps,  array( 'SUMMARY', 'PRIORITY' ));
        if( 'l' == $this->param['scope'] )
          $okProps = array_merge( $okProps, array( 'DTEND', 'DUE', 'DURATION', 'URL', 'LOCATION', 'RESOURCES', 'CATEGORIES' ));
        $compsinfo = $this->calendar->getConfig( 'compsinfo');
        $ccnt = $pcntr = $pcnta = 0;
        foreach( $compsinfo as $cix => $compinfo) {
          $ccnt += 1;
          if( FALSE === ( $comp = $this->calendar->getComponent( $compinfo['ordno'] ))) { // get on ordno parameter
            if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' expget1 ERR (44)', $compinfo['uid']." ordno=".$compinfo['ordno'] );
            continue; // ????????????
          }
          foreach( $compinfo['props'] as $propName => $cnt ) { // remove properties
            if( in_array( $propName, $okProps ))
              $pcnta += $cnt;  // increase accepted count
            else {
              while( $comp->deleteProperty( $propName ))
                $pcntr++; // increase removed count
            }
          }
          if(( 'l' == $this->param['scope'] ) &&   // if from list view, reset duration as x-current-dtend
            (( FALSE === ( $dstart = $comp->getProperty( 'x-current-dtend' ))) &&
             ( FALSE === ( $dstart = $comp->getProperty( 'x-current-due' )))) &&
             ( $dur = $comp->getProperty( 'duration' ))) {
            if( $dtstart = $comp->getProperty( 'x-current-dtstart' ))
              $dtstart = $comp->_date_time_string( $dtstart[1] );
            else
              $dtstart = $comp->getProperty( 'dtstart' );
            $endDate = $comp->duration2date( $dtstart, $dur );
            $comp->setProperty( 'x-current-dtend', $endDate['year'].'-'.$endDate['month'].'-'.$endDate['day'].' '.$endDate['hour'].':'.$endDate['min'].':'.$endDate['sec'] );
          }
          if( $comp->deleteProperty( 'duration' )) {
            $pcnta--; // reduce accepted count
            $pcntr++; // increase removed count
          }
          if(( 'l' == $this->param['scope'] ) && ( FALSE !== ( $url = $comp->getProperty( 'url' )))) // url to x-prop
            $comp->setProperty( 'X-URL', $url );
          $this->calendar->setComponent( $comp, $compinfo['ordno'] ); // set on ordno parameter (=replace)
        }
        if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  exprem ', "TOT comps:$ccnt acceptedProps:$pcnta removedProps:$pcntr" );
      }
      if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  .exec: '.number_format(( microtime( TRUE ) - $fcnStart2 ), 6 ), 'remove properties' );
    }
           /** set XML format */
    $this->calendar->setConfig( 'format', 'xcal' );
    $filename        = $this->calendar->getConfig( 'filename' );
    if( 'ics' == substr( $filename, -3 ))
      $filename      = substr( $filename, 0, -3 ).'xml';
    $output          = $this->calendar->createCalendar();
    $filezise        = strlen( $output );
    if( 4 <= $this->config['LOGLEVEL'] ) $this->addMessage( $output );
    if( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage( '...exec: '.number_format(( microtime( TRUE ) - $this->starttime ), 6 ), 'tinycal getdata Server class');
    $this->logg();
           /** return data */
    if( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] )) {
      $output   = gzencode( $output, 9 );
      $filezise = strlen( $output );
      header( 'Content-Encoding: gzip');
      header( 'Vary: *');
    }
    header( 'Content-type: text/xml; charset=UTF-8');
    header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
    header( 'Cache-Control: max-age=10' );
    header( 'Content-Length: '.$filezise );
    echo $output;
    exit();
  }
  function fixinput( $input ) {
    if( 3 <= $this->config['LOGLEVEL'] ) $fcnStart = microtime( TRUE );
            /** remove all unwanted parameters */
    $okKeys = array('tcid','scope','comptype','d1','UID','download','prev','next','cnt','frY','frM','frD','toY','toM','toD');
    foreach( $input as $key => $value ) {
      if( !in_array ( $key, $okKeys ))
        unset( $input[$key] );
    }
    if( 2 <= $this->config['LOGLEVEL'] ) { ksort( $input ); $this->addMessage( ' input1: ', $input ); };
            /** get all parameters */
    $this->param = array();
    $this->param['tcid']      = ( isset( $input['tcid'] ))     ?       $input['tcid']     : 1;
    $this->param['scope']     = ( isset( $input['scope'] ))    ?       $input['scope']    : 'f';
    $this->param['comptype']  = ( isset( $input['comptype'] )) ?       $input['comptype'] : 'vevent';
    $this->param['d1']        = ( isset( $input['d1'] ))       ?       $input['d1']       : null;
    $this->param['UID']       = ( isset( $input['UID'] ))      ?       $input['UID']      : null;
    $this->param['download']  = ( isset( $input['download'] )) ?       $input['download'] : null;
    $this->param['prev']      = ( isset( $input['prev'] ))     ?       $input['prev']     : null;
    $this->param['next']      = ( isset( $input['next'] ))     ?       $input['next']     : null;
    $this->param['cnt']       = ( isset( $input['cnt'] ))      ? (int) $input['cnt']      : null;
    $this->param['frY']       = ( isset( $input['frY'] ))      ? (int) $input['frY']      : null;
    $this->param['frM']       = ( isset( $input['frM'] ))      ? (int) $input['frM']      : null;
    $this->param['frD']       = ( isset( $input['frD'] ))      ? (int) $input['frD']      : null;
    $this->param['toY']       = ( isset( $input['toY'] ))      ? (int) $input['toY']      : null;
    $this->param['toM']       = ( isset( $input['toM'] ))      ? (int) $input['toM']      : null;
    $this->param['toD']       = ( isset( $input['toD'] ))      ? (int) $input['toD']      : null;
            /** check filename parameter */
    if( !empty( $this->param['d1'] )&& ctype_digit( $this->param['tcid'] )) {
      if( isset( $this->config['TCCALDIR'] ) && is_dir( $this->config['TCCALDIR'] ))
        $file = $this->config['TCCALDIR'].DIRECTORY_SEPARATOR.$this->param['d1'];
      else
        $file = $this->param['d1'];
      if ( !is_file( $file ) || !is_readable( $file )) {
        if ( is_file( $file.'.ics' ) && is_readable( $file.'.ics' ))
          $this->param['d1'] .= '.ics';
        elseif ( is_file( $file.'.ICS' ) && is_readable( $file.'.ICS' ))
          $this->param['d1'] .= '.ICS';
        else {
          $this->addMessage( ' ERROR (51) no file OR unreadable (local) parameter file : '.$file );
          $this->error = TRUE;
          return;
        }
      }
      clearstatcache();
    }
            /** check startdate parameter */
    if( $this->param['frY'] && $this->param['frM'] &&  $this->param['frD'] ) {
      if( !checkdate( $this->param['frM'], $this->param['frD'], $this->param['frY'] )) {
        $this->addMessage( ' ERROR (52) unvalid parameter startdate : '.$this->param['frY'].'-'.$this->param['frM'].'-'.$this->param['frD'] );
        $this->error = TRUE;
        return;
      }
    }
            /** check enddate parameter */
    if( $this->param['toY'] && $this->param['toM']&&  $this->param['toD'] ) {
      $date = mktime ( 0, 0, 0, $this->param['toM'], $this->param['toD'], $this->param['toY'] );
      $this->param['toY'] = date("Y", $date );
      $this->param['toM'] = date("m", $date );
      $this->param['toD'] = date("d", $date );
      if( !checkdate( $this->param['toM'], $this->param['toD'], $this->param['toY'] )) {
        $this->addMessage( ' ERROR (53) unvalid parameter enddate : '.$this->param['toY'].'-'.$this->param['toM'].'-'.$this->param['toD'] );
        $this->error = TRUE;
        return;
      }
    }
    ksort( $this->param );
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' ..exec: '.number_format(( microtime( TRUE ) - $fcnStart ), 6 ), 'fixinput End' );
  }
  function fixwebcal( $external ) {
    if( 3 <= $this->config['LOGLEVEL'] ) $fcnStart = microtime( TRUE );
            /** fix wabcal, cache file localy at first call, then after every $this->config['TCTIMEOUT'] sec */
    $this->remFiles();
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  webcal "'.$external.'"' );
            /** set local file name */
    $tmp = str_replace( '/', '_', substr( $external, 7 )); // skip prefix and replace '/'
    if(( '.' == substr( $tmp, -4, 1 )) && 'ics' != substr( $tmp, -3 ))
      $tmp .= 'ics';
    if( 150 < strlen( $tmp ))
      $tmp = trim( substr( $tmp, -150 ));
    $this->param['d1'] = $this->config['TCCACHE'].DIRECTORY_SEPARATOR.$tmp;
    if( !is_file( $this->param['d1'] )) {
            /** chache webcal file localy, speeding upp all but first call */
      if( FALSE === ( $remoteContent = @file_get_contents( $external ))) {
            /** trying to fetch webcal file but unreachable */
        $out  = "\n  error:";
        $tmp  = error_get_last();
        foreach( $tmp as $k => $v ) $out .= "\n   $k->$v";
        $out .= "\n  url:";
        $purl  = parse_url( $external );
        foreach( $purl as $k => $v ) $out .= "\n  $k->$v  ";
          // scheme - e.g. http, host, port, user, pass, path, query - after the question mark ?,fragment - after the hashmark # 
        $this->addMessage( "  ERROR (61), webcal, ($external) unreachable, $out" );
        $this->error = TRUE;
        return;
      }
      elseif( empty( $remoteContent )) {
            /** webcal file empty */
        $this->addMessage( '  ERROR (62), webcal, "'.$external.'" empty or unable to fetch' );
        $this->error = TRUE;
        return;
      }
      elseif( $fp = fopen( $this->param['d1'], 'a' )) {
            /** fix carriage return, opt icalcreator bug? */
        $remoteContent = str_replace( "\r\n",  "\n", $remoteContent );
            /** cache local copy of webcal file */
        fwrite( $fp, $remoteContent );
        fclose( $fp );
        if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  webcal downloaded to local cache as  "'.$this->param['d1'].'" size:'.filesize($this->param['d1']) );
      }
      else {
            /** unable to create local file */
        $this->addMessage( '  ERROR (63) , webcal, failed to create file "'.$this->param['d1'],'"' );
        $this->error = TRUE;
        return;
      }
      clearstatcache();
    }
            /** check local file */
    if( !is_file( $this->param['d1'] ) || !is_readable( $this->param['d1'] )) {
      $this->addMessage( '  ERROR, webcal (64), no file or unreadable downloaded (webcal) file: '.$this->param['d1'] );
      $this->error = TRUE;
    }
    elseif( 2 <= $this->config['LOGLEVEL'] ) $this->addMessage( '  webcal used  "'.$this->param['d1']."\"\n      as local copy of \"".$external.'"' );
    clearstatcache();
    if( 3 <= $this->config['LOGLEVEL'] ) $this->addMessage( ' ..exec: '.number_format(( microtime( TRUE ) - $fcnStart ), 6 ), 'fixwebcal End' );
  }
  function logg() {
    if( 1 > $this->config['LOGLEVEL'] ) return;
    if(( 1 == $this->config['LOGLEVEL'] ) && !$this->error ) return;
    if( $this->error )
      $this->addMessage( "..........ERROR ERROR ERROR..........\n" );
    if( empty( $this->message )) return;
            /** print messages into file, log/testing */
    if( !isset( $this->config['LOGFILE'] ) || empty( $this->config['LOGFILE'] ))
      return;
            /** what to to do when.. .?? */
    if( !is_file( $this->config['LOGFILE'] ))
      touch( $this->config['LOGFILE'] );
    $fp = fopen( $this->config['LOGFILE'], 'a' );
    foreach( $this->message as $row )
      fwrite( $fp, $row."\n" );
    fclose( $fp );
  }
  function remFiles() {
            /** remove all old iCal files in dir TCCACHE, older than TCTIMEOUT secs */
    if( !isset( $this->config['TCTIMEOUT'] ) || ( 0 >= $this->config['TCTIMEOUT'] ))
      return;
    if( !isset( $this->config['TCCACHE'] )) {
           /** unset or unvalid cache directory */
      $this->addMessage( '  ERROR (81) "TCCACHE" is missing! tcid='.$this->param['tcid'] );
      $this->error = TRUE;
      return;
    }
    elseif( !is_dir( $this->config['TCCACHE'] )) {
           /** unset or unvalid cache directory */
      $this->addMessage( '  ERROR (82) "'.$this->config['TCCACHE'].'" is no directory!! tcid='.$this->param['tcid'] );
      $this->error = TRUE;
      return;
    }
    clearstatcache();
           /** create removal datetime */
    $rmDateTime  = mktime (date('G'), date('i'), date('s') - $this->config['TCTIMEOUT'], date("n") ,date("d"), date("Y"));
           /** iterate directory and ics files, check candidates for removal */
    $dir = new DirectoryIterator( $this->config['TCCACHE'] );
    foreach( $dir as $file ) {
      $dirfilename = $this->config['TCCACHE'].DIRECTORY_SEPARATOR.$file->getFilename();
      if(( '.' != substr( $dirfilename, -1 )) && ( '.' != substr( $dirfilename, 0, 1 )) && $file->isFile() &&
         (( '.ics' == strtolower( substr( $dirfilename, -4 ))) ||
          ( '.xml' == strtolower( substr( $dirfilename, -4 ))))) {
        $filemTime = $file->getMTime();
        if( $filemTime < $rmDateTime ) {
          $res = @unlink( $dirfilename );
          if( 3 <= $this->config['LOGLEVEL'] ) {
            if( $res )
              $this->addMessage( "    del: '".$dirfilename.'" removed, filemtime ('.date( 'H:i:s Y-m-d', $filemTime ).') < remDate('.date( 'H:i:s Y-m-d', $rmDateTime).')');
            else
              $this->addMessage( "    WARN (83) del: unable to remove '".$dirfilename);
          }
        }
      }
    }
  }
}
?>