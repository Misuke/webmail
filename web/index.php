<?php
require_once __DIR__ .'/../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webmail</title>
    <!-- Bootstrap CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Webmail</a>
        </div>
        <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
            <li class="active">
                <a href="#">Home</a>
            </li>
            <li>
                <a href="#">Help</a>
            </li>
        </ul>
        <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search..." />
        </form>
        </div>
    </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-md-2 sidebar">
                <ul class="nav nav-sidebar">
                    <li><a href="#">Compose</a></li>
                    <li class="active"><a href="#">Unseen</a></li>
                </ul>
            </div>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <div class="page-header">
                    <h1>Inbox</h1>
                    <audio id="speaker" src="" autoplay>
                        Your browser does not support the audio tag.
                    </audio>
                </div>
                <div id="page-content">
                    <p>No unseen messages.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/mailer.js"></script>
</body>
</html>