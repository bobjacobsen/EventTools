tinycal v2.2
copyright (c) 2008-2009 Kjell-Inge Gustafsson, kigkonsult
www.kigkonsult.se/tinycal/index.php
ical@kigkonsult.se


DESCRIPTION
===========

tinycal is a small HTML calendar box, displaying calendar information from
local and/or remote calendar files or even urls, ex. Google calendar.

tinycal offers access to calendar information in month, week, day, list and
component views and offers also ablility to download a (complete, part of or
single event within) calendar file. tinycal is only 220px in width
(configurable), 'calendar-in-a-box'.

tinycal displays 'vevent' (default), 'vtodo' or 'vjournal' calendar component
types except any (included) 'valarm' components and supports recurring events
(component properties rdate, rrule etc).

More than one tinycal box can be showed on the same page, showing contents from
different sources.

tinycal is written completely in javascript and used in a HTML page as script-
tags, using Ajax technology and a PHP Axaj backend.

tinycal uses prototype 1.5.0 javascript framework (included within package) for
display and Axaj logics and iCalCreator (iCalcreator.class.php) (not included
within the download package) for managing calendar files by the Ajax backend PHP
script.


FUNCTIONALITY
=============

tinycal is preset to display, in the configured start view, the current date
(week) at initiation.

In the following, event(-s) represent 'vevent', 'vtodo' or 'vjournal' calendar 
component(-s).


The tinycal menu displays (in order left to right):
 arrow icon for the previous month / week / day / list section / event
 text for the month (and year) / week number (and year) / day date,
 arrow icon for the next month / week / day / list section / event
 icons for 'list', 'day', 'week' and 'month' view (all optional, configurable)


In the 'month' view, day cells with events are marked with bold characters.
Holding the mouse over a bold day date, the number of calendar components are
displayed. Clicking a day date number opens the 'day' view (if not disallowed),
clicking a week number in the week column the opens 'week' view (if not
disallowed).

The menu arrow icons offers ability to go to previous or next month.

The 'month' view is default (configurable) the start view. There is also an
overall option to disallow usage of the 'month' view.


In the 'week' view, every event is marked with a 'diamond' at the day/start hour
cell (if set, otherwise at the top). Holding the mouse over a 'diamond', the
component 'SUMMARY' property are displayed. Clicking a 'diamond' opens the
'component' view (if not disallowed). Clicking a day date number in the day date
row opens the 'day' view (if not disallowed).

The menu arrow icons offers ability to go to previous or next week.

The 'week' view is configurable to use as start view. There is also an overall 
option to disallow usage of the 'week' view.


In the 'day' view, every events 'SUMMARY' property are displayed at the event
start hour (if set or else as an 'All-Day-event', at the top in the day hour 
column), clicking text opens the 'component' view (if not disallowed).

The menu arrow icons offers ability to go to previous or next day.

The 'day' view is configurable to use as start view. There is also an overall
option to disallow usage of the 'day' view.


In the 'list' view, events are displayed in date/time order, day-by-day, with
configurable event content; properties (within a preset selection) to show,
property order and with or without label. Clicking an event opens the 
'component' view (if not disallowed). The calendar list is displayed in sections
(size configurable) for minimizing tinycal height.

The menu arrow icons offers ability to display previous or next list section.

The 'list' view is configurable to use as start view. There is also an overall
option to disallow usage of the 'list' view.


In the 'component' view, the event details are displayed with configurable
content; properties to show, property order and with or without label.

The menu arrow icons offers ability to display previous or next event.

There is an overall option to disallow usage of the 'component' view.


tinycal is configurable to allow downloads, total (whole calendar file) and
extracts within the scope of the current view, all default allowed. At the
bottom of the tinycal box and at the left side, there is an icon and text for
total download, and, at the right, text for the current view and icon. When
allowing download, ensure that each calendar file include METHOD property (ex.
'PUBLISH' value) and X-Properties and values for 'X-WR-CALNAME', 'X-WR-CALDESC'
and 'X-WR-TIMEZONE' to allow compatibility with calendar software (MS et.al.),
please check included 'createTestfile.php' for creation details.

If the event property 'PRIORITY' is used, a red 'diamond' in the 'week' view, a
red 'SUMMARY' text in the 'day' view and red underlined 'PRIORITY' in the 'list'
view is used to mark 'HIGH' priority (value 1-4), yellow colour is used to mark 'MEDIUM' priority (5). For 'LOW' priority (value 6-9) or no priority at all,
black is used in 'week' and 'day' view. Also the 'list' and 'component' view use
colour marks when displaying the 'PRIORITY' 'HIGH' and 'MEDIUM' property values.

Clicking the bottom row ('tinycal v2.2.. .') opens and closes information about
tinycal copyright, licence and components; namn, version, licence and link to
homepage.

Is it presumed that the the component property 'UID' (unique id) are set in ALL
calendar resources to ensure a unique component identity and proper sorting of
calendar components. If missing, iCalcreator is adding an 'UID' to every event.

Recurrent events are supported and are displayed in all days they occur.


INSTALL
=======

Unpack to any folder within the web server 'Dokument root'.

Download 'iCalcreator' from
"http://www.kigkonsult.se/downloads/index.php#iCalcreator"
and place in 'includes' directory.

Include the prototype framework javascript (v 1.5.0)
'<script type="text/javascript" 
src="http://-host-/-path-/tinycal/includes/js/prototype.js"></script>'
in the html page if not included elsewhere. Replace -host-/-path- to fit.

Include the tinycal function javascript
'<script type="text/javascript" 
src="http://-host-/-path-/tinycal/includes/js/tinycal.mini.js"></script>'
in the html page. Replace -host-/-path- to fit.

Include the tinycal language javascript
'<script type="text/javascript" 
src="http://-host-/-path-/tinycal/includes/js/tinycal.lang.en.js"></script>'
in the html page. Replace -host-/-path- to fit. English and Swedish available,
feel free to translate to other languages, please mail a copy.

Adapt the tinycal configuration javascript 'tinycal.config.js' (see FRONT END
PARAMETERS below) and include
'<script type="text/javascript" 
src="http://-host-/-path-/tinycal/tinycal.config.js"></script>'
in the html page where the tinycal box is to appear. Replace -host-/-path- to
fit. NB, the parameter  'tcData1' is to contain filename (for calendar file to
display) without 'ics' extension. For a remote file, webcal or URL resource
exclude prefix 'http://' or 'webcal://' BUT include extension.

For HTML example, examine tinycal.php.

Review and update parameters in 'getdata.php' (see BACK END PARAMETERS below)
- managing opt. log level and log file name
- folders for calendars and caching
- option for calendar testing is included


More than one tinycal box is possible in a single HTML page. Copy the script
'tinycal.config.js', adapt configuration (see FRONT END PARAMETERS below) and
save with a unique filename like 'tinycal.config.2.js' etc. Include
'<script type="text/javascript" 
src="http://-host-/-path-/tinycal/tinycal.config.2.js"></script>'
in the html page where the additional tinycal box is to appear. Replace
-host-/-path- to fit.

If the tinycal boxes is to appear next to each other (horizontally), additional
tinycal configuration(-s) can be placed in the same configuration file (ex.
'tinycal.config.js') and no additional 'script' tag is necessary.

NB, use a UNIQUE object NAME and IDENTIFIER in 'tinycal.config.*' for every
unique tinycal box!!!!!


If testing tinycal (running in 'test' mode, ex. using 'tinycal.php') and want a
test calendar file, configure 'createTestfile.php' (see TEST FILE PARAMETERS
below). The script creates a calendar file with 32 events, starting today and
within the next seven days, three events every day, including recurrent events.

This option is default enabled, when running in 'production' mode, remove or
comment the include line 
"require_once 'includes'.DIRECTORY_SEPARATOR.'createTestfile.php';"
in 'getdata.php'.


CONFIGURATION
=============

FRONT END PARAMETERS

Open 'tinycal.config.js' in an editor and adapt parameters.

NB, don't use vertical bar (|) character (hexadecimal 7C)!
Use a UNIQUE object NAME and IDENTIFIER in 'tinycal.config.*' for every unique
tinycal box!!!!!

                                     // UNIQUE obj. NAME (here 'tcrobj1')
                                     // and IDENTIFIER (here '32')
                                     // characters a-w,A-W and 0-9 allowed
tcrobj1                = new tcobj('32');
                                     // use numeric identifier when reading
                                     // local files, ex. '1' (put filename in
                                     // txData1 parameter below)
                                     //
                                     // use 'r' prefix when reading remote
                                     // files, ex. 'remote1' (set url without
                                     // http:// -prefix) in tcData1)
                                     //
                                     // use 'w' prefix when reading webcal
                                     // files, ex. 'webcal2' (set url without
                                     // webcal:// -prefix) in tcData1)

tcrobj1.tcData1        = 'calendar'; // calendar filename WITHOUT .ics suffix.
                                     // Opt remote url WITHOUT prefix ('http://'
                                     // or 'webcal://'), but WITH url suffix

tcrobj1.tccomptype     = 'vevent';   // calendar component type to display;
                                     // 'vevent'(def.) / 'vtodo' / 'vjournal'

                                     // tinycal Ajax PHP backend url without
                                     // 'http://' prefix (or auto-conf.)
tcrobj1.tcUrl          = '<host>/<path>/tinycal/getdata.php';

                                     // DEFINE defaults
                                     // allowed views to display, 
                                     // remove item from array to disallow
tcrobj1.tcAllowedViews =
                       new Array('month','week','day','comp','list','download');

tcrobj1.tcStartView    = 'month';    // Start view
                                     // [list/day/week/month] (month default)


                                     // allowed downloads, remove to disallow
                                     // item must exist in tcAllowedViews
tcrobj1.tcAllowedDownloads = 
                     new Array('month', 'week', 'day', 'comp', 'list', 'total');

tcrobj1.tcCompProps = new Array();   // Properties (and order) to display in
                                     // component view, if allowed.
                                     // To exclude an item, comment line.
                                     // upper case:label + value,
                                     // lower case: value only
tcrobj1.tcCompProps[1]  = 'time';          // (recurring) start (date+) time
tcrobj1.tcCompProps[2]  = 'SUMMARY';       // event summary
//tcrobj1.tcCompProps[3]  = 'COMPLETED';     // todo completed
tcrobj1.tcCompProps[3]  = 'PRIORITY';      // event priority
tcrobj1.tcCompProps[4]  = 'DESCRIPTION';   // event description
tcrobj1.tcCompProps[5]  = 'COMMENT';       // event comments
tcrobj1.tcCompProps[6]  = 'URL';           // event url (for more info)
tcrobj1.tcCompProps[7]  = 'CONTACT';       // event contact
tcrobj1.tcCompProps[8]  = 'ORGANIZER';     // event organizer
tcrobj1.tcCompProps[9]  = 'ATTENDEE';      // event attendee
//tcrobj1.tcCompProps[7]   = 'PERCENT';    // the todo percent completion
//tcrobj1.tcCompProps[8]   = 'CLASS';      // event access classification
tcrobj1.tcCompProps[12] = 'LOCATION';      // event location
tcrobj1.tcCompProps[11] = 'RESOURCES';     // event resources
tcrobj1.tcCompProps[10] = 'CATEGORIES';    // event categories
//tcrobj1.tcCompProps[14]  = 'TRANSP';     // transparant for busy time searches
tcrobj1.tcCompProps[16] = 'ORDER'          // event order in file
tcrobj1.tcCompProps[15] = 'RECURRENT';     // if recurrent, org start date
tcrobj1.tcCompProps[14] = 'UID';           // event unique id
tcrobj1.tcCompProps[13] = 'CREATED';       // event created datetime
tcrobj1.tcCompProps[17] = 'LAST_MODIFIED'; // modification datetime,
                                           // only if date > creation date

tcrobj1.tcListItems    = 5;          // if allowing 'list' view,
                                     // number of items in list display section
tcrobj1.tcListProps = new Array();   // Properties (and order) to display in
                                     // list view, if allowed.
                                     // To exclude an item, comment line.
                                     // upper case:label + value,
                                     // lower case: value only
tcrobj1.tcListProps[2]  = 'time';          // (recurring) start (date+) time
tcrobj1.tcListProps[3]  = 'summary';       // event summary
tcrobj1.tcListProps[5]  = 'location';      // event location
tcrobj1.tcListProps[6]  = 'resources';     // event resources
tcrobj1.tcListProps[4]  = 'categories';    // event categories
tcrobj1.tcListProps[1]  = 'PRIORITY';      // event priority
tcrobj1.tcListProps[99] = 'url';           // event url. If used, ALLWAYS LAST!!


tcrobj1.tcColWidth     = 25          // if allowing 'comp', 'list' or 'day' view
                                     // max characters in a row,
                                     // within tcdivStyle (width) below

tcrobj1.tcWeekstart    = 1;          // week start on 0=sunday, 1=monday

tcrobj1.tcDaystartHour = 8;          // First hour to display in day/week view,
                                     // min '1' !!

tcrobj1.tcDayendHour   = 17;         // Last hour to display in day/week view,
                                     // max '23' !!

                                     // SET header text
tcrobj1.tcheaderText   = '1 file calendar.ics';
                                     // if no header, empty or null

                                     // SET general layout style
                                     // width required, reduce width by 2px
                                     // if border (thin=1+1) left+right used
tcrobj1.tcdivStyle     = 'width:218px;border:gray solid thin'; // =>220

                                     // SET header background colour +font style
                                     // used if tcheaderText is set
tcrobj1.tcheaderStyle  = 'background-color:#c0c0c0; .. .';

                                     // SET menu background colour
tcrobj1.tcmenuStyle    = 'background-color:#efefef;.. .'; 

                                     // SET label background colour
tcrobj1.tclabelStyle   = 'background-color:#efefef;.. .';

                                     // SET odd row background colour
tcrobj1.tcoddrowStyle  = 'background-color:#efefef';

If component property 'PRIORITY' colours are inappropriate, open
'includes/js/tinycal.lang.XX.js' and update bottom row directive
'tcPrioColors = new Array(.. .)', please note 'Priority color signals' text.
Using the 'nullified' tcPrioColors row will result in NOT showing PRIORITY
component property and no colours in text or symbols (i.e. black).

IE6 might display (bg-)colours different, solution proposals are welcome.


BACK END PARAMETERS

Open 'getdata.php' in an editor and adapt (required) parameters:

LOGLEVEL      0                      // none, default
              1                      // only error msg
              2                      // every call =>
                                     // input_params+output_params+exec_time
                                     // + err msg)
              3                      // all above + action reports
              4                      // all above + output
LOGFILE       'tinycal.log'          // fixed log file name if LOGLEVEL > 0
              date( 'Ymd' ).'.log'   // ex. a daily log file name
              date( 'YW' ).'.log'    // ex. a weekly log file name
              date( 'Ym' ).'.log'    // ex. a monthly log file name
TCCALDIR      'calendar'             // folder for ALL local calendar files
TCCACHE       'cache'                // cache directory, write grants needed,
                                     // speeding up webcal
TCTIMEOUT     3600                   // seconds, renew cached remote files efter
                                     // TCTIMEOUT sec (i.e. delete old files)
TCUNIQUEID    'ical.net'             // site unique id, used when parsing
                                     // calendar files
TCALLOWGZIP   TRUE                   // if request headers accepts gzip,
                                     // output is gziped (TRUE) to increase
                                     // performance. Otherwise, or if web server
                                     // don't allow gzip, set to FALSE
TCLOCALOFFSET date( 'Z' )            // used when converting UTC datetimes to
                                     // local datetimes (dtstart, dtend, due)

Also make sure path in PHP require_once commands are correct.


TEST FILE PARAMETERS

Used if testing tinycal (running in 'test' mode, ex. using 'tinycal.php') and
want a dummy calendar file. The script creates a calendar file with 32 events,
starting today and within the next seven days, three events every day and four
recurrent events.

Open 'createTestfile.php' in an editor and adapt parameters

            /* include iCalcreator class incl. path */
require_once 'includes/iCalcreator.class.php';
            /* site unique id */
DEFINE( 'UNIQUE',   'ical.net' );
            /* folder for test file */
DEFINE( 'CALDIR',   'calendars' );
            /* name of calendar file */
DEFINE( 'TESTFILE', 'testFile.ics' );
            /* todays date */
DEFINE( 'THISDATE',  date('Y').date('m').date('d'));
            /* Some (MS) calendar definitions */
DEFINE( 'METHOD',   'PUBLISH' )
DEFINE( 'CALNAME',  'testFile' );
DEFINE( 'CALDESC',  'Calendar test file' );
DEFINE( 'TIMEZONE', 'Europe/Stockholm' );

The 'tinycal.config.js' configuration parameter 'tcData1' MUST match filename
defined here by 'TESTFILE' and the the 'TCCALDIR' configuration parameter in
'getdata.php' MUST match 'CALDIR'!

NB, if running in 'production' mode, remove or comment the include line
"require_once 'includes'.DIRECTORY_SEPARATOR.'createTestfile.php';"
in 'getdata.php'.

Including 'createTestfile.php', the tinycal back end overall executing time
extends by 0.8-1.0 second.

FILE LIST
=========
cache/                          cache directory (rw)
calendar/                       directory for calendars (r*)
images/                         images directory (r)
includes/                       includes directory (r)
includes/ajaxBackend.class.php  tinycal server backend class
includes/createTestFile.php     creates calendar test file
includes/iCalcreator.class.php  to place iCalcreator 2.6 class file
includes/js                     javascript directory (r)
includes/js/prototype.js        THE javascript
includes/js/tinycal.mini.js     tinycal javascript, (minified and obfuscated)
includes/js/tinycal.lang.en.js  tinycal en. language javascript
includes/js/tinycal.lang.se.js  tinycal se. language javascript
getdata.php                     tinycal ajax backend
tinycal.config.js               tinycal configuration javascript
tinycal.php                     sample index file testing the tinycal box
tinycal.log                     if using log (and fixed file name) in getdata
GPL.txt                         licence
README.txt                      this file

(rw) : read-write access rights for web server user (also files in directory)
(r)  : read access right for web server user (also files in directory)
(r*) : read-write access rights for web server user (if createTestFile is used)


COPYRIGHT & LICENCE
===================

COPYRIGHT

tinycal
copyright (c) 2008-2009 Kjell-Inge Gustafsson, kigkonsult
www.kigkonsult.se/tinycal/index.php
ical@kigkonsult.se

LICENCE

This program is free software; you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation; either version 2 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program; if not, write to the Free Software Foundation, Inc., 59 Temple 
Place, Suite 330, Boston, MA  02111-1307  USA
