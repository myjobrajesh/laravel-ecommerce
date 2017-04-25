@extends('admin.dash')

@section('content')

    <div class="container-fluid" id="admin-product-container">

        <br><br>
        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-bars fa-5x"></i></a>
        <a href="{{ url('admincare/shop/attribute/add') }}" class="btn btn-primary">Add new Attribute</a>
        <br><br>

        <h6>There are {{ count($attribute) }} attributes</h6><br>


        <table class="table table-bordered table-condensed table-hover">
            <thead>
            <tr>
                <th class="text-center blue white-text">Delete</th>
                <th class="text-center blue white-text">Edit</th>
                <th class="text-center blue white-text">Name</th>
                <th class="text-center blue white-text">Value</th>
            </tr>
            </thead>
            <tbody>
            @foreach($attribute as $attr)
            <tr>
                <td class="text-center">
                    <form method="post" action="{{ route('admin.shop.attribute.delete', $attr->id) }}" class="delete_form_product">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button id="delete-product-btn">
                            <i class="material-icons red-text">delete_forever</i>
                        </button>
                    </form>
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.shop.attribute.edit', $attr->id) }}">
                        <i class="material-icons green-text">mode_edit</i>
                    </a>
                </td>
                <td class="text-center">{{ $attr->name }}</td>
                <td class="text-center">{{ $attr->value }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    {!! $attribute->links() !!}
    </div>

@endsection