        <div class="col-md-4 col-sm-4 col-xs-12 postListItem grid-item">
                            <div class=" roundedBox text-center" style="height:auto">
                                <a href="{{ route('shop.show.product', $product->product_name) }}" style="text-decoration:none">
                                @if ($product->photos->count() === 0)
                                    <span class="productImage"><img src="/imgs/no-image-found.jpg" alt="No Image Found Tag" id="Product-similar-Image" class="img-responsive"></span>
                                @else
                                    @if ($product->featuredPhoto)
                                        <span class="productImage"><img src="{{ \ImageHelper::viewFile($product->featuredPhoto->filepath, 'sm') }}" class="img-responsive" /></span>
                                    @elseif(!$product->featuredPhoto)
                                        <span class="productImage"><img src="{{ \ImageHelper::viewFile($product->photos->first()->filepath, 'sm')}}" class="img-responsive" /></span>
                                    @else
                                        N/A
                                    @endif
                                @endif

                                    <h3 class="productName" >{{ $product->product_name }}</h3>
                                    <?php
                                    $totalRating = 0;
                                    $rateBg = 0;
                                    if($product->ratingArr) {
                                        $totalRating = $product->ratingArr->total;
                                        $rateBg = (($product->ratingArr->average)/5)*100;
                                    }
                                    ?>
                                    <div class="starRating" >
                                        <?php ?>
                                        <div class="Fr-star size-4">
                                            <div class="Fr-star-value" style="width: {{$rateBg}}%"></div>
                                            <div class="Fr-star-bg" ></div>
                                        </div>
                                        <div class="textRating">({{$totalRating}} Ratings)
                                        </div>
                                    </div>
                                    
                                    <span class="productPrice">
                                    @if($product->reduced_price == 0)
                                        {{config('app.defaultCurrencySign')}} {{  \CommonHelper::numberFormat($product->price) }}
                                    @else
                                        {{config('app.defaultCurrencySign')}} {{ \CommonHelper::numberFormat($product->reduced_price) }}
                                    @endif
                                    </span>
                                </a>
                            </div>
                            </div>
                        