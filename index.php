<?php require __DIR__ . '/utm-live.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Building Web Applications in Django — a Campus-Linked Open Course with Dr. Chuck.">
  <title>Building Web Applications in Django — CLOC</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header>
    <div class="header-inner">
      <h1>Building Web Applications in Django</h1>
      <p>A Campus-Linked Open Course</p>
    </div>
  </header>

  <main class="clearfix">
    <img
      class="hero-image"
      src="assets/cloc-hero.png"
      alt="Campus-Linked Open Course illustration"
      tabindex="0"
      role="button"
      aria-label="Open the course image at a larger size"
      data-modal-image>

    <h2>Learn Django with a little more structure and support</h2>

    <p>Hi, I’m Dr. Chuck. I built this course for people who like the flexibility of online learning but do not want to feel completely on their own. You can work from anywhere, follow the course calendar, and get help from the instructional team while the University of Michigan campus course is running.</p>

    <ul class="actions" aria-label="Course links">
      <li><a href="https://si-ali.catalog.instructure.com/courses/building-web-applications-in-django" target="_blank" rel="noopener noreferrer">Register for the course</a></li>
      <li><a href="about.html">Read how the CLOC works</a></li>
      <li><a href="https://si-ali.catalog.instructure.com/dashboard/in-progress" target="_blank" rel="noopener noreferrer">Enrolled Students: Course Dashboard</a></li>
    </ul>

    <p>This is a practical Django course with readings, videos, exercises, assignments, weekly communication, and live help. There are no university grades or credit, and you can keep working after the campus semester ends. The idea is simple: give online learners more rhythm, more connection, and a better chance to keep moving.</p>

    <p class="note">Click the image to view it at a larger size.</p>
  </main>

  <footer>
    <p>django.dr-chuck.com</p>
  </footer>

  <div id="image-modal" class="modal" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Course image preview">
    <button class="modal-close" type="button" aria-label="Close image preview">&times;</button>
    <img src="assets/cloc-hero.png" alt="Campus-Linked Open Course illustration enlarged">
  </div>

  <script src="script.js"></script>
</body>
</html>
