<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Talk | Bootaide Theme</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="/favicon.ico">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" />
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.3.2/css/simple-line-icons.min.css" />
	    <link rel="stylesheet" href="http://zafree.github.io/bootaide/bower_components/fontface-source-sans-pro/css/source-sans-pro.min.css"/>
	    <link rel="stylesheet" href="http://zafree.github.io/bootaide/bower_components/owl-carousel2/dist/assets/owl.carousel.min.css" />
	    <link rel="stylesheet" href="http://zafree.github.io/bootaide/bower_components/owl-carousel2/dist/assets/owl.theme.default.min.css" />
		<link rel="stylesheet" href="http://zafree.github.io/bootaide/dist/css/bootaide.min.css" />
	    
	    <link rel="stylesheet" href="http://zafree.github.io/bootaide/dist/css/bootaide.min.css" />
	</head>
	<body>
		<!--[if lt IE 10]>
		<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		
		<header class="bg-white navbar-fixed-top box-shadow">
			<div class="container">
				<div class="navbar-header">
					<button class="btn btn-link visible-xs pull-right m-r m-t-sm" type="button" data-toggle="collapse" data-target=".navbar-collapse">
						<i class="fa fa-bars"></i>
					</button>
					<a href="." class="navbar-brand m-r-lg"><img src="img/logo.png" class="m-r-sm hide"><span class="h3 font-bold">Talk</span></a>
				</div>
			
			</div>
		</header>
		
		<section class="bg-info">
			<div class="container">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 p-v-xxl text-center">
						<h1 class="h1 m-t-xxl p-v-xxl">User Login</h1>
					</div>
				</div>
			</div>
		</section>
		
		
		<section>
			<div class="container">
				<div class="row">	
			
					
					
					<div class="col-lg-8 col-lg-offset-2">
						<div class="m-b-lg p-h m-t-lg">
				
				<form action="" method="post">
								
								<div class="form-group">
									<label>Email</label>
									<input type="text" class="form-control" name="email" placeholder="Enter email"
									required data-bv-notempty-message="The name is required and cannot be empty" data-bv-regexp-message="The name can only consist of alphabetical, number, dot and underscore" />
								</div>
								
								
								<div class="form-group">
									<label>Password</label>
									<input class="form-control" name="password" type="password" placeholder="Enter Password"
									required data-bv-notempty-message="The email is required and cannot be empty"
									data-bv-emailaddress-message="The input is not a valid email address" />
								</div>


					<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">
										Login
									</button>
								</div>
					</form>
						</div>	
					</div>
					
				</div>
				<p class="text-center m-t-xxl">learn about validator go to <a class="text-info" href="http://1000hz.github.io/bootstrap-validator/"> bootstrapValidator</a> </p>
				
			</div>
		</section>
		
		<footer class="bg-dark">
			<div class="container">
				<div class="row p-v m-t-md text-center">
					
					<p class="m-b-none">
						Build with <i class="fa fa-heart-o m-h-2x"></i> by <a href="https://www.facebook.com/zafree" target="_blank"> Zafree</a>
					</p>
					<p>
						Code licensed under <a href="https://github.com/zafree/bootaide/blob/master/LICENSE">MIT</a>, 
						documentation under <a href="https://creativecommons.org/licenses/by/3.0/" target="_blank">CC BY 3.0</a>.
					</p>
					<p>
						2015 &copy; Bootaide
					</p>
				</div>
			</div>
		</footer>
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="http://zafree.github.io/bootaide/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="http://zafree.github.io/bootaide/bower_components/headroom.js/dist/headroom.min.js"></script>
		<script src="http://zafree.github.io/bootaide/bower_components/owl-carousel2/dist/owl.carousel.min.js"></script>
		<script src="http://zafree.github.io/bootaide/dist/js/bootaide.min.js"></script>
		
		
	</body>
</html>
