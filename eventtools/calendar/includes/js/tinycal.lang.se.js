/**
 * copyright (c) 2008-2009 Kjell-Inge Gustafsson kigkonsult
 * www.kigkonsult.se/tinycal/index.php
 * ical@kigkonsult.se
 * updated 20081022
 *
 * tinycal Swedish language file
 *
 * Feel free to translate to other languages, please mail a copy.
 * Please use HTML character entity equivalents except for //-// rows
**/
function tclang(key, index, weekStart ) {
  switch (key) {
    case 'about' :             return 'Om tinycal.. .';     break;
    case 'attendee' :          return 'deltagare';          break;
    case 'categories' :        return 'kategori';           break;
    case 'class' :             return 'klass';              break;
    case 'close' :             return 'stäng';              break; //-//
    case 'comment' :           return 'kommentar';          break;
    case 'contact' :           return 'kontakt';            break;
    case 'completed' :         return 'klar';               break;
    case 'created' :           return 'skapad';             break;
    case 'day' :               return 'dag';                break; //-//
    case 'download' :          return 'ladda ner ';         break; //-//
    case 'description' :       return 'beskrivning';        break;
    case 'dtend' :             return 'slut';               break; // not used
    case 'dtstamp' :           return 'tidsst&auml;mpel';   break; // not used
    case 'dtstart' :           return 'start';              break; // not used
    case 'duration' :          return 'varaktighet';        break; // not used
    case 'last_modified' :     return 'sen.&auml;ndr.';     break;
    case 'list' :              return 'lista';              break; //-//
    case 'loading' :           return 'Laddar.. .';         break;
    case 'location' :          return 'plats';              break;
    case 'month' :             return 'månad';              break; //-//
    case 'next' :              return 'nästa';              break; //-//
    case 'open' :              return 'öppna';              break;
    case 'order' :             return 'ordn.';              break;
    case 'organizer' :         return 'organisat&ouml;r';   break;
    case 'percent' :           return 'procent';            break;
    case 'previous' :          return 'föregående';         break; //-//
    case 'priority' :          return 'prioritet';          break;
    case 'priorityText' : /** Priority : HIGH 1-4, MEDIUM 5, LOW 6-9 */
      var prioTextKey = new Array(null,'H&Ouml;G','H&Ouml;G','H&Ouml;G','H&Ouml;G','MEDIUM','L&Aring;G','L&Aring;G','L&Aring;G','L&Aring;G');
      return prioTextKey[index];
      break;
    case 'recurrent' :         return '&aring;terkommande'; break;
    case 'resources' :         return 'resurser';           break;
    case 'summary' :           return 'sammanfattning';     break;
    case 'time' :              return 'tid';                break;
    case 'transp' :            return 'transparent';        break;
    case 'uid' :               return 'unikt&nbsp;id';      break;
    case 'url' :               return 'url';                break;
    case 'week' :              return 'vecka';              break; //-//
    case 'wDaysArr' :
      var weekStart = weekStart || 0;
      if(1 != weekStart)
        var wDaysArr  = new Array('&nbsp;','s&ouml;','m&aring;','ti','on','to','fr','l&ouml;');
      else
        var wDaysArr  = new Array('&nbsp;','m&aring;','ti','on','to','fr','l&ouml;','s&ouml;');
      return wDaysArr[index];
      break;
    case 'wMonth' :
      var wMonth = new Array('jan','feb','mar','apr','maj','jun','jul','aug','sept','okt','nov','dec');
      return wMonth[index];
      break;
    default:                   return '';                 break;
  }
}
            /** Priority color signals: HIGH (1-4)=>red, MEDIUM (5)=>yellow, LOW (6-9) => none */
var tcPrioColors  = new Array(null,'#ff0000','#ff0000','#ff0000','#ff0000','#ee9a00',null,null,null,null);
// var tcPrioColors  = new Array(null,null,null,null,null,null,null,null,null,null);
