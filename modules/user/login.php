<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Περιφέρεια Δυτικής Μακεδονίας - Είσοδος</title>
	
    <link href="<?=URL?>/assets/lib/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=URL?>/assets/css/sb-admin-2.css" rel="stylesheet">
    <link href="<?=URL?>/assets/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

    <div class="container">
        <div class="row">
			<div id="headerlogin">
				<a href="index.php"/><img id="headerlogo" src="<?=URL?>/assets/img/logo.png" alt="Περιφέρεια Δυτικής Μακεδονίας" /></a>
			</div>
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Σύνδεση Χρήστη</h3>
                    </div>
                    <div class="panel-body">
						<?php echo user_get_login_url(array('btn', 'btn-lg', 'btn-success', 'btn-block'));?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?=URL?>/assets/lib/jquery/dist/jquery.min.js"></script>
    <script src="<?=URL?>/assets/lib/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>