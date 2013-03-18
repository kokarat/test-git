<?php 
$filename = $_REQUEST['filename'];
if($filename != ''){
    $file = tempnam("tmp","zip");
    zip_directory('demo/',$file);

    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($file));
    header('Content-Disposition: attachment; filename="'.$filename.'.zip"');

    readfile($file);
    unlink($file); 
}else{
    echo "<h1>Please enter file name !!!</h1>";
}
function zip_directory($source,$tempfile){
    if(!extension_loaded('zip') || !file_exists($source)) return false;
    $zip = new ZipArchive();
    if(!$zip->open($tempfile,ZIPARCHIVE::CREATE)) return false;
    $source = str_replace('\\','/',realpath($source));
    if(is_dir($source) === true){
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
        foreach($files as $file){
            $file = str_replace('\\', '/', realpath($file));
            if(is_dir($file) === true) $zip->addEmptyDir(str_replace($source . '/','', $file . '/'));
            else if(is_file($file) === true) $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
        }
    }
    elseif(is_file($source) === true) $zip->addFromString(basename($source), file_get_contents($source));
    return $zip->close();
}

?>