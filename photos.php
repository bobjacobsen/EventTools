<?php

// show the contents of a "thumbs" directory
function show_thumb_strip($id, $dir, $linkURL) {
    // open thumbs directory 
    $path = $dir.'/'.$id;
    $thumbDir = opendir($path.'/thumbs');
    $photoDir = opendir($path);
    
    // get each entry
    while($entryName = readdir($thumbDir)) {
        $thumbArray[] = $entryName;
    }
    while($entryName = readdir($photoDir)) {
        $photoArray[] = $entryName;
    }
    
    // close directories
    closedir($thumbDir);
    closedir($photoDir);
        
    for($index=0; $index < count($thumbArray); $index++) {
        if (substr("$thumbArray[$index]", 0, 1) == ".") continue;
        if (
                (strtoupper(substr("$thumbArray[$index]", -4)) != ".JPG") &&
                (strtoupper(substr("$thumbArray[$index]", -4)) != ".GIF") &&
                (strtoupper(substr("$thumbArray[$index]", -4)) != ".PNG")
            ) continue;
        
        // get stem
        $stem = substr($thumbArray[$index],0,strrpos($thumbArray[$index],'.'));
        if (substr($stem,0,3) == 'th_') $stem = substr($stem,3);
        // find parent photo
        $target = 'foo';
        for($j=0; $j < count($photoArray); $j++) {
            if (substr("$photoArray[$j]", 0, 1) == ".") continue;
            if (
                    (strtoupper(substr("$photoArray[$j]", -4)) != ".JPG") &&
                    (strtoupper(substr("$photoArray[$j]", -4)) != ".GIF") &&
                    (strtoupper(substr("$photoArray[$j]", -4)) != ".PNG")
                ) continue;
            if (strrpos($photoArray[$j],$stem) === 0) 
                $target = $photoArray[$j];
        }
        echo '<a href="'.$linkURL.$path.'/'.$target.'"><img src="'.$path.'/thumbs/'.$thumbArray[$index].'"></a>';
    }
}

// show the pictures for a layout
function show_photo_strip($id, $dir) {
    // open image directory 
    $path = $dir.'/'.$id;
    $photoDir = opendir($path);
    
    while($entryName = readdir($photoDir)) {
        $photoArray[] = $entryName;
    }
    
    // close directories
    closedir($photoDir);
        
    for($index=0; $index < count($photoArray); $index++) {
        if (substr("$photoArray[$index]", 0, 1) == ".") continue;
        if (
                (strtoupper(substr("$photoArray[$index]", -4)) != ".JPG") &&
                (strtoupper(substr("$photoArray[$index]", -4)) != ".GIF") &&
                (strtoupper(substr("$photoArray[$index]", -4)) != ".PNG")
            ) continue;
        
        echo '<img src="'.$path.'/'.$photoArray[$index].'">';
    }
}

?>
