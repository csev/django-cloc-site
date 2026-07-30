<?php
/**
 * UTM tracking — one file, two jobs:
 *   - include from index.php → record visit UTMs
 *   - open this URL directly → live results table
 *
 * Storage: SQLite file (utm.sqlite) beside this script. No server DB required.
 */

define('UTM_DB_FILE', __DIR__ . '/utm.sqlite');
define('UTM_MAX_CODES', 250);

function utm_db() {
    static $db = null;
    if ($db instanceof SQLite3) {
        return $db;
    }

    $db = new SQLite3(UTM_DB_FILE);
    $db->busyTimeout(3000);
    $db->exec('PRAGMA journal_mode=WAL');
    $db->exec(
        'CREATE TABLE IF NOT EXISTS utm (
            utm_string TEXT PRIMARY KEY NOT NULL,
            count INTEGER NOT NULL DEFAULT 0,
            first_use TEXT NOT NULL,
            last_use TEXT NOT NULL
        )'
    );
    return $db;
}

function utm_normalize_string(array $params) {
    // Fold query keys/values to lowercase so UTM_SOURCE=Email matches utm_source=email.
    $lower = array();
    foreach ($params as $name => $value) {
        $lower[strtolower((string) $name)] = $value;
    }

    // Bare ?utm=qr is allowed, plus the usual utm_* pair params.
    $keys = array('utm', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content');
    $parts = array();
    foreach ($keys as $key) {
        if (!isset($lower[$key])) {
            continue;
        }
        $value = strtolower(trim((string) $lower[$key]));
        if ($value === '') {
            continue;
        }
        // Keep values short so the table cannot be stuffed with huge strings.
        if (strlen($value) > 200) {
            $value = substr($value, 0, 200);
        }
        $parts[] = $key . '=' . $value;
    }
    return implode('&', $parts);
}

function utm_prune(SQLite3 $db) {
    $count = (int) $db->querySingle('SELECT COUNT(*) FROM utm');
    if ($count <= UTM_MAX_CODES) {
        return;
    }

    $extra = $count - UTM_MAX_CODES;
    $stmt = $db->prepare(
        'DELETE FROM utm WHERE utm_string IN (
            SELECT utm_string FROM utm
            ORDER BY last_use ASC, utm_string ASC
            LIMIT :limit
        )'
    );
    $stmt->bindValue(':limit', $extra, SQLITE3_INTEGER);
    $stmt->execute();
}

function utm_track() {
    $utm = utm_normalize_string($_GET);
    if ($utm === '') {
        return;
    }

    $now = gmdate('Y-m-d H:i:s');
    $db = utm_db();
    $db->exec('BEGIN IMMEDIATE');

    try {
        $stmt = $db->prepare('SELECT count FROM utm WHERE utm_string = :utm');
        $stmt->bindValue(':utm', $utm, SQLITE3_TEXT);
        $row = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

        if ($row) {
            $upd = $db->prepare(
                'UPDATE utm SET count = count + 1, last_use = :now WHERE utm_string = :utm'
            );
            $upd->bindValue(':now', $now, SQLITE3_TEXT);
            $upd->bindValue(':utm', $utm, SQLITE3_TEXT);
            $upd->execute();
        } else {
            $ins = $db->prepare(
                'INSERT INTO utm (utm_string, count, first_use, last_use)
                 VALUES (:utm, 1, :now, :now)'
            );
            $ins->bindValue(':utm', $utm, SQLITE3_TEXT);
            $ins->bindValue(':now', $now, SQLITE3_TEXT);
            $ins->execute();
            utm_prune($db);
        }

        $db->exec('COMMIT');
    } catch (Exception $e) {
        $db->exec('ROLLBACK');
    }
}

function utm_h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function utm_show_dashboard() {
    $db = utm_db();
    $total_codes = (int) $db->querySingle('SELECT COUNT(*) FROM utm');
    $total_hits = (int) $db->querySingle('SELECT COALESCE(SUM(count), 0) FROM utm');
    $result = $db->query(
        'SELECT utm_string, count, first_use, last_use
         FROM utm
         ORDER BY last_use DESC, count DESC'
    );

    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-store');
    ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>UTM Live</title>
</head>
<body>
  <p><a href="./">Home</a></p>
  <h1>UTM Live</h1>
  <p>
    <?php echo (int) $total_codes; ?> / <?php echo (int) UTM_MAX_CODES; ?> codes,
    <?php echo (int) $total_hits; ?> hits. Times UTC.
    Cap drops oldest last_use.
  </p>

  <?php if ($total_codes === 0): ?>
    <p>No UTM visits yet.</p>
  <?php else: ?>
    <table border="1" cellpadding="4" cellspacing="0">
      <tr>
        <th>utm_string</th>
        <th>count</th>
        <th>first_use</th>
        <th>last_use</th>
      </tr>
      <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
          <td><code><?php echo utm_h($row['utm_string']); ?></code></td>
          <td><?php echo (int) $row['count']; ?></td>
          <td><?php echo utm_h($row['first_use']); ?></td>
          <td><?php echo utm_h($row['last_use']); ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php endif; ?>
</body>
</html>
    <?php
}

// Included from another page → track. Opened directly → show live results.
if (realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
    utm_show_dashboard();
} else {
    utm_track();
}
