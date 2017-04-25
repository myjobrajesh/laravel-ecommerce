
                    <form id="frmAddress" name="frmAddress" role="form" autocomplete="off" novalidate ng-submit="submitAddress(frmAddress.$valid)" >
                        {!! csrf_field() !!}

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" placeholder="first name" class="form-control" name="first_name" required ng-model="shipping.first_name" >
                                <span ng-show="frmAddress.first_name.$invalid && frmAddress.$submitted" class="help-block validationError">required.</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" placeholder="last name" class="form-control" name="last_name" required ng-model="shipping.last_name">
                                <span ng-show="frmAddress.last_name.$invalid && frmAddress.$submitted" class="help-block validationError">required.</span>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" placeholder="address" class="form-control" name="address" required ng-model="shipping.address">
                                <span ng-show="frmAddress.address.$invalid && frmAddress.$submitted" class="help-block validationError">required.</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address Line 2 (Optional)</label>
                                <input type="text" placeholder="address 2" class="form-control" name="address2" ng-model="shipping.address2">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Country:</label>
                                <select id="country" name="country" class="form-control" required ng-model="shipping.country">
                                    @foreach(App\Utilities\Country::all() as $k=>$val)
                                        <option value="{{ $k }}" >{{ $k }}</option>
                                    @endforeach
                                </select>
                                <span ng-show="frmAddress.country.$invalid && frmAddress.$submitted" class="help-block validationError">required.</span>
                            </div>
                        </div>
                            
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state">State:</label>
                                <input type="text" placeholder="State" class="form-control" name="state" required ng-model="shipping.state">
                                <span ng-show="frmAddress.state.$invalid && frmAddress.$submitted" class="help-block validationError">required.</span>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" placeholder="city" class="form-control" name="city" required ng-model="shipping.city">
                                <span ng-show="frmAddress.city.$invalid && frmAddress.$submitted" class="help-block validationError">required.</span>
                            </div>
                        </div>

                        <div class="col-md-6" >
                            <div class="form-group">
                                <label>Zip Code</label>
                                <input type="text" placeholder="zip" class="form-control"    name="zip" required ng-model="shipping.zip">
                                <span ng-show="frmAddress.zip.$invalid && frmAddress.$submitted" class="help-block validationError">required.</span>
                             </div>
                        </div>
                    
                        <div class="col-md-12" >
                            <div class="form-group">
                                <input type="checkbox" title="billingsame" id="chk_billing_same" name="chk_billing_same" value="1" >
                                Billing address is same as shipping address
                            </div>
                        </div>
                        @include("shop.partials.checkout.billing_address")  

                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" ng-show="!addressLoading" name="submit" class='btn btnSmall' value="Submit">
                                <i class="fa fa-spinner" ng-show="addressLoading"></i>
                            </div>
                        </div>

                    </form> <!-- close form -->
