@extends('layouts.app')

@section('topLeftMenuLabel')
		<i class="fa fa-search"></i> <strong>Cart</strong>
@endsection

@section('content')
		<div class="row " >
			<div class="col-md-12 col-sm-12 col-xs-12" ng-controller="content">
				
                <div class="row" >
					<div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class=" roundedBox clearfix" >
                        <h4  class="col-sm-12 col-md-12">Your Cart
                        <span> : {{ $count }}</span></h4>
                        
                        @include('shop.partials.cart_table', ['pageType'=>'cart'])
                        
                        <p class="">
                        @if ($cart_total === 0)
                            <a href="{{ route("shop") }}" class="btn btn-primary btnSmall">Continue Shopping</a>
                        @else
                            <a href="{{ route("shop") }}" class="btn btn-primary btnSmall">Continue Shopping</a>
                            <a href="{{ route('shop.checkout') }}" class="btn btn-default btnSmall">Checkout</a>
                        @endif
                        </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
@stop

@section('jsSection')
    <script>
	    ngApp.controller('content', function ($scope, $http, $compile, Scopes, contentShopFactory) {
        
        });
    </script>
@stop
        