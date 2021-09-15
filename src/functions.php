<?php
function exploreFiles() : ?array{
    $dirName = __DIR__.'/ascii_art';
    $filesList = [];
    if(file_exists($dirName) && is_dir($dirName)){
        if($handle = opendir($dirName)){
            while (false !== ($entry = readdir($handle))) {
                if($entry != '.' && $entry != '..'){
                   $fileName = substr($entry, 0, strpos($entry, '.'));
                   $format = <<<EOD
<li class="list-group-item d-flex">
<a href="?action=display&txtFile=$fileName" class="list-group-item list-group-item-action bg-light">
<i class="fa fa-file-text" aria-hidden="true"></i>
%s
</a>
<a href="?action=show&txtFile=$fileName" class="align-self-center"><i class="fa fa-pencil-square fa-lg ml-1" aria-hidden="true"></i></a>
<a href="?action=delete&txtFile=$fileName" class="align-self-center"><i class="fa fa-times fa-lg ml-1" aria-hidden="true"></i></a>
</li>
EOD;
                    array_push($filesList, sprintf($format, $fileName));
                }
            }
            return $filesList;
        }
        closedir($handle);
    }
    return '<div class="alert alert-danger">Impossible de lister les fichiers !<div>';
}

function showArt(string $fileName) : ?string {
    $dirName = __DIR__ . '/ascii_art';

    if(file_exists($dirName) && is_dir($dirName)){
        $filePath = sprintf('%s/%s.txt', $dirName, $fileName);
        if(file_exists($filePath) && is_file($filePath)){
            $handle = fopen($filePath, 'r');
            if($handle === false){
                return '<div class="alert alert-danger">Impossible d\'ouvrir le fichier !<div>';
            }
            $content = fread($handle, filesize($filePath));
            fclose($handle);
            $format = sprintf('<pre class="mx-auto w-50 p-2 mt-3">%s</pre>', $content);
            return $format;
        }
        return '<div class="alert alert-danger">Ce dessin n\'existe pas !<div>';
    }
}


function create_file(string $fileName, string $drawing) : ?string{
    $dirName = __DIR__.'/ascii_art';
    if(file_exists($dirName) && is_dir($dirName)){
        $filePath = sprintf('%s/%s.txt', $dirName, $fileName);
        if(!file_exists($filePath)){
            $handle = fopen($filePath, 'w'); 
            if($handle === false){
                return '<div class="alert alert-danger">Impossible d\'ouvrir le fichier !<div>';
            }
            if(fwrite($handle, $drawing) === false){
                return '<div class="alert alert-danger">Impossible d\'écrire dans le fichier !<div>';
            }
            fclose($handle);
            return '<div class="alert alert-success">Votre dessin a bien été créé</div>';
        }
        return '<div class="alert alert-danger">Ce nom de dessin existe déjà</div>';
    }
}


function upload_file(array $file_param){
    $dirName = __DIR__.'/ascii_art';
    $allowedType = ['text/plain'];
    $allowedSize = 1000000;

    if(in_array($file_param['type'], $allowedType) === false){
        return '<div class="alert alert-danger"> Ce type de fichier n\'est pas autorisé.</div>';
    }
    if($file_param['size'] > $allowedSize){
        return '<div class="alert alert-danger">Le fichier est trop lourd.</div>';
    }
    $newFilePath = $dirName . '/' . $file_param['name'];
    if(file_exists($newFilePath)){
        return '<div class="alert alert-danger">Ce nom de fichier existe déjà !</div>';
    }
    if(move_uploaded_file($file_param['tmp_name'], $newFilePath) === false){
        return '<div class="alert alert-danger">Une erreur est survenue lors du déplacement du fichier. Actualisez la page et réessayez.<div>';
    }
    return '<div class="alert alert-success">Votre fichier a bien été ajouté !</div>';
}


function show_edit_file_form(string $fileName) : string {
    $dirName = __DIR__.'/ascii_art';
    $filePath = sprintf("%s/%s.txt", $dirName, $fileName);
    $handle = fopen($filePath, 'r');
    if($handle === false){
        return '<div class="alert alert-danger">Impossible d\'ouvrir le fichier !<div>';
    }
    $content = fread($handle, filesize($filePath));
    fclose($handle);

    $format = <<<EOD
<form class="p-2 w-50 mx-auto" method="post" action='/'>
<div class="form-group">
<label for="exampleFormControlInput1" class="form-label">File Name</label>
<input type="text" class="form-control" id="exampleFormControlInput1" value="$fileName" name="editFile[newName]" required>
<input type="hidden" value="$fileName" name="editFile[oldName]">
</div>
<div class="form-group">
<label for="exampleFormControlTextarea1" class="form-label"> Drawing </label>
<textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="editFile[art]" required>$content</textarea>
</div>
<div style="text-align: center;">
<button type="submit" class="btn btn-primary text-center w-90">Edit drawing</button>
</div>
</form>
EOD;
    return $format;
}


function edit_file(string $oldNameFile, string $newNameFile, string $editedDrawing) : string{
    $dirName = __DIR__.'/ascii_art';
    $filePathNewName = sprintf('%s/%s.txt', $dirName, $newNameFile);
    $filePathOldName = sprintf('%s/%s.txt', $dirName, $oldNameFile);

    if($filePathOldName !== $filePathNewName){
        rename($filePathOldName, $filePathNewName);
    }
    $handle = fopen($filePathNewName, 'w');
    if($handle === false){
        return '<div class="alert alert-danger">Impossible d\'ouvrir le fichier !<div>';
    }
    if (fwrite($handle, $editedDrawing) === false) {
        return '<div class="alert alert-danger">Impossible d\écrire dans le fichier !<div>';
    }
    fclose($handle);
    return '<div class="alert alert-success">Votre dessin a bien été mis à jour !</div>';
}


function delete_file(string $fileName){
    $dirName = __DIR__.'/ascii_art';
    $filePath = sprintf('%s/%s.txt', $dirName, $fileName);
    if(false !== unlink($filePath)){
        return '<div class="alert alert-success">Le fichier a bien été supprimé !</div>';
    }
    return '<div class="alert alert-danger">Une erreur est survenue lors de la suppression du fichier !<div>';
}