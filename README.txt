DJANGO CLOC MINI-SITE

Files:
  index.php        Main one-page site (includes UTM tracking)
  about.html       Short explanation of the CLOC model
  utm-live.php     UTM tracker, link click counts, and the live results page
  register.php     Counts a tracked link click, then redirects to its destination
  utm.sqlite       SQLite data file (created automatically on first UTM hit)
  styles.css       Layout and styling
  script.js        Click-to-open image modal
  assets/          Hero image

Upload the contents of this folder to the web root for django.dr-chuck.com.
PHP with the SQLite3 extension is required for UTM tracking.

UTM TRACKING
  Home page visits that include standard utm_* query parameters are recorded.
  Example:
    https://django.dr-chuck.com/index.php?utm_source=email&utm_medium=newsletter&utm_campaign=fall

  View live counts:
    https://django.dr-chuck.com/utm-live.php

  Storage columns: utm_string, count, first_use, last_use (UTC).
  Cap: 250 distinct UTM strings. Extra rows are deleted by oldest last_use.
  Visits with no UTM are stored as (none) and are never pruned.

  Link clicks are counted separately, then the browser is redirected.
    Register (hidden on the home page): register.php?to=register
      https://si-ali.catalog.instructure.com/courses/building-web-applications-in-django
    CLOC-502 from the home page:        register.php?to=next
      https://python.dr-chuck.com/
    CLOC-502 from the about page:       register.php?to=next-about
      https://python.dr-chuck.com/
    Financial aid (about):              register.php?to=finaid
      https://forms.gle/uVtEWywveacTEUz96
  Any other to= value redirects home and is not counted.
  The live page lists each link. A count is a click, not a completed enrollment or aid application.

IMPORTANT LINK CHECK
On the home page:
  Register (currently commented out), via register.php?to=register:
    https://si-ali.catalog.instructure.com/courses/building-web-applications-in-django
  CLOC-502, via register.php?to=next:
    https://python.dr-chuck.com/
  Course dashboard:
    https://si-ali.catalog.instructure.com/dashboard/in-progress

On both pages:
  LinkedIn article:
    https://www.linkedin.com/pulse/campus-linked-open-course-charles-severance-hjccc/
  YouTube video:
    https://www.youtube.com/watch?v=vaEBaRATTt8&list=PLQqtpRlfsBek&index=2
  First week welcome video:
    https://www.youtube.com/watch?v=0lUAYNDl-mM&list=PLQqtpRlfsBek&index=1

On about.html only:
  CLOC-502, via register.php?to=next-about:
    https://python.dr-chuck.com/
  Financial aid, via register.php?to=finaid:
    https://forms.gle/uVtEWywveacTEUz96
  Master Programmer Curriculum:
    https://www.masterprogrammer.com

External links open in a new tab.

No libraries, frameworks, or build step are required.
