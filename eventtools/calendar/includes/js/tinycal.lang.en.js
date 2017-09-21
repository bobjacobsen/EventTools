/**
 * copyright (c) 2008-2009 Kjell-Inge Gustafsson kigkonsult
 * www.kigkonsult.se/tinycal/index.php
 * ical@kigkonsult.se
 * updated 20081022
 *
 * tinycal English language file
 *
 * Feel free to translate to other languages, please mail a copy.
 * Please use HTML character entity equivalents except for //-// rows
**/
function tclang(key, index, weekStart ) {
  switch (key) {
    case 'about' :         return 'About tinycal.. .'; break;
    case 'attendee' :      return 'attendee';          break;
    case 'categories' :    return 'categories';        break;
    case 'class' :         return 'class';             break;
    case 'close' :         return 'close';             break; //-//
    case 'comment' :       return 'comment';           break;
    case 'contact' :       return 'contact';           break;
    case 'completed' :     return 'completed';         break;
    case 'created' :       return 'created';           break;
    case 'day' :           return 'day';               break; //-//
    case 'description' :   return 'decription';        break;
    case 'download' :      return 'download ';         break; //-//
    case 'dtend' :         return 'end';               break; // not used
    case 'dtstamp' :       return 'timestamp';         break; // not used
    case 'dtstart' :       return 'start';             break; // not used
    case 'duration' :      return 'duration';          break; // not used
    case 'last_modified' : return 'modified';          break;
    case 'list' :          return 'list';              break; //-//
    case 'loading' :       return 'Loading.. .';       break;
    case 'location' :      return 'location';          break;
    case 'month' :         return 'month';             break; //-//
    case 'next' :          return 'next';              break; //-//
    case 'open' :          return 'open';              break;
    case 'order' :         return 'order';             break;
    case 'organizer' :     return 'organizer';         break;
    case 'percent' :       return 'percent';           break;
    case 'previous' :      return 'previous';          break; //-//
    case 'priority' :      return 'priority';          break;
    case 'priorityText' :
      var prioTextKey = new Array(null,'HIGH','HIGH','HIGH','HIGH','MEDIUM','LOW','LOW','LOW','LOW');
      return prioTextKey[index];
      break;
    case 'recurrent' :     return 'recurrent';         break;
    case 'resources' :     return 'resources';         break;
    case 'summary' :       return 'summary';           break;
    case 'time' :          return 'time';              break;
    case 'transp' :        return 'transparent';       break;
    case 'uid' :           return 'unique&nbsp;id';    break;
    case 'url' :           return 'url';               break;
    case 'week' :          return 'week';              break; //-//
    case 'wDaysArr' :
      var weekStart = weekStart || 0;
      if(1 != weekStart)
        var wDaysArr  = new Array('&nbsp;','su','mo','tu','we','th','fr','sa');
      else
        var wDaysArr  = new Array('&nbsp;','mo','tu','we','th','fr','sa','su');
      return wDaysArr[index];
      break;
    case 'wMonth' :
      var wMonth = new Array('jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');
      return wMonth[index];
      break;
    default:               return '';                  break;
  }
}
            /** Priority color signals: HIGH (1-4)=>red, MEDIUM (5)=>yellow, LOW (6-9) => none */
var tcPrioColors  = new Array(null,'#ff0000','#ff0000','#ff0000','#ff0000','#ee9a00',null,null,null,null);
// var tcPrioColors  = new Array(null,null,null,null,null,null,null,null,null,null);
