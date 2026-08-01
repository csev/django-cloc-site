DJANGO CLOC MINI-SITE

Files:
  index.php        Main one-page site (includes UTM tracking)
  about.html       Short explanation of the CLOC model
  utm-live.php     UTM tracker + live results page
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

IMPORTANT LINK CHECK
On both pages:
  Register:
    https://si-ali.catalog.instructure.com/courses/building-web-applications-in-django
  Course dashboard:
    https://si-ali.catalog.instructure.com/dashboard/in-progress

On both pages:
  LinkedIn article:
    https://www.linkedin.com/pulse/campus-linked-open-course-charles-severance-hjccc/
  YouTube video:
    https://www.youtube.com/watch?v=vaEBaRATTt8

On about.html only:
  Financial aid:
    https://forms.gle/uVtEWywveacTEUz96
  Master Programmer Curriculum:
    https://www.masterprogrammer.com

External links open in a new tab.

No libraries, frameworks, or build step are required.
