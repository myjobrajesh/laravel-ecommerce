@extends('layouts/app')
    @section('topLeftMenuLabel')
		<i class="fa fa-search"></i> <strong>Shop</strong>
	@endsection
@section('content')

		<div class="row " >
			<div class="col-md-12 col-sm-12 col-xs-12" ng-controller="content">
				<div class="row" >
                	<div class="col-md-12 col-sm-12 col-xs-12" page-scroll="getList()">
						<div id="list" class="row"  >
                        @if(count($products)==0)
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class=" roundedBox text-center" >
                            No product found
                            </div>
                            </div>
                        @endif
                        @include("shop/productlist", array('products'=>$products))
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('jsSection')
    {!! \Minify::stylesheet('/css/rating.css') !!}
    {!! \Minify::javascript('/js/rating.js') !!}
    
    <script src="{{ asset('/js/masonry.pkgd.min.js') }}"></script>
    <script>
        ngApp.controller('content', function ($scope, $http, $compile, Scopes, contentShopFactory) {
        
            Scopes.store('content', $scope);
			$scope.token = '{{csrf_token()}}';
			$scope.category = '';//TODO
			$scope.singleView = '';//TODO 
			
			$scope.getList = function (refresh, isPageLoad) {
				if (refresh) {
					$scope.nextPage = null;//reinitialize
				}
                if (isPageLoad) {
                    $scope.masonryLoad();//load firsttime
                } else {
                    contentShopFactory.getProducts($scope.token, 'shop').success(function(data){
                        //for first page
                        if (!$scope.nextPage) {
							var stampHtml = '';
                            $('.stamp').each(function(k, v){
                                stampHtml+=v.outerHTML;
                            });
                            $('#list').html('');
                            var $el = $(stampHtml).appendTo('#list');
                            $compile($el)($scope);
                        }
                        var $el2 = $(data).appendTo('#list');
                        $compile($el2)($scope);
                        $scope.masonryLoad();
			        });
                }
            }
            masonryLoad($scope);
            $scope.getList(true, true);//load firsttime

		});
    </script>
@stop
