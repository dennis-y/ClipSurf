<?php

$DEFAULT_COLUMNS = 4;

function getMediaType($path) {
    $mime = mime_content_type($path);
    if (strpos($mime, "image/") === 0) {
        return "image";
    }
    if (strpos($mime, "video/") === 0) {
        return "video";
    }
    return null;
}

function createMediaInfoList($directory) {
    $mediaInfoList = array();    
    $files = scandir($directory);
    foreach ($files as $fileName) {
        $filePath = $directory . $fileName;
        $mediaType = getMediaType($filePath);
        if (is_null($mediaType)) {
            continue;
        }
        $entry = array(
            'path' => $filePath,
            'mediaType' => $mediaType,
        );
        array_push($mediaInfoList, $entry);
    }
    return $mediaInfoList;
}

function createVideoThumbnail($path, $thumbDir, $width) {
    $name = pathinfo($path, PATHINFO_FILENAME);
    $thumbpath = $thumbDir . $name . ".jpg";
    if (file_exists($thumbpath)) {
        return $thumbpath;
    }
    $cmd = ("ffmpeg -n -loglevel error -ss 00:00:00.00 " .
            "-i '$path' " .
            "-vf 'scale=$width:-1:force_original_aspect_ratio=decrease' " .
            "-vframes 1 " . 
            "'$thumbpath'");
    shell_exec($cmd);
    return $thumbpath;
}

function addThumbnails($mediaInfoList, $thumbDir, $thumbWidth = 300) {
    $updatedMediaInfoList = array();
    if (!is_dir($thumbDir)) {
        mkdir($thumbDir);
    } 
    foreach ($mediaInfoList as $mediaInfo) {
        if ($mediaInfo['mediaType'] === 'video') {
            $mediaInfo['thumbpath'] = createVideoThumbnail($mediaInfo['path'], $thumbDir, $thumbWidth);
        }
        array_push($updatedMediaInfoList, $mediaInfo);
    }
    return $updatedMediaInfoList;
}

// Need a reference -- everything in php is passed by value by default
function sortInPlaceByModifiedTimeNewestFirst(&$files) {
    usort($files, function($a, $b) {
        return filemtime($a['path']) < filemtime($b['path']) ? 1 : -1;
    }); 
}

function splitIntoColumns($files, $numColumns) {
    $columns = array();
    for ($i = 0; $i < $numColumns; $i++) {
        array_push($columns, array());
    }
    $elementCount = 0;
    foreach ($files as $file) {
        $col = ($elementCount % $numColumns);
        array_push($columns[$col], $file);
        $elementCount++;
    }
    return $columns;
}

function convertPathToUrl($path) {
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    $absolutePath = realpath($path);
    return str_replace($documentRoot, '', $absolutePath);
}

function getFromQueryStringOrDie($name) {
    if (!isset($_GET[$name])) {
        throw new Exception("Expected $name to be set in the query string, but it isn't");
    }
    return $_GET[$name];
}

function getFromQueryStringOrDefault($name, $default) {
    if (!isset($_GET[$name])) {
        return $default;
    }
    return $_GET[$name];
}

// Not currently used
function getDimensions($path) {
    $cmd = "ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 '$path'";
    $out = shell_exec($cmd);
    list($x, $y) = explode('x', $out);
    return array(
        'width' => intval($x),
        'height' => intval($y),
    );
}
?>