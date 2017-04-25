@extends('admin.dash')

@section('content')

    <div class="container" id="admin-product-container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admincare/shop/attributes') }}" class="btn btn-danger">Back</a>
        <br><br>

        <h4 class="text-center">Edit {{ $attribute->name }}</h4><br><br>

        <div class="col-md-12">

            <form role="form" method="POST" action="{{ route('admin.shop.attribute.update', $attribute->id) }}">
                {{ csrf_field() }}

                <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>Attribute Name</label>
                        <input type="text" class="form-control" name="name" value="{{ Request::old('name') ? : $attribute->name }}" placeholder="Edit New Attribute name">
                        @if($errors->has('name'))
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>

                <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                    <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
                        <label>Attribute Value</label>
                        <input type="text" class="form-control" name="value" value="{{ Request::old('value') ? : $attribute->value }}" placeholder="Edit New Attribute value">
                        @if($errors->has('value'))
                            <span class="help-block">{{ $errors->first('value') }}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label>Attribute Status</label>
                        <select name="status" class="form-control">
                            <option value="active" {{($attribute->status == 'active' ? 'selected' : '')}}>Active</option>
                            <option value="inactive" {{($attribute->status == 'inactive' ? 'selected' : '')}}>Inactive</option>
                        </select>
                </div>    
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Edit Attribute</button>
                </div>

            </form>

        </div> <!-- Close col-md-12 -->

    </div>  <!-- Close container -->
@endsection

@section('footer')

@endsection
