# ClipSurf
A minimalist browser based gallery that displays all the images and videos in a local folder.

![Screenshot](https://user-images.githubusercontent.com/3581229/237789627-eb37d3f2-169e-4b33-a243-4e44721a2bf6.png)

To use:

1. Install PHP 5+ and FFmpeg. 

2. Start PHP's built-in webserver in your home directory:
```
php -S localhost:8000
```
3. Open `localhost:8000/path/to/index.php?folder=/path/to/media/folder/` in your favorite web browser. (The first load will take a while because it's generating thumbnails for all the videos.)

Note that both paths are relative to your home folder. For example, say you downloaded this project to `/home/code/ClipSurf/` and you want to view `/home/documents/images/`. You would open the following page in your browser: `localhost:8000/code/ClipSurf/index.php?folder=/documents/images/`.

I've only tested this on Mac with PHP 7, but it should work cross platform. (Please open an issue if it doesn't.)

