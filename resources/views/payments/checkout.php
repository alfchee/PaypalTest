<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instacolin | Checkout Order</title>

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
                    <li class="active"><a href="crawler/crawler.html">Crawler</a></li>
                    <li><a href="<?php echo route('payments.checkout') ?>?points=20">Payments</a></li>
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
                            <h1>Your cart</h1>
                        </div>
                        <div class="cart-item">
                            <div class="product-thumbnail">
                                <img src="http://placehold.it/150x185" class="img-responsive" />
                            </div>
                            <div class="product-description">
                                <h2>Points</h2>
                                <div class="product-info">
                                    <p>Points that the buyer is buying.</p>
                                </div>
                                <div class="price">
                                    <span><?php echo '$'.$points ?></span>
                                </div>
                                <div class="qty-line">
                                    <p>Quantity: <span class="qty"><?php echo $points ?></span></p>
                                </div>
                                <div class="remove">
                                    <i class="icon-trash"></i> Remove
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="checkout-right-pane">
                    <div id="cart-summary" class="checkout-pane">
                        <div class="summary-header">
                            <h2>Summary</h2>
                        </div>
                        <div class="inner">
                            <form action="<?php echo route('payments.placeorder') ?>" method="POST">
                                <div class="subtotal-order-recap">
                                    <div class="subtotal-field subtotal-line">
                                        <span class="pull-left">Sub Total:</span>
                                        <span class="pull-right price"><?php echo '$'.$points ?></span>
                                    </div>
                                    <div class="subtotal-line other-cost">
                                        <span class="pull-left">VAT:</span>
                                        <span class="pull-right price">$0.00</span>
                                    </div>
                                    <div class="subtotal-line other-cost">
                                        <span class="pull-left">Shipping:</span>
                                        <span class="pull-right price">$0.00</span>
                                    </div>
                                </div>
                                <div class="subtotal-line border-top total-order">
                                    <span class="pull-left">Total:</span>
                                    <span class="pull-right price"><?php echo '$'.$points ?></span>
                                    <input type="hidden" name="total" value="<?php echo $points ?>" />
                                </div>
                                <div class="accepted-payment border-top text-center">
                                    <h4>Accepted Payment Types</h4>
                                    <p>Paypal</p>
                                    <input type="hidden" name="currency" value="USD" />
                                    <input type="hidden" name="description" value="Points of the User." />
                                </div>
                                <div class="checkout-container text-center">
                                    <button type="submit" id="checkout-now" class="btn btn-lg btn-primary">Pay Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
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