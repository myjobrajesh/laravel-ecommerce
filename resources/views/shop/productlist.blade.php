                        @if (count($products) > 0)
                            @foreach($products as $post)
                                @include("shop/singlelist", array('product'=>$post))
                            @endforeach
                            @if ($products->nextPageUrl())
                                <div class="col-sm-12 col-md-12 col-xs-12 grid-item next text-center" page-next ><i class="fa fa-spinner"></i></div>
                            @endif
                        @endif
