@extends('back/organization/layouts/master')

@section('title')
	Payment Methods
@endsection

@section('one-step')
    / Payment Methods
@endsection

@section('content')
	<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-statistic-4">
              <div class="align-items-center justify-content-between">
                <div class="row ">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                    <div class="card-content">
                        <a href="{{ route('org.pay-with-flutterwave') }}">
                            <!-- <h3 class="font-15">Flutter Wave</h3> -->
                            <div class="banner-img">
                              <img src="{{ asset('assets/img/banner/flutterwave.png') }}" alt="">
                            </div>
                        </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    {{--
    <!-- <div class="col-lg-3">
        <div class="card">
            <div class="card-statistic-4">
              <div class="align-items-center justify-content-between">
                <div class="row ">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                    <div class="card-content">
                        <a href="{{ route('org-coopbank') }}">
                            <div class="banner-img">
                              <img src="{{ asset('assets/img/banner/co-op.png') }}" alt="">
                            </div>
                        </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div> -->
     --}}
  </div>
@endsection
