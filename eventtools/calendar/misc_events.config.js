/**
 * tinycal v2.2
 *
 * copyright (c) 2008-2009 Kjell-Inge Gustafsson kigkonsult
 * www.kigkonsult.se/tinycal/index.php
 * ical@kigkonsult.se
 * updated 20090105
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
 * tinycal configuration javascript
 *
**/
                                     // UNIQUE obj. NAME (here 'tcrobj1')
                                     // and IDENTIFIER (here '1')
                                     // characters a-w,A-W and 0-9 allowed
var tcrobj1            = new tcobj('r');
                                     // use numeric identifier when reading
                                     // local files, ex. '1' (filename in
                                     // txData1 parameter below)
                                     //
                                     // use 'r' prefix when reading remote
                                     // files, ex. 'remote1' (set url without
                                     // http:// -prefix) in tcData1)
                                     //
                                     // use 'w' prefix when reading webcal
                                     // files, ex. 'webcal2' (set url without
                                     // http:// -prefix) in tcData1)

tcrobj1.tcData1        = "127.0.0.1/localeventtools/calendar_download.php?types=m";  // 'clg' is all
                                    // calendar filename WITHOUT .ics suffix
                                     // Opt remote url WITHOUT prefix ('http://'
                                     // or 'webcal://'), but WITH url suffix

tcrobj1.tccomptype     = 'vevent';   // calendar component type to display;
                                     // 'vevent'(def.) / 'vtodo' / 'vjournal'

                                     // tinycal Ajax PHP backend url without
                                     // 'http://' prefix
// tcrobj1.tcUrl          = '<server>/<path>/getdata.php';
                                     // or auto-conf.
tcrobj1.tcUrl          = location.href.substring(7,(location.href.lastIndexOf("/")+1))+'getdata.php';

                                     // SET allowed views to display.
                                     // Remove item from array to disallow
tcrobj1.tcAllowedViews = new Array('month', 'week', 'day', 'comp', 'list', 'download');
tcrobj1.tcStartView    = 'month';     // Start view [list/day/week/month (month default)]

                                     // SET allowed downloads, remove item to disallow
                                     // item must exist in tcAllowedViews
tcrobj1.tcAllowedDownloads = new Array('month', 'week', 'day', 'comp', 'list', 'total');

tcrobj1.tcCompProps = new Array();   // properties (and order) to display in component view, if allowed
                                     // to exclude an item, comment line
                                     // upper case:label + value, lower case: value only
tcrobj1.tcCompProps[1]  = 'time';          // (recurring) event start (date and) time
tcrobj1.tcCompProps[2]  = 'summary';       // event summary
//tcrobj1.tcCompProps[3]  = 'COMPLETED';     // todo completed
tcrobj1.tcCompProps[3]  = 'PRIORITY';      // event priority
tcrobj1.tcCompProps[4]  = 'DESCRIPTION';   // event description
tcrobj1.tcCompProps[5]  = 'COMMENT';       // event comments
tcrobj1.tcCompProps[6]  = 'URL';           // event url (for more info)
tcrobj1.tcCompProps[7]  = 'CONTACT';       // event contact
tcrobj1.tcCompProps[8]  = 'ORGANIZER';     // event organizer
tcrobj1.tcCompProps[9]  = 'ATTENDEE';      // event attendee
//tcrobj1.tcCompProps[7]   = 'PERCENT';       // the todo percent completion
//tcrobj1.tcCompProps[8]   = 'CLASS';         // event access classification
tcrobj1.tcCompProps[12] = 'LOCATION';      // event location
tcrobj1.tcCompProps[11] = 'RESOURCES';     // event resources
tcrobj1.tcCompProps[10] = 'CATEGORIES';    // event categories
//tcrobj1.tcCompProps[14]  = 'TRANSP';        // transparent or not to busy time searches
//tcrobj1.tcCompProps[15] = 'ORDER'          // event order in file
tcrobj1.tcCompProps[13] = 'RECURRENT';     // if recurrent, org start date
//tcrobj1.tcCompProps[15] = 'UID';           // event unique id
tcrobj1.tcCompProps[16] = 'CREATED';       // event created datetime
tcrobj1.tcCompProps[17] = 'LAST_MODIFIED'; // modification datetime, if date different from creation date

tcrobj1.tcListItems     = 4;         // if allowing 'list' view, number of items in list display section
tcrobj1.tcListProps = new Array();   // properties (and order) to display in list view, if allowed
                                     // to exclude an item, comment line
                                     // upper case:label + value, lower case: value only
tcrobj1.tcListProps[1]  = 'time';          // (recurring) event start (date and) time
tcrobj1.tcListProps[2]  = 'summary';       // event summary
tcrobj1.tcListProps[5]  = 'location';      // event location
//tcrobj1.tcListProps[6]  = 'resources';     // event resources
tcrobj1.tcListProps[7]  = 'categories';    // event categories
tcrobj1.tcListProps[3]  = 'PRIORITY';      // event priority
tcrobj1.tcListProps[99] = 'URL';           // event url (for more info) ALWAYS LAST, if included!!!

tcrobj1.tcColWidth     = 60          // if allowing 'comp', 'list' or 'day' view
                                     // max characters in a row, within tcdivStyle (width) below

tcrobj1.tcWeekStart    = 0;          // week start on 0=sunday, 1=monday

tcrobj1.tcDaystartHour = 7;          // First hour to display in day/week view,
                                     // min '1' !!

tcrobj1.tcDayendHour   = 23;         // Last hour to display in day/week view,
                                     // max '23' !!

                                     // SET header text
tcrobj1.tcheaderText   = 'X2011West'; // if no header, set empty or null

                                     // SET general border+width style
                                     // width required, reduce width by 2px
                                     // if border (thin=1+1) left+right used
tcrobj1.tcdivStyle     = 'border:gray solid thin;width:418px'; //1+1+218=220px
                                     // or no border at all
// tcrobj1.tcdivStyle      = 'border:none;width:220px';
                                     // or only top+bottom border
// tcrobj1.tcdivStyle      = 'border-top:gray solid medium;border-bottom:gray solid thin;width:220px';

                                     // SET header bgcolor+font style, used if tcheaderText is set
tcrobj1.tcheaderStyle  = 'background-color:silver;font:bold normal 100% serif';

                                     // SET menu layout bgcolour+border style
tcrobj1.tcmenuStyle    = 'background-color:#efefef;border-bottom:black solid thin;border-top:black solid thin'; 

                                     // SET label background colour
tcrobj1.tclabelStyle   = 'background-color:#efefef';

                                     // SET odd row bgcolour (!) style
tcrobj1.tcoddrowStyle  = 'background-color:silver'; // #efefef'; // InactiveCaption'; // ThreeDShadow';

tcrobj1.tctest         = 0;          // Test mode, dont't change this,
                                     // don't even think.. .

tcinit(tcrobj1);                     // fire up the box

