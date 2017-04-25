
    
                    <form id="frmPayment" name="frmPayment" role="form" autocomplete="off" novalidate ng-submit="submitPayment(frmPayment.$valid)" >
                        {!! csrf_field() !!}

                        <div class="col-md-12">
                            <div class="form-group">
                                <ul class="list-unstyled">
                                    @foreach($checkoutSteps['paymentMethods'] as $method)
                                    <li>
                                        <input type="radio" name="payment_method" value="{{$method['name']}}" checked>&nbsp; {{$method['label']}}
                                        <span class="text-right">{{$method['price']}}</span>
                                        @if(isset($method['form']))
                                            @include('shop.partials.payment.'.$method['name'])
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" ng-show="!paymentLoading" name="submit" class='btn btnSmall' value="Submit">
                                <i class="fa fa-spinner" ng-show="paymentLoading"></i>
                                <input type="button" ng-click="currentStep='addressStep'" name="back" class='btn btnSmall btnGrey' value="Back">
                            </div>
                        </div>
                    </form>
          