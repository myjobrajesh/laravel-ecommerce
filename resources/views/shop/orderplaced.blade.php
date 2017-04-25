@extends('layouts/app')
    @section('topLeftMenuLabel')
		<i class="fa fa-search"></i> <strong>Shop</strong>
	@endsection
@section('content')

		<div class="row " >
			<div class="col-md-12 col-sm-12 col-xs-12" ng-controller="content">
				<div class="row" >
                	<div class="col-md-12 col-sm-12 col-xs-12" >
						<div id="list" class="row"  >
                            <div class="col-md-12 col-sm-12 col-xs-12">
                               <div class=" roundedBox text-center" >
                                   Your order was processed successfully<br>
                                    <p><strong>Order No : {{session()->get('orderId')}}</strong></p>
                                    <p>&nbsp;</p>
                                    <p><a href="{{ route("shop") }}" class="btn btn-primary btnSmall">Continue Shopping</a></p>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('jsSection')
    
    <script>
        ngApp.controller('content', function ($scope, $http, $compile, Scopes, contentShopFactory) {
        
            Scopes.store('content', $scope);
			$scope.token = '{{csrf_token()}}';
			$scope.category = '';//TODO
			$scope.singleView = '';//TODO 
			
	
		});
    </script>
@stop
