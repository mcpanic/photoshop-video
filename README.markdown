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

## Directory Permission
Make sure `img/thumbnails/` directory is accessible by the user PHP runs in.
You can verify the username in PHP by adding `echo exec('whoami');` to a blank PHP file.

## Machine-Specific Configuration
ToolScape requires ffmpeg for video processing and thumbnail generation. Make sure the ffmpeg path is correctly configured.

1. Verify you machine's ffmpeg path by running `which ffmpeg` from command line.
1. Then, modify `conn.php` to reflect the correct path.

Refer to [ffmpeg installation instructions](https://ffmpeg.org/trac/ffmpeg/wiki/UbuntuCompilationGuide) if you need to compile ffmpeg on Linux.

# Running ToolScape
Open `index.php` and enter `admin` as your username.

## Switching Tasks and Interfaces
After logging in, skip ahead to `Part 2-1` or set URL to `list.php?part=2&step=list&cond=A&tid=4&iid=1`.
Change `tid` and `iid` parameters for different tasks and interfaces, respectively.

`tid` values and task descriptions

1. Motion Blur
1. Background Removal
1. Lomo Effect
1. Retro Effect
1. Photo to Sketch
1. Teeth Whitening

`iid` values and interface descriptions

1. ToolScape
1. YouTube
1. Text only + step-by-step

## Labeling Interface
From the ToolScape's `list.php` page, click on the `(label)` link associated with each video.

