**ToolScape** is a web interface for browsing and watching tutorial videos.
It features a storyboard video summary for faster scanning and interactive timeline for non-sequential watching.

# Requirement 
## Server-side 
Apache, PHP5, MySQL

## Client-side 
Standard-compliant web browser with HTML5 and CSS3


# Directory Structure
- `archive`: old prototypes for reference.
- `css`: all stylesheets.
- `img`: all images and automatically-generated thumbnails for labels.
- `js`: all Javascript code resides here. Third party libraries should be added to the `libs` directory.
- `video`: all video files and their Youtube metadata files (.info.json). Subdirectories:
	- `ff`: all Fast Forward summaries
	- `sc`: all Scene Clip summaries
	- `scripts`: scripts for downloading and processing videos
	- `thumbs`: thumbnail sprites for the thumbnail preview feature in the video player


# Web Server Setup Instruction 

## Get Video and Thumbnails
Media files are not in the repository, so you need to download them.
- [thumbnails](http://juhokim.com/toolscape/thumbnails.zip) (extract to `[toolscape_root]/img/thumbnails`)
- [video](http://juhokim.com/toolscape/video.zip) (extract to `[toolscape_root]/video`)

## Database
1. Update `conn.php` with correct MySQL connection information.
1. Set up an empty MySQL database called `video_tutorial`, or name you specified in `conn.php`.
1. Then import tables and records from `vt.sql`. This file assumes `video_tutorial` to be the database name. 
Be careful if you want to use another name for database.
	- command line: TBD
	- phpmyadmin: Go to `import`, select `vt.sql`, and done.

## Machine-Specific Configuration
ToolScape requires ffmpeg for video processing and thumbnail generation. Make sure the ffmpeg path is correctly configured.

1. Verify you machine's ffmpeg path by running `which ffmpeg` from command line.
1. Then, modify `conn.php` to reflect the correct path.


# Running ToolScape
Open `index.php` and enter `admin` as your username.