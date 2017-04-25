@extends('admin.dash')

@section('content')

    <div class="container" id="admin-product-container">

            <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admincare/shop/attributes') }}" class="btn btn-danger">Back</a>
            <br><br>

        <h4 class="text-center">Add new Attribute</h4><br><br>

        <div class="col-md-12">

            <form role="form" method="POST" action="{{ route('admin.shop.attribute.post') }}">
                {{ csrf_field() }}

                <div class="col-sm-6 col-md-6" id="Product-Input-Field">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label>Attribute Name</label>
                          <br>Select Attribute name
                          <select name="name_select">
                            @foreach($attributes as $attr)
                                <option value="{{$attr->name}}">{{$attr->name}}</option>
                            @endforeach
                          </select>
                          <br>
                          OR Add New
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Add New Attribute">
                        @if($errors->has('name'))
                            <span class="help-block">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-3 col-md-3" id="Product-Input-Field">
                    <div class="form-group">
                        <label>Attribute Value</label><br>
                        <input type="text" class="form-control" name="value" value="{{ old('value') }}" placeholder="Add New Value">
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Create Attribute</button>
                </div>

            </form>

        </div> <!-- Close col-md-12 -->

    </div>  <!-- Close container -->
@endsection

@section('footer')



@endsection
