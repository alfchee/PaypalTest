<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instacolin | Crawler</title>

     <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
      
    <link rel="stylesheet" type="text/css" href="../css/main.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- Begin of menu -->
    <nav class="navbar navbar-inverse navbar fixed top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="#" class="navbar-brand">Instacolin</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#">PDF</a></li>
                    <li><a href="ajaxtest.php">AJAX Test</a></li>
                    <li class="active"><a href="crawler/crawler.html">Crawler</a></li>
                    <li><a href="<?php echo route('payments.checkout') ?>?points=20">Payments</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End of menu -->

    <!-- Begin of content -->
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 search">
                <form class="form-inline" action="/crawler/query" method="POST">
                    <div class="form-group">
                        <label for="url">URL:</label>
                        <input type="text" class="form-control" name="url" id="url" />    
                    </div>
                    <button id="search-submit" type="submit" class="btn btn-primary"><i class="icon-search"></i> Search</button>
                </form>
            </div>
        </div>
        <div class="row" style="margin-top: 40px; margin-bottom: 60px;">
            <p>
                <a href="#" id="add-site-manager" class="btn btn-primary">Add to Site Manager</a>
            </p>
            <div class="table-responsive">
                <table id="result" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="3%"></th>
                            <th width="50%"><span>URL</span></th>
                            <th><span>Equity Links</span></th>
                            <th><span>Links</span></th>
                            <th><span>MozRank: URL</span></th>
                            <th><span>HTTP Status Code</span></th>
                            <th><span>Page Authority</span></th>
                            <th><span>Domain Authority</span></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>


    <!-- Vendor Scripts -->
    <script type="text/javascript" src="../js/jquery.min.js" ></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../js/crawlr.js"></script>
</body>
</html>