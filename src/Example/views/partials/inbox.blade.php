<div class="col-sm-3 h-full p-h-none b">
						<div class="item">
							<div class="top w-full box-shadow" style="z-index:1">
								<div class="bg-light dker font-semi-bold inline w-full p-v-xs">
									<ul class="nav navbar-nav">
										<li>
											<a href>Inbox</a>
										</li>
										<li>
											<a class="text-muted" href>Others</a>
										</li>
									</ul>
									<ul class="nav navbar-nav pull-right">
										<li class="dropdown">
											<a href class="dropdown-toggle clear" data-toggle="dropdown">More <span class="caret"></span></a>
											<!--dropdown -->
											<ul class="dropdown-menu w-sm pull-right">
												<li>
													<a href> <span class="badge bg-light pull-right">14</span> Unread</a>
												</li>
												<li>
													<a href> <span class="badge bg-danger pull-right">30</span> <span>Span</span> </a>
												</li>
												<li>
													<a href> <span class="badge bg-dark pull-right">3</span>  Trust </a>
												</li>
											</ul>
											<!--/ dropdown -->
										</li>
									</ul>
								</div>
								<div class="wrapper-sm bg-light b-b">
						          <div class="input-group">
						            <input class="form-control input-sm" placeholder="Search" type="text">
						            <span class="input-group-btn">
						              <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-search"></i></button>
						            </span>
						          </div>
						        </div>
							</div>
							<div class="center niceScroll" style="top:115px">
								<div class="box bg-light">
									<div class="col">
										<div class="list-group list-group-lg m-b-none r-none">
											@foreach($inbox as $msg)
								            <a href="{{url('laravel-talk/example/message/read/' . $msg->conv_id)}}" class="list-group-item clearfix inbox">
											
								              	<span class="clear">
								              		<small class="pull-right text-muted">{{$msg->created_at}}</small>
								                	<span class="clear text-ellipsis font-semi-bold">{{$msg->name}}</span>
								                	<small class="text-muted clear text-ellipsis">{{$msg->message}}</small>
								            	</span>
								            </a>
                                            @endforeach

								        </div>
									</div>
								</div>
							</div>
						</div>
