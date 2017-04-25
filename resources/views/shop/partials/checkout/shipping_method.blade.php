

                    <form id="frmShipping" name="frmShipping" role="form" autocomplete="off" novalidate ng-submit="submitShipping(frmShippingMethod.$valid)" >
                        {!! csrf_field() !!}

                        <div class="col-md-12">
                            <div class="form-group">
                                <ul class="list-unstyled">
                                    @foreach($checkoutSteps['shippingMethods'] as $method)
                                    <li>
                                        <input type="radio" name="shipping_method" value="{{$method['name']}}">&nbsp; {{$method['label']}}
                                        @if($method['price'])
                                        <span class="pull-right">{{config('app.defaultCurrencySign')}} {{\CommonHelper::numberFormat($method['price'])}}</span>
                                        @endif
                                    </li>
                                     @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" ng-show="!shippingLoading" name="submit" class='btn btnSmall' value="Submit">
                                <i class="fa fa-spinner" ng-show="shippingLoading"></i>
                                <input type="button" ng-click="currentStep='addressStep'" name="back" class='btn btnSmall btnGrey' value="Back">
                            </div>
                            
                        </div>
                    </form>