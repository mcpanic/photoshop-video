<?php

// NOTE: The following 'define' settings are machine and server-specific.
// Make sure to configure them correctly before starting ToolScape.

define("FFMPEG_PATH", "/opt/local/bin/ffmpeg");	// path of ffmpeg

define("DB_HOST", "localhost");	// MySQL host name
define("DB_USERNAME", "root");	// MySQL username
define("DB_PASSWD", "root");	// MySQL password
define("DB_NAME", "video_learning");	// MySQL database name. vt.sql uses the default video_learning name. So be careful.

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>