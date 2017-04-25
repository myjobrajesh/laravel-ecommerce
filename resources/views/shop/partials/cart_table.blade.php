
@if ($cart_total === 0)
    <a href="{{ route("shop") }}" class="col-sm-12 text-center"> No products in your cart</a><br>
@else
    <div class="clearfix"></div>
    <div class="table-responsive">
    <table class="table table-striped table-hover cartTable">
        <tr>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th >Total Price</th>
            @if($pageType =="cart")    
            <th></th>
            @endif
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
                @if($pageType =="cart")
                <form action="/shop/cart/update" method="post" class="form-inline">
                    {!! csrf_field() !!}
                    <input type="hidden" name="product" value="{{$cart_item->products->id}}" />
                    <input type="hidden" name="cart_id" value="{{$cart_item->id}}" />
                    <div class="form-group">
                        <select name="qty" class="form-control" title="Cart Quantity">
                            @for ($i = 1; $i <= config('app.shopQtySelection'); $i++)
                                <option value="{{$i}}" {{($cart_item->qty == $i) ? 'selected="selected"' : ''}}>{{$i}}</option>
                            @endfor
                        </select>
                        <button class="btn btn-sm btn-default"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                    </div>
                </form>
                @else
                {{$cart_item->qty}}
                    @endif
            </td>
            <td>{{\CommonHelper::numberFormat($cart_item->total)}}</td>
            @if($pageType =="cart")
            <td>
                <a href="{{URL::route('shop.delete_book_from_cart', array($cart_item->id))}}">
                    <i class="fa fa-times" aria-hidden="true" style="color: darkred;"></i>
                </a>
            </td>
            @endif
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
@endif