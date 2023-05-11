# ClipSurf
A minimalist browser based gallery that displays all the images and videos in a local folder.

To use:

1. Install PHP 5+ and FFmpeg. 

2. Start PHP's built-in webserver in your home directory:
```
php -S localhost:8000
```
3. Open `localhost:8000/path/to/index.php?folder=path/to/media/folder/` in your favorite web browser.

Note that both paths are relative to your home folder. For example, say you downloaded this project to `/home/code/ClipSurf/` and you want to open `/home/documents/images/`. You would open the following page in your browser: `localhost:8000/code/ClipSurf/index.php?folder=/documents/images/`.

I've only tested this on Mac with PHP 7, but it should work cross platform. 

