<?php
require_once('functions.php');
$displayDrawing = isset($_GET['action'], $_GET['txtFile']) && $_GET['action'] === 'display';
$addDrawing = isset($_POST['file'], $_POST['file']['name'], $_POST['file']['art']) && !empty($_POST['file']['name']) && !empty($_POST['file']['art']);
$uploadDrawing = isset($_FILES['drawing']);
$deleteDrawing = isset($_GET['action'], $_GET['txtFile']) && $_GET['action'] === 'delete';

$editForm = isset($_GET['action'], $_GET['txtFile']) && $_GET['action'] === 'show';
$editDrawing = isset($_POST['editFile']['art'], $_POST['editFile']['newName'], $_POST['editFile']['oldName']) && !empty($_POST['editFile']['oldName']) && !empty($_POST['editFile']['newName']) && !empty($_POST['editFile']['art']);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Simple Sidebar - Start Bootstrap Template</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/simple-sidebar.css" rel="stylesheet">

    <!-- See https://fontawesome.com/v4.7.0/icons/ for more informations -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
          crossorigin="anonymous"
    >
    <link rel="stylesheet" href="css/master.css">
    <style>
        .hide{
            display:none;
        }

        .fa-trash{
            color: red;
        }

        .fa-times{
            color: red;
        }

    </style>
</head>

<body>

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading"> <i class="fa fa-paint-brush"></i> Ascii Art </div>
        <div class="list-group list-group-flush">
            <a href="/" class="list-group-item list-group-item-action list-group-item-primary bg-light"><i class="fa fa-home"></i> Accueil</a>
            <a href="#" class="list-group-item list-group-item-action bg-light" id="explore"><i class="fa fa-search"></i> Explorer</a>
            <ul class="list-group hide" id="fileMenu">
                <?php foreach(exploreFiles() as $value) { echo $value;} ?> <!-- exploreFiles() affiche un template html avec le nom du fichier .txt -->
            </ul>
            <a href="#" class="list-group-item list-group-item-action bg-light" id="create"><i class="fa fa-plus"></i> Créer</a>
            <form class="p-2 hide" id="fileForm" method="post" action='/'>
                <div class="form-group">
                    <!-- <label for="exampleFormControlInput1" class="form-label">File Name</label> -->
                    <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="File name" name="file[name]" required>
                </div>
                <div class="form-group">
                    <!-- <label for="exampleFormControlTextarea1" class="form-label">Draw your ascii art here...</label> -->
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Draw your ascii art here..." name="file[art]" required></textarea>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary text-center w-90">Add drawing</button>
                </div>
            </form>
            
            <a href="#" class="list-group-item list-group-item-action bg-light" id="upload"><i class="fa fa-upload" aria-hidden="true"></i></i> Ajouter</a>
            <form class="p-2 hide" id="uploadForm" method="post" action='/' enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="1000000">
                <div class="form-group">
                    <!-- <label for="exampleFormControlFile" class="form-label">Ajouter un dessin</label> -->
                    <input type="file" class="form-control mt-3" name="drawing" id="exampleFormControlFile">
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary text-center w-90">Add drawing file</button>
                </div>
            </form>
        </div>
        <?= $addDrawing ? create_file($_POST['file']['name'], $_POST['file']['art']) : '' ?> <!-- Si les conditions de la variable $addDrawing son vraies, create_file() est appelée -->
        <?= $uploadDrawing ? upload_file($_FILES['drawing']) : '' ?> <!-- Si les conditions de la variable $uploadDrawing son vraies, upload_file() est appelée -->
        <?= $deleteDrawing ? delete_file($_GET['txtFile']) : '' ?> <!-- Si les conditions de la variable $deleteDrawing son vraies, delete_file() est appelée -->
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <button class="btn btn-primary" id="menu-toggle"><i id="icon-toggle" class="fa fa-chevron-left"></i></button>
        </nav>

        <div class="container-fluid">
            <!-- <h1 class="mt-4">Bienvenue sur le site de l'ASCII Art !</h1>
            <p>Visualisez et créez en utilisant des caractères ASCII.</p> -->
            <?php  
                if($displayDrawing) {
                    echo showArt($_GET['txtFile']);
                } else if($editForm){
                    echo show_edit_file_form($_GET['txtFile']);
                } else if($editDrawing){
                    echo edit_file($_POST['editFile']['oldName'], $_POST['editFile']['newName'], $_POST['editFile']['art']);
                }
            ?>
        </div>
    </div>
    <!-- /#page-content-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Bootstrap core JavaScript -->
<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- Menu Toggle Script -->
<script>
    const toggleMenu = document.querySelector('#menu-toggle');
    const iconToggle = document.querySelector('#icon-toggle');
    const wrapper = document.querySelector('#wrapper');
    const explore = document.querySelector('#explore');
    const create = document.querySelector('#create');
    const upload = document.querySelector('#upload');
    
    toggleMenu.addEventListener('click', function(e){
        e.preventDefault();
        if(iconToggle.classList.contains('fa-chevron-left')){
            iconToggle.classList.remove('fa-chevron-left');
            iconToggle.classList.add('fa-chevron-right');
        } else {
            iconToggle.classList.remove('fa-chevron-right');
            iconToggle.classList.add('fa-chevron-left');
        }
        wrapper.classList.toggle('toggled');
    });

    explore.addEventListener('click', function(e){
        if(e.target.nodeName == 'A'){
            fileMenu = document.querySelector('#fileMenu');
            fileMenu.classList.toggle('hide')
        }
    })

    create.addEventListener('click', function(e){
        if(e.target.nodeName == 'A'){
            fileForm = document.querySelector('#fileForm');
            fileForm.classList.toggle('hide');
        }
    })

    upload.addEventListener('click', function(e){
        if(e.target.nodeName == 'A'){
            uploadForm = document.querySelector('#uploadForm');
            uploadForm.classList.toggle('hide');
        }
    })
</script>

</body>

</html>