

                    <form id="frmConfirmOrder" name="frmConfirmOrder" role="form" autocomplete="off"  action ="{{route("shop.order")}}" method="post">
                        {!! csrf_field() !!}

                        <div class="col-md-12">
                            <div class="form-group">
                                <ul class="list-unstyled">
                                    <li>
                                        @include('shop.partials.cart_table', ['pageType' => 'confirmOrder'])
                            
                                    </li>
                                    @if($checkoutSteps['shippingMethods'])
                                    <li>
                                        <div class="text-right" style="padding-right:35px">
                                            Shipping Method : <span class="selectedShippingMethod">{{config('app.defaultCurrencySign')}} 0.00</span>
                                        </div>
                                    </li>
                                    @endif
                                    <li>
                                        <div class="text-right selectedPaymentMethod" style="padding-right:35px">
                                            Payment Method : <span class="method_label"></span> : {{config('app.defaultCurrencySign')}}<span class="price">0.00</span></span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="text-right grandTotalRow" style="padding-right:35px">
                                          Grand Total : {{config('app.defaultCurrencySign')}}<span class="price" style="font-weight:bold" > 0.00</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" ng-show="!coLoading" name="submit" class='btn btnSmall' value="Place Order">
                                <i class="fa fa-spinner" ng-show="coLoading"></i>
                                <input type="button" ng-click="currentStep='paymentStep'" name="back" class='btn btnSmall btnGrey' value="Back">
                            </div>
                        </div>
                    </form>