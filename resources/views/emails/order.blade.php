@extends('emails/email')
@section('content')
        <h4>Dear {{$user->profile->firstname.' '.$user->profile->lastname}},</h4>
        <p>Thank you for shopping with us.</p>
        <p>Your Order:</p>
        
        <div class="clearfix"></div>
        <div class="table-responsive">
        <table class="table table-striped table-hover cartTable">
            <tr>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th >Total Price</th>
            </tr>
            @foreach($cart_products as $cart_item)
            <tr>
                <td>
                    <span class="pull-left productCol">
                        <a href="{{ route('shop.show.product', $cart_item->products->product_name) }}">
                        @if ($cart_item->products->photos->count() === 0)
                                <img src="/imgs/no-image-found.jpg" alt="No Image Found Tag"  class="noImage" >
                        @else
                            @if ($cart_item->featuredPhoto)
                                <img src="{{ \ImageHelper::viewFile($cart_item->featuredPhoto->filepath, 'th') }}" class="img-responsive" style="max-width:50px"/>
                            @elseif(!$cart_item->featuredPhoto)
                                <img src="{{ \ImageHelper::viewFile($cart_item->products->photos->first()->filepath, 'th')}}" class="img-responsive" style="max-width:50px"/>
                            @else
                                N/A
                            @endif
                        @endif
                        </a>
                    </span>
                    <span class="pull-left">
                        <a href="{{ route('shop.show.product', $cart_item->products->product_name) }}">
                        {{ $cart_item->products->product_name }}
                        </a>
                        <p>
                            ISBN: {{ $cart_item->products->product_sku }}<br>
                            @if($cart_item->attributes->count())
                                @foreach($cart_item->attributes as $attr)
                                    {{$attr->attribute_name}} : <i>{{$attr->attribute_value}}</i><br>
                                @endforeach
                            @endif
                            @if($cart_item->measurements->count())
                                <b>Measurements:</b><br>
                                @foreach($cart_item->measurements as $mt)
                                    {{$mt->name}} : <i>{{$mt->value}}</i><br>
                                @endforeach
                            @endif
                        </p>
                    </span>
                </td>
                    
                <td>
                    {{\CommonHelper::numberFormat($cart_item->total/$cart_item->qty)}}
                </td>
                    
                <td>
                    {{$cart_item->qty}}
                </td>
                <td>{{\CommonHelper::numberFormat($cart_item->total)}}</td>
            </tr>        
            @endforeach
        </table>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12" id="Cart_Totals_Container">
            <div class="col-xs-4 col-sm-8 col-md-9">
                
            </div>
            <div class="col-xs-8 col-sm-4 col-md-3">
                <b>TOTAL: {{config('app.defaultCurrencySign')}}{{\CommonHelper::numberFormat($cart_total)}}</b>
            </div>
        </div>


@endsection