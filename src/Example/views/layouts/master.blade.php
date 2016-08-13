<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Talk | Bootaide Template</title>
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
		
	</head>
	<body>
		<!--[if lt IE 10]>
		<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->

		<header class="bg-white navbar-fixed-top box-shadow">
			<div class="container">
				<div class="navbar-header">
					<button class="btn btn-link visible-xs pull-right m-r m-t-sm" type="button" data-toggle="collapse" data-target=".navbar-demo-4">
						<i class="fa fa-bars"></i>
					</button>
					<a href="." class="navbar-brand m-r-sm"><img src="img/logo.png" class="m-r-sm hide"><span class="h4 font-bold">Bootaide</span></a>
				</div>
				<div class="collapse navbar-collapse navbar-demo-4">
					
					<!-- search form -->
					<form class="navbar-form navbar-left m-v-sm">
						<div class="form-group">
							<div class="input-group">
								<input type="text" class="form-control input-sm bg-light" placeholder="Search">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-sm bg-light">
										<i class="fa fa-search"></i>
									</button> 
								</span>
							</div>
						</div>
					</form>
					<!-- / search form -->

					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
				            <a href class="dropdown-toggle" data-toggle="dropdown">
				            	<i class="icon-bell fa-fw"></i>
				                <span class="visible-xs-inline">Notifications</span>
				                <span class="badge badge-sm up bg-danger">2</span>
				            </a>
				            <!--dropdown -->
				            <div class="dropdown-menu w-xl">
				            	<div class="panel bg-white">
					                <div class="panel-heading b-light bg-light">
					                	<strong>You have <span>2</span> notifications</strong>
					                </div>
					                <div class="list-group">
					                	<a class="list-group-item" href>
						                    <span class="pull-left thumb-sm m-r-sm">
						                    	<img src="imgs/a0.jpg" alt="..." class="img-circle">
						                    </span>
						                    <span class="block m-b-none">
						                    	Panic message<br>
						                    	<small class="text-muted">13 minutes ago</small>
						                    </span>
					                    </a>
					                    <a class="list-group-item" href>
						                    <span class="block m-b-none">
						                    	First commit<br>
						                    	<small class="text-muted">1 hour ago</small>
						                    </span>
					                    </a>
					                </div>
					                <div class="panel-footer text-sm">
					                	<a class="pull-right" href><i class="fa fa-cog"></i></a>
					                	<a href>See all the notifications</a>
					                </div>
				              	</div>
				            </div>
				            <!--/ dropdown -->
				        </li>
						<li class="dropdown">
							<a href class="dropdown-toggle clear" data-toggle="dropdown"> 
								<span class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm"> 
									<img src="imgs/a0.jpg" alt="..."> 
									<i class="on md b-white bottom"></i> 
								</span> <span class="hidden-sm hidden-md">Gifaree.Evan</span>
							</a>
							<!--dropdown -->
							<ul class="dropdown-menu w">
								<li>
									<a href> <span class="badge bg-danger pull-right">30%</span> <span>Settings</span> </a>
								</li>
								<li>
									<a href>Profile</a>
								</li>
								<li>
									<a href> <span class="label bg-info pull-right">new</span> Help </a>
								</li>
								<li class="divider"></li>
								<li>
									<a href>Logout</a>
								</li>
							</ul>
							<!--/ dropdown -->
						</li>
					</ul>
				</div>
			</div>
		</header>
		
		
		<div class="pos-abt w-full" style="top:50px;bottom:0">
			<div class="container h-full">
				<div class="row h-full">
					@include('talk::partials.inbox')
					</div>
					<div class="col-sm-6 bg-white h-full p-h-none">
						<div class="item">
							<div class="top bg-light dker w-full wrapper b-b">
								{{--<h6 class="inline p-t-xs m-t-xxs m-b-none"><a class="font-semi-bold" href="">Natai Baba</a></h6>--}}
								<div class="btn-group pull-right">
								  
								  
								  <!--more msg stuff -->
								  <div class="btn-group pull-right">
									  <button type="button" class="btn btn-default btn-sm" data-toggle="dropdown">
									    <i class="icon-settings"></i>
									  </button>
                                      @if(isset($id))
									  <ul class="dropdown-menu">
                                          <li><a href="{{url('laravel-talk/example/conversation/delete/'. @$id)}}">Delete Conversation</a></li>
                                      </ul>
                                      @endif
								  </div>

								  <!--/ more msg stuff -->


								</div>
							</div>
				@yield('body')
						</div>
					</div>
					<div class="col-sm-3 bg-light lt h-full b clear niceScroll">
						<div class="p-t">
					      <div class="panel b-light clearfix">
					        <div class="panel-body">
					          <a href="" class="thumb pull-left m-r">
					            <img src="imgs/a1.jpg" class="img-circle">
					          </a>
					          <div class="clear">
					            <a href="" class="text-info font-semi-bold">Hasin Hayder</a>
					            <small class="block text-muted">2,415 followers</small>
					            <a href="" class="btn btn-xs btn-info m-t-xs">Follow</a>
					          </div>
					        </div>
					      </div>
					    </div>
					</div>
				</div>
			</div>
		</div>		
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script src="http://zafree.github.io/bootaide/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="http://zafree.github.io/bootaide/bower_components/headroom.js/dist/headroom.min.js"></script>
		<script src="http://zafree.github.io/bootaide/bower_components/owl-carousel2/dist/owl.carousel.min.js"></script>
		<script src="http://zafree.github.io/bootaide/dist/js/bootaide.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#talk-inbox').getNiceScroll(0).doScrollTop($('#talk-conversations').height());
            })
        </script>
	</body>
</html>
