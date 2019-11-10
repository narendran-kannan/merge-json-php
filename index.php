<?php
function mergejson($foldername, $inputbasename, $outputbasename, $maxsize)
{
  $fi = new FilesystemIterator($foldername.'/', FilesystemIterator::SKIP_DOTS);
  $merged = [];
  $merged_json = [];


  for ($i = 1; $i <= iterator_count($fi); $i++) {
    $aDecoded = json_decode(file_get_contents($foldername . '/' . $inputbasename . $i . '.json'), true);

    $name = key($aDecoded);
    if ($i == 1) {
      $exist = array($name => [],);
    }
    $merged = [
      $name => array_merge($aDecoded[$name]),
    ];
    $exist = [
      $name => array_merge($exist[$name], $merged[$name]),
    ];
  }

  $merged_json = json_encode($exist, JSON_PRETTY_PRINT);

  if (sizeofvar($merged_json) < $maxsize) {
    $mergedfile = fopen('output/'.$outputbasename.".json", "w") or die("Unable to open file!");
    fwrite($mergedfile, $merged_json);

    fclose($mergedfile);
    echo "File merged and saved in output/ as ".$outputbasename.".json";
  } else {
    echo "Merged File size is larger than given size! The file cannot be created";
  }
}

mergejson('input', 'data', 'output', '520');

function sizeofvar($var)
{
  $start_memory = memory_get_usage();
  $tmp = unserialize(serialize($var));
  return memory_get_usage() - $start_memory;
}
