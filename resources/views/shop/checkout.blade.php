@extends('layouts.app')

@section('topLeftMenuLabel')
		<i class="fa fa-search"></i> <strong>Checkout</strong>
	@endsection


@section('content')
		<div class="row " >
			<div class="col-md-12 col-sm-12 col-xs-12" ng-controller="content">
				
                <div class="row" >
					<div class="col-md-12 col-sm-12 col-xs-12" >
                        <div class=" roundedBox clearfix" >
                        <h4  class="col-sm-12 col-md-12">Checkout<span> : {{ $count }}</span></h4>

                            @include('shop.partials.cart_table', ['pageType' => 'checkout'])
                            
                            <a href="{{ route('shop') }}" class="btn btn-primary btnSmall">Continue Shopping</a>
                            <a href="{{ route('shop.cart') }}" class="btn btn-default  btnSmall">Cart</a>
                            <br><br><br><br>
                
                            @if($count)
                            <div id="checkoutSteps">
                                
                                <div id="addressStep" >
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="panel panel-default" id="shippingAdressStep">
                                                <div class="panel-heading">Shipping information</div>
                                                <div class="panel-body" ng-show="currentStep == 'addressStep'">
                                                @include('shop.partials.checkout.shipping_address')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($checkoutSteps['shippingMethods'])
                                <div id="shippingStep" >
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="panel panel-default" >
                                                <div class="panel-heading">Select Shipping method</div>
                                                <div class="panel-body" ng-show="currentStep == 'shippingStep'">
                                                @include('shop.partials.checkout.shipping_method')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($checkoutSteps['paymentMethods'])
                                <div id="paymentStep" >
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="panel panel-default" >
                                                <div class="panel-heading">Select Payment method</div>
                                                <div class="panel-body" ng-show="currentStep == 'paymentStep'">
                                                @include('shop.partials.checkout.payment_method')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div id="confirmOrderStep" >
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="panel panel-default" >
                                                <div class="panel-heading">Confirm Order</div>
                                                <div class="panel-body" ng-show="currentStep == 'confirmOrderStep'">
                                                @include('shop.partials.checkout.confirm_order')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>            
@stop

@section('jsSection')
    <script>
	    ngApp.controller('content', function ($scope, $http, $compile, Scopes, contentShopFactory) {
            Scopes.store('content', $scope);
			$scope.token = '{{csrf_token()}}';
			$scope.currentStep = 'addressStep';
            $scope.isShipping   =   '{{$checkoutSteps['shippingMethods'] ? true : false}}';
            $scope.shipping = {};
            //old data
            var existingAddress = {!!json_encode($address)!!};
            if(existingAddress) {
                angular.forEach(existingAddress, function(v, k) {
                    $scope.shipping[k] = v;
                    if(k=='chk_billing_same' && v==1) {
                        $("#chk_billing_same").trigger("click");
                        $("#billingAdressRow").hide();
                    }
                });
            }

            //hide show biiling address
            $("#chk_billing_same").on("click", function() {
                if(this.checked) {
                    $("#billingAdressRow").hide();
                } else {
                    $("#billingAdressRow").show();
                }
            });

            //shipping submit
            $scope.submitAddress = function($isValid) {
                
                //validate billing address
                var validateBilling = true;
                if(!($("#frmAddress input[name='chk_billing_same']").prop("checked"))) {
                    if(angular.element("input[name='billing_first_name']").val() &&
                        angular.element("input[name='billing_last_name']").val() &&
                        angular.element("input[name='billing_address']").val() &&
                        angular.element("select[name='billing_country']").val() &&
                        angular.element("input[name='billing_state']").val() &&
                        angular.element("input[name='billing_city']").val() &&
                        angular.element("input[name='billing_zip']").val() ) {
                            validateBilling = true;
                    } else {
                            validateBilling = false;
                    }
                }
                
                if($isValid && validateBilling) {
                    $scope.addressLoading = true;
                    var frmId = angular.element("#frmAddress");
                
                    var formData = {};
                    angular.forEach(frmId.serializeArray(), function(value, key){
                        formData [value.name] = value.value;
                    });

                    contentShopFactory.addressSave(formData).success(function(data){
                        $scope.addressLoading = false;
                        $scope.currentStep = $scope.isShipping ? "shippingStep" : 'paymentStep';
                        
                    });
                }

            }
            
            $scope.submitShipping = function($isValid) {
                
                if($isValid) {
                    $scope.shippingLoading = true;
                    var frmId = angular.element("#frmShipping");
                
                    var formData = {};
                    angular.forEach(frmId.serializeArray(), function(value, key){
                        formData [value.name] = value.value;
                    });

                    contentShopFactory.shippingSave(formData).success(function(data){
                        $scope.shippingLoading = false;
                        $scope.currentStep = 'paymentStep';
                        
                    });
                }
            }
            
            $scope.submitPayment = function($isValid) {
                
                if($isValid) {
                    $scope.paymentLoading = true;
                    var frmId = angular.element("#frmPayment");
                
                    var formData = {};
                    angular.forEach(frmId.serializeArray(), function(value, key){
                        formData [value.name] = value.value;
                    });

                    contentShopFactory.paymentSave(formData).success(function(data){
                        $scope.paymentLoading = false;
                        $scope.currentStep = 'confirmOrderStep';
                        console.log(data);
                        if(data.payment) {
                            $(".selectedPaymentMethod .method_label").html(data.payment.label);
                            $(".selectedPaymentMethod .price").html((data.payment.price) ? data.payment.price : '0.00');
                        }
                        $(".grandTotalRow .price").html(data.order.grandTotal);
                        
                    });
                }
            }
            
            //confirm order submit
            /*$scope.submitConfirmOrder = function() {
                
                $scope.coLoading = true;
                var frmId = angular.element("#frmConfirmOrder");
            
                var formData = {};
                angular.forEach(frmId.serializeArray(), function(value, key){
                    formData [value.name] = value.value;
                });

                contentShopFactory.confirmOrderSave(formData).success(function(data){
                    $scope.coLoading = false;
                    //redirect to order thank you
                    
                });
                
            }*/
            
        });
    </script>
@stop
