<div class="row">
                                        <div class="col-sm-12 col-md-12" id="Similar-Products-Container">
                                            <h6 class="">SIMILAR PRODUCTS</h6><br>
                                            @foreach($similar_product->slice(0, 4) as $similar)
                                                <div class="col-xs-6 col-sm-4 col-md-3 text-center" id="Similar-Product-Sub-Container">
                                                    <a href="{{ route('shop.show.product', $similar->product_name) }}">
                                                        @if ($similar->photos->count() === 0)
                                                            <p id="Similar-Title">{{ str_limit($similar->product_name, $limit = 28, $end = '...') }}</p>
                                                            <img src="/imgs/no-image-found.jpg" alt="No Image Found Tag" id="Product-similar-Image">
                                                        @else
                                                            @if ($similar->featuredPhoto)
                                                                <p id="Similar-Title">{{ str_limit($similar->product_name, $limit = 28, $end = '...') }}</p>
                                                                <img src="{{ \ImageHelper::viewFile($similar->featuredPhoto->filepath, 'sm') }}" class="img-responsive" id="Product-similar-Image" />
                                                            @elseif(!$similar->featuredPhoto)
                                                                <p id="Similar-Title">{{ $similar->product_name }}</p>
                                                                <img src="{{ \ImageHelper::viewFile($similar->photos->first()->filepath, 'sm')}}" class="img-responsive" alt="Photo" id="Product-similar-Image" />
                                                            @else
                                                                N/A
                                                            @endif
                                                        @endif
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                </div>