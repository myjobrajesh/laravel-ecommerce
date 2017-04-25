@extends('layouts/app')
    @section('topLeftMenuLabel')
		<i class="fa fa-search"></i> <strong>Shop</strong>
	@endsection
@section('content')

		<div class="row " >
			<div class="col-md-12 col-sm-12 col-xs-12" ng-controller="content">
				<div class="row" >
					<div class="col-md-12 col-sm-12 col-xs-12" >
						<div  class="row"  >
                        @if(count($product)==0)
                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class=" roundedBox text-center" >
                            No product found
                            </div>
                            </div>
                        @endif

                            <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="roundedBox" style="height:auto">
                                <div class="row">
                                <div class="col-sm-6 col-md-6 col-xs-12">
                                @if ($product->photos->count() === 0)
                                    <span class="productImage"><img src="/imgs/no-image-found.jpg" alt="No Image Found Tag" id="Product-similar-Image" class="img-responsive"></span>
                                @else
                                    @if ($product->featuredPhoto)
                                        <span class="productImage"><img src="{{ \ImageHelper::viewFile($product->featuredPhoto->filepath, 'sm') }}" class="img-responsive" /></span>
                                    @elseif(!$product->featuredPhoto)
                                        <span class="productImage"><img src="{{ \ImageHelper::viewFile($product->photos->first()->filepath, 'sm')}}" class="img-responsive" /></span>
                                    @else
                                        N/A
                                    @endif
                                    @if($product->photos)
                                    <ul class="list-inline list-unstyled productImageList" >
                                        @foreach($product->photos as $photo)
                                        <li class="col-sm-2 col-md-2 col-xs-2">
                                            <img src="{{ \ImageHelper::viewFile($photo->filepath, 'th') }}" class="img-responsive aClick" ng-click="dispProductImage('{{ \ImageHelper::viewFile($photo->filepath, 'sm') }}')" />
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                @endif
                                </div>
                                <div class="col-sm-6 col-md-6 col-xs-12">
                                    <h3 class="productName" >{{ $product->product_name }}</h3>
                                    <span class="starRating col-sm-12" id="rateHtmlDisplay"></span>
                                    <span class="productPrice">
                                    Price :<span class="priceTag">
                                        <%displayCurrency%><%displayPrice%>
                                        </span>
                                    </span><br>
                                    <div class="attributeDisp">
                                    <form id="frmAttribute">
                                        <table id="attributeTable"></table>
                                    </form>
                                    </div>
                                    <span>Product related queries</span>
                                    <span><a href="{{route('faq')}}" target="_blank" class=" btn btnSmall">FAQ</a> or
                                        <button class="btn btnSmall aClick"  data-toggle="modal" data-target="#modalAskAQuestion">Ask a Question</button>
                                    </span>
                                    @if ($product->product_qty == 0)
                                        <h5 class="red-text">Sold Out</h5>
                                        {{--<p class="text-center"><b>Available: {{ $product->product_qty }}</b></p>--}}
                                    @else
                                        <form  method="post" name="add_to_cart" id="addToCart">
                                            <input type="hidden" name="product" value="{{$productHashId}}" />
                                            <label>QTY</label>
                                            <select name="qty" class="form-control" id="Product_QTY" title="Product Quantity" >
                                                @for ($i = 1; $i <= config('app.shopQtySelection'); $i++)
                                                    <option value="{{$i}}" >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <br><br>
                                            {{--<p><b>Available: {{ $product->product_qty }}</b></p>--}}
                                            <button class="btn btnSmall" ng-click="addToCart()">ADD TO CART</button>
                                        </form>
                                    @endif
                                </div>
                                </div>
                                <div class="row productDescPanel">
                                <div class="col-sm-12 col-md-12 col-xs-12 panelRow">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#product_description" aria-controls="home" role="tab" data-toggle="tab">DESCRIPTION</a></li>
                                                <li role="presentation"><a href="#product_spec" aria-controls="profile" role="tab" data-toggle="tab">SPECS</a></li>
                                            </ul>
                        
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="product_description">
                                                    {!! $product->description !!}
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="product_spec">
                                                    {!! $product->product_spec !!}
                                                </div>
                                            </div>
                                </div>
                                <!-- measurement -->
                                @include('shop.partials.measurement')
                                <!-- measurement end -->

                                <!-- customer reviews -->
                                @include('shop.partials.reviews', ["reviews"=>$reviews])
                                <!--customer review end -->
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('shop.partials.askquestion')    
            </div>
        </div>
@endsection
@section('jsSection')
    {!! \Minify::stylesheet('/css/rating.css') !!}
    {!! \Minify::javascript('/js/rating.js') !!}
    <script src="{{ asset('/js/masonry.pkgd.min.js') }}"></script>
    <script>
		var attributes = {!!json_encode($attributes)!!};
        //console.log((attributes));
        var tableId = 'attributeTable';

        ngApp.controller('content', function ($scope, $http, $compile, Scopes, contentShopFactory) {
		
            Scopes.store('content', $scope);
			$scope.token = '{{csrf_token()}}';
			$scope.category = '';//TODO
			$scope.singleView = '';//TODO
            $scope.pId = '{{$productHashId}}';
			$scope.product = {!!$product->toJson()!!};
            $scope.displayPrice = 0;
            $scope.originalPrice = 0;
            $scope.totalReview = '{{$totalReview}}';
            $scope.ratingByStar =   {!!$ratingByStar->toJson()!!};
            //console.log($scope.ratingByStar);
            $scope.displayCurrency = '{{config("app.defaultCurrencySign")}}';
            
            //rating, 
            $scope.ratingDisplay = function(html_class, rate_value, rate_times) {
                var rate_bg = ((rate_value)/5)*100;
                var html = '<div class="pull-left Fr-star '+ html_class +'"  data-rating="'+rate_value +'">';
                html += '<div class="Fr-star-value" style="width: '+ rate_bg +'%"></div>';
                html += '<div class="Fr-star-bg" ng-show="!rateLoading" ng-click="addRating($event, '+rate_bg+')"></div>';
                html += '</div><i class="fa fa-spinner" ng-show="rateLoading"></i>';
                //html += '<span class="textRating" style="color:grey; font-size:12px;">('+rate_times+' Ratings)</span><br>';
                html += '<span class="textRating; pull-left"> '+$scope.totalReview+' Reviews</span><br>';
                
                angular.element("#rateHtmlDisplay").html('');
				var $elc = $(html).appendTo("#rateHtmlDisplay");
				$compile($elc)($scope);
                
                $scope.ratingDisplayAtBottom(html_class, rate_value, rate_times);
            };
            
            $scope.ratingDisplayAtBottom = function(html_class, rate_value, rate_times) {
                var rate_bg = ((rate_value)/5)*100;
                var html = '<div class="pull-left Fr-star '+ html_class +'"  data-rating="'+rate_value +'">';
                html += '<div class="Fr-star-value" style="width: '+ rate_bg +'%"></div>';
                html += '<div class="Fr-star-bg" ng-show="!rateLoading" ng-click="addRating($event, '+rate_bg+')"></div>';
                html += '</div><i class="fa fa-spinner" ng-show="rateLoading"></i>';
                html += '<span class="textRating"> '+rate_times+'</span><br>';
                html += '<span class="textRating"> '+rate_value+' out of 5 stars <i class="caret" ng-click="ratingDetailHtml = !ratingDetailHtml"></i></span><br>';
                
                html += '<div ng-show="ratingDetailHtml" class="table-responsive" >';
                if($scope.ratingByStar) {
                    html += '<table class="table table-condensed">';
                    angular.forEach($scope.ratingByStar, function(value, key){
                        var percentage = Math.round((value.total/rate_times)*100);
                        html += '<tr>';
                        html += '<td style="width:50px; padding:0;">'+value.rating+' star </td>';
                        html += '<td style="padding:0;">';
                        
                        html += '<div class="progress" >';
                        html += '<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '+percentage+'%;">';
                        html += percentage+'%</div></div>';
                        
                        html += '</td>';
                        html += '</tr>';
                    });
                    html += '</table>';
                }
                
                html +='</div><div class="clearfix"></div>';
                
                angular.element("#ratingDisplayBottom").html('');
				var $elc = $(html).appendTo("#ratingDisplayBottom");
				$compile($elc)($scope);
            };
            
            
            $scope.addRating = function (obj, width) {
                $scope['rateLoading'] = true;
                var xCoor = obj.offsetX;
                var width = obj.target.offsetWidth;
                percent = (xCoor/width) * 100;
                if(percent < 101){
                  ratingDecimal = ("" + (percent / 100) * 5 + "").substr(0, 3);
                  if(ratingDecimal.substr(-2) == ".9"){
                        ratingDecimal = Math.round(ratingDecimal, 2);
                  }
                  contentShopFactory.addRating($scope.pId, ratingDecimal, $scope.token).success(function(data){
                      $scope['rateLoading'] = false;
                      //console.log(data);
                      $scope.ratingByStar = data.ratingByStar;
                      $scope.ratingDisplay('size-3', data.rating.average, data.rating.total);
                  });
                }
            };
            
            //review displays
            $scope.getReviewList = function (refresh, isPageLoad) {
                if (refresh) {
					$scope.nextPage = null;//reinitialize
				}
                if (isPageLoad) {
                    $scope.masonryLoad();//load firsttime
                } else {
                    contentShopFactory.getReviews($scope.pId, $scope.token).success(function(data){
                        //for first page
                        if (!$scope.nextPage) {
							//remove old 
							var stampHtml = '';
                            $('.stamp').each(function(k, v){
                                stampHtml+=v.outerHTML;
                            });
                            $('#reviewDisplayBottom').html('');
                            var $el = $(stampHtml).appendTo('#reviewDisplayBottom');
                            $compile($el)($scope);

                        }
                        var $el2 = $(data).appendTo('#reviewDisplayBottom');
                        $compile($el2)($scope);
                        $scope.masonryLoad();
			        });
                }
            };
            
            masonryLoad($scope);
            $scope.getReviewList(true, true);//load firsttime
            
            $scope.addReview = function () {	
                var frmId = angular.element('#frmReview');
                
                var formData = {};
                angular.forEach(frmId.serializeArray(), function(value, key){
                    formData [value.name] = value.value;
                });

                review = formData['review'];
                if(review) {
                    $scope['reviewLoading'] = true;
                    contentShopFactory.addReview($scope.pId, review, $scope.token).success(function(data){
                        $scope.reviewSucessMsg = true;
                        $("textarea[name='review']").val('');
                        $scope['reviewLoading'] = false;
                        //update review lists
                        angular.element("#reviewDisplayBottom").html('');
                        var $elc = $(data.reviews).appendTo("#reviewDisplayBottom");
                        $compile($elc)($scope);
                        $scope.nextPage = null;//reinitialize
                    });
                 }
            };
            //set scrollbar for reviews
            setScrollbar('reviewsScrollbar', {showOnHover:false});
            
            //default, page load
            $scope.ratingDisplay('size-3', '{{$rating->average}}', '{{$rating->total}}');
            
			$scope.addToCart = function () {	
                var frmId = angular.element('#addToCart');
                var mFrmId = angular.element("#measurement");
                var aFrmId = angular.element("#frmAttribute");
                
                var formData = {};
                angular.forEach(frmId.serializeArray(), function(value, key){
                    formData [value.name] = value.value;
                });
                
                formData ['totalPrice'] = $scope.displayPrice;
                
                var measurements = {};
                angular.forEach(mFrmId.serializeArray(), function(value, key){
                    measurements[value.name] = value.value;
                });
                formData['measurements'] = measurements;
                
                var attrs = {};
                angular.forEach(aFrmId.serializeArray(), function(value, key){
                    attrs[value.name] = value.value;
                });
                formData['attributes'] = attrs;
                //console.log(formData);
                contentShopFactory.addToCart(formData, $scope.token).success(function(data){
                      //console.log(data);
                      location.href= data.redirect;
                  });
            };
           
            $scope.setPriceToBeDisplay = function() {
                //console.log($scope.product.reduced_price);
                var price = $scope.product.price;
                if($scope.product.reduced_price && $scope.product.reduced_price!='0.00') {
                    price = $scope.product.reduced_price;
                }
                $scope.displayPrice = price;
                $scope.originalPrice = price;
                
                return price;
            }
        
            $scope.setPriceToBeDisplay();
            
            //atributes
            $scope.viewAttributes = function() {
                if(attributes) {
                    $.each(attributes, function(attrKey, attrVal) {
                        var html = '<tr>';
                        html += '<td><select class="selAttrName" data-key="'+attrKey+'" name="'+attrKey+'" >';
                        html += '<option value="">Select '+attrKey+'</option>';
                        $.each(attrVal, function(k,v) {
                            html += '<option value="'+v.value+'">'+v.value+'</option>';
                        });
                        html += '</select></td></tr>';
                        $("#"+tableId).append(html);
                    });
                    
                    $(".selAttrName").on("change", function(e) {
                        $scope.updatePriceByAttribute();
                    });
                }
            }
            
            $scope.updatePriceByAttribute = function() {
                
                var originalPrice = $scope.originalPrice;
                var selectedValue = 0;
                //get selected attributes values
               // $.each($("select[name^=attr_name]"), function(k,v) {
               $.each($(".selAttrName"), function(k,v) {
               
                    key = $(this).data("key");
                    value = $(this).val();
                    
                    $.each(attributes[key], function(k,v) {
                        if(v.value == value ) {
                            //update price
                            var priceToBeAdded = 0;
                            if(v.price_variant) {
                                priceToBeAdded = (v.price_variant == 'fixed') ? v.price :((originalPrice*v.price)/100);
                                //console.log("add="+priceToBeAdded);
                            }
                            selectedValue += parseFloat(priceToBeAdded); 
                        }
                    });
                });
                selectedValue = parseFloat(originalPrice) + parseFloat(selectedValue);
                //console.log("final="+selectedValue);
                setTimeout(function () {
                    $scope.displayPrice = selectedValue.toFixed(2);
                    $scope.$apply();
                }, 10);
            }
        
            $scope.viewAttributes();
            
            //ask a question form submit
            $scope.submitAskAQuestion = function ($isValid) {
                if($isValid) {
                    $scope.aaqLoading = true;
                    contentShopFactory.askAQuestion($scope.pId, $scope.askquestion.question, $scope.token).success(function(data){
                        if(data.success) {
                            $scope.aaqSucessMsg = true;
                            $scope.askquestion.question = '';
                        } else {
                            $scope.aaqSucessMsg = false;
                        }
                        $scope.aaqLoading = false;
                  });
                }
            }
        
            //display big images
            $scope.dispProductImage = function(path) {
                angular.element(".productImage").find("img").attr("src", path);
            };
        });
        
    </script>
@stop
