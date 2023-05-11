<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="root">
            <div class="row">
            <form action="" method="get">
                <label for="folder">Folder:</label>
                <input 
                    type="text"
                    id="folder" 
                    name="folder" 
                    value="<?php
                        require_once 'functions.php'; 
                        echo getFromQueryStringOrDie('folder'); 
                    ?>"
                >
                <label for="numColumns">Columns:</label>
                <input 
                    type="number" 
                    id="numColumns" 
                    name="columns" 
                    value="<?php 
                        require_once 'functions.php';
                        global $DEFAULT_COLUMNS;
                        echo getFromQueryStringOrDefault('columns', $DEFAULT_COLUMNS); 
                    ?>"
                >
                <button type="submit">Reload</button>
            </form>
            </div>
            <div class="row">
            <?php
                require_once 'functions.php';

                global $DEFAULT_COLUMNS;
                $numColumns = $DEFAULT_COLUMNS;
                if (isset($_GET['columns'])) {
                    $numColumns = intval($_GET['columns']);
                }

                $generateThumbnails = true;

                $directory = $_SERVER['DOCUMENT_ROOT'] . '/' . getFromQueryStringOrDie('folder');
                $lastChar = substr($directory, -1);
                if ($lastChar !== '/' && lastChar !== '\\') {
                    $directory .= '/';
                }
                if (!is_dir($directory)) {
                    exit("Directory not found: " . $directory);
                }

                $mediaInfoList = createMediaInfoList($directory);
                if ($generateThumbnails) {
                    $thumbDir = $directory . '__thumbs/';
                    $mediaInfoList = addThumbnails($mediaInfoList, $thumbDir);
                }
                sortInPlaceByModifiedTimeNewestFirst($mediaInfoList);
                $columns = splitIntoColumns($mediaInfoList, $numColumns);

                // Generates the html for the columns and the media they contain
                foreach ($columns as $mediaColumn) {
                    echo '<div class="column">';
                    foreach ($mediaColumn as $mediaInfo) {
                        $url = convertPathToUrl($mediaInfo['path']);
                        if ($mediaInfo['mediaType'] === "image") {
                            $imageSrc = "src=\"$url\"";
                            $imageOptions = 'loading="lazy"';
                            echo "<img $imageSrc $imageOptions>";
                        }
                        else if ($mediaInfo['mediaType'] === "video") {
                            $videoSrc = "src=\"$url\"";
                            $videoOptions = 'controls="true" preload="none"';
                            $videoPoster = '';
                            if (isset($mediaInfo['thumbpath'])) {
                                $thumbpath = convertPathToUrl($mediaInfo['thumbpath']);
                                $videoPoster = "poster=\"$thumbpath\"";
                            }
                            echo "<video $videoSrc $videoPoster $videoOptions></video>";
                        }
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </body>
</html>




