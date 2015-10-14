<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instacolin | Order Confirmation</title>

     <!-- Bootstrap -->
    <link href="../css/bootstrap.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700' rel='stylesheet' type='text/css'>
      
    <link rel="stylesheet" type="text/css" href="../css/payment.css">

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
                    <li><a href="crawler/crawler.html">Crawler</a></li>
                    <li class="active"><a href="<?php echo route('payments.checkout') ?>?points=20">Payments</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End of menu -->

    <!-- Begin of content -->
    <div class="container">
        <div id="checkout-main">
            <div class="checkout-inner">
                <div class="checkout-left-pane">
                    <div id="cart" class="checkout-pane col-md-12">
                        <div class="inner">
                            <h1>Order Confirmation</h1>
                        </div>
                        <div class="cart-item">
                            <div class="well <?php echo $type == 'success'? 'bg-success' : 'bg-danger' ?>">
                                <p><?php echo $message ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="checkout-right-pane">
                    
                </div>
            </div>
        </div>
    </div>


    <!-- Vendor Scripts -->
    <script type="text/javascript" src="../js/jquery.min.js" ></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/payments.js"></script>
</body>
</html>