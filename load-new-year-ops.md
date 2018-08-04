Instructions for Rolling to a New Year

These are the instructions for rolling a convention to a new year. E.g. you've have BayRails 2015, and want to get ready to use that same EventTools install for BayRails 2017.  You can also use this to reload a test database from a production one.

- [ ] Export all bayrails2015 databases using phpMyAdmin

- Prepare a database update
    - [ ] Start with a recent backup of just the old (bayrails2015_) tables
    - [ ] replace all prefix bayrails2015_ with bayrails2017_ (about 1758 entries)
    - [ ] remove INSERT for bayrails2017_eventtools_changelog table, resetting that
    - [ ] remove these lines near top and bottom
>                /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
>                /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

    - [ ] remove CREATE ALGORITHM defines near bottom (leave the DROP lines)
    - [ ] Remove previous year op session data by removing IMPORT blocks for
        - [ ] bayrails2017_eventtools_opsession (leave in place, if they've started working on defining sessions before you get around to this)
        - Four tables for op session requests and assignments:
        - [ ] bayrails2017_eventtools_opsession_req (resetting this resets the attendee list too)
        - [ ] bayrails2017_eventtools_opsreq_group
        - [ ] bayrails2017_eventtools_opsreq_group_req_link
        - [ ] bayrails2017_eventtools_opsreq_req_status (huge!)
        - Note: you're leaving the old layouts, etc defined so they can be reused if needed; the organizers will have to set them to inactive status if they don't want them displayed (we could do the systematically perhaps)
    - [ ] Temporarily edit define_views.sql (you don't have to save it, you'll just use content below) to replace prefix_ with bayrails2017_
       
- Sequel Pro (for localhost database update)
    - [ ] Start mySql, log on to localhost
    - [ ] Make sure correct (e.g snuemann) database exists and is selected
    - [ ] "Query" icon, paste the load content (from above) in window, select entire pasted content, hit "Run Selection": should see about 84 queries, 1686 rows affected
    - [ ] "Query" icon, paste define_views.sql contents in window, select entire pasted content, hit "Run Selection": should see about 14 queries
        
- [ ] Check that localhost is working

- Go to the Bayrails phpMyAdmin
    - [ ] do the data insert via "SQL" and cut&paste
    - [ ] refresh tables to see the new ones
    - [ ] do the view creation via "SQL" and cut&paste

 - [ ] Check the site is working

