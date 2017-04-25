    <div id="billingAdressRow">

        <div class="col-md-6">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" title="first_name" class="form-control" name="billing_first_name"  ng-model="billing.first_name" >
                <span class="requiredStar">*</span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" title="last_name" class="form-control" name="billing_last_name"  ng-model="billing.last_name">
                <span class="requiredStar">*</span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Address</label>
                <input type="text" title="address" class="form-control" name="billing_address"  ng-model="billing.address">
                <span class="requiredStar">*</span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Address Line 2 (Optional)</label>
                <input type="text" title="address2" class="form-control" name="billing_address2"  ng-model="billing.address2">
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="country">Country:</label>
                <select id="country" name="billing_country" class="form-control"  ng-model="billing.country">
                    @foreach(App\Utilities\Country::all() as $k=>$val)
                        <option value="{{ $k }}" >{{ $k }}</option>
                    @endforeach
                </select>
                <span class="requiredStar">*</span>
            </div>
        </div>
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" placeholder="state" class="form-control" name="billing_state"  ng-model="billing.state">
                <span class="requiredStar">*</span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>City</label>
                <input type="text" title="city" class="form-control" name="billing_city"  ng-model="billing.city">
                <span class="requiredStar">*</span>
            </div>
        </div>

        <div class="col-md-6" >
            <div class="form-group">
                <label>Zip Code</label>
               <input type="text" title="zip" class="form-control" name="billing_zip"  ng-model="billing.zip">
               <span class="requiredStar">*</span>       
            </div>
        </div>
    </div>