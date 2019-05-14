<?php
  $uploadfolder = "uploads";
  $iconfolder = "img/classic";
  $iconsizewidth = "35px";
  $iconsizeheight = "35px";
  $filesviewrow = 2;
  if (file_exists($uploadfolder)) {
    $files = scandir($uploadfolder);
    $files = array_diff(scandir($uploadfolder), array('.', '..'));
    $i = 0;
    $c = count($files);
    if ($c < 1) {
      echo "No uploads yet.";
    } else {
      function findIcon($uploadfolder, $iconfolder, $filename) {
        // Check if it's a folder or a file
        $filetype = substr($filename, strrpos($filename, ".") + 1);
        if (is_dir($uploadfolder."/".$filename)) {
          return "img/extra/folder.svg";
        }
        elseif (file_exists($iconfolder.'/'.$filetype.'.svg')) {
          return $iconfolder.'/'.$filetype.'.svg';
        } else {
          return $iconfolder.'/blank.png';
        }
      }
      echo "<table>";
    while ($i < $c) {
      $ti = $i+2;
      if (($i % $filesviewrow) == 0 || $i == 0) {
      // Create new row?
      echo "<tr>";
      }
      $filesize = filesize($uploadfolder."/".$files[$ti]) / 1000 / 1000;
      if ($filesize < 1) {
        $filesize = strtok(($filesize*1000), ".");
        $filesize = $filesize." KB";
      } elseif ($filesize > 1000) {
        $filesize = strtok(($filesize/1000), ".");
        $filesize = $filesize." GB";
      } else {
        $filesize = strtok($filesize, ".")." MB";
      }
      echo "<td><a href='$uploadfolder/$files[$ti]' style='color:black;' target='_blank'><img src=".findIcon($uploadfolder, $iconfolder, $files[$ti])." style='width:$iconsizewidth;height:$iconsizeheight;'> ".$files[$ti]."</a><td><kbd>$filesize</kbd></td></td>";
     if (($i % $filesviewrow) == 0) {
      // End row?
      echo "</tr>";
    }
      $i++;
    }
      echo "</table>";
    }
  } else {
    echo "Upload folder doesn't exist.";
  }
 ?>
