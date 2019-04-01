@extends('layouts.app')

@section('body')
@php $count = 1; @endphp
@foreach($sections as $section)
    <section class="sell-with-o-section popular-deals section bg-gray" id="section-{{$count}}">
        <div class="container ">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <div class="section-title text-center">
                        @if(!empty($section['first'])) <h2 class="text-white">{{$section['first']}}</h2> @endif
                        <p class="sect-message">{!! $section['msg'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-lg-8 offset-lg-2 hidden-sm hidden-xs">
                    <!-- product card -->
                    <div class="product-item">
                        <div class="">
                            <div class="thumb-content">
                                <a href="{{$section['photoPath']}}" data-lity>
                                    <img class="card-img-top img-fluid" style="box-shadow: 2px 2px 5px black" src="{{$section['photoPath']}}" alt="Card image cap">
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="text-center"><a href="#section-{{++$count}}" class="btn bg-white theme-color m-2">Next Section</a></div>
                </div>


            </div>
        </div>
    </section>
@endforeach

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





