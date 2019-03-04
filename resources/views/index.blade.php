@extends('layouts.app')

@section('body')
<section class="hero-area text-center  bg-white" style="min-height: 100% !important;" id="landing-page-section-1">
	<!-- Container Start -->
	<div class="container">
		<div class="row">

			<div class="col-md-12 text-center">
				<!-- Header Contetnt -->
				<div class="content-block">
				<img src="{{baseUrlConcat('/classimax/images/logos/o-logo-w-tagline.png')}}" style="width: 70%; height: auto">
					
				</div>
				<!-- Advance Search -->
				<a href="/#how-it-works" class="btn-lg theme-background">How it Works</a>

			</div>
		</div>
	</div>
	<!-- Container End -->
</section>

<!--===================================
=            Client Slider            =
====================================-->


<!--===========================================
=            Popular deals section            =
============================================-->

<section class="popular-deals section bg-gray theme-background" id="how-it-works">
	<div class="container ">
		<div class="row">
			<div class="col-md-12 mb-0">
				<div class="section-title text-center">
					<h2 class="text-white">How it works</h2>
					<p class="text-white">1st, find a service that you use often or just need in general</p>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- offer 01 -->
			<div class="col-6 offset-3 mb-4 mt-0 round-5">
				<span class="fa fa-search fa-2x theme-color" style="position: relative; top: 40px; left: 10px"></span>
				<input class="form-control bg-white theme-color text-center" type="text" placeholder="nails" readonly="">
			</div>
<div class="col-sm-12 col-lg-4 offset-lg-4 hidden-sm hidden-xs">
				<!-- product card -->
	<div class="product-item bg-light">
		<div class="card">
			<div class="thumb-content">
				<!-- <div class="price">$200</div> -->
				<a href="">
					<img class="card-img-top img-fluid" style="box-shadow: 2px 2px 5px black" src="{{baseUrlConcat('/classimax/images/sample_nails.png')}}" alt="Card image cap">
				</a>
			</div>
		</div>
	</div>

	<div class="text-center"><a href="#second-section" class="btn bg-white theme-color m-2">Next Section</a></div>
</div>

			
	</div>
</div>
</section>

<section class="popular-deals section bg-gray bg-white" id="second-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-0">
				<div class="section-title">
					<p class="theme-color">2nd, choose whether you want to pay monthly or annually</p>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- offer 01 -->
			<div class="col-sm-12 col-lg-4 offset-lg-4 hidden-sm hidden-xs">
				<!-- product card -->
				<div class="product-item bg-light">
					<div class="card">
						<div class="thumb-content">
							<!-- <div class="price">$200</div> -->
							<a href="">
								<img class="card-img-top img-fluid" style="box-shadow: 2px 2px 5px black" src="{{baseUrlConcat('/classimax/images/subscribe_sample.png')}}" alt="Card image cap">
							</a>
						</div>
					</div>
				</div>
			</div>


		</div>
	</div>
	<div class="text-center"><a href="#third-section" class="btn theme-background m-2">Next Section</a></div>
</section>


<section class="popular-deals section bg-gray theme-background" id="third-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-0">
				<div class="section-title">
					<p class="text-white">Lastly, when you're ready to use it, go to your subscriptions and hit check-in.</p>
				</div>
			</div>
		</div>
		<div class="row">
			<!-- offer 01 -->
			<div class="col-sm-12 col-lg-4 offset-lg-4 hidden-sm hidden-xs">
				<!-- product card -->
				<div class="product-item bg-light">
					<div class="card">
						<div class="thumb-content">
							<!-- <div class="price">$200</div> -->
							<a href="">
								<img class="card-img-top img-fluid" style="box-shadow: 2px 2px 5px black" src="{{baseUrlConcat('/classimax/images/checkin_lg.png')}}" alt="Card image cap">
							</a>
						</div>
					</div>
				</div>
			</div>


		</div>
	</div>
</section>


<!--==========================================
=            All Category Section            =
===========================================-->

<section class=" section">
	<!-- Container Start -->
	<div class="container">
		<div class="row">
			<div class="col-12">
				<!-- Section title -->
				<div class="section-title">
					<h2>Simple :)<br>
					Now get to subscribing!</h2>
				</div>
				<div class="row">
					<!-- Category list -->

					
				</div>
			</div>
		</div>
	</div>
	<!-- Container End -->
</section>


@endsection





