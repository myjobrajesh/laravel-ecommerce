                    <div class="col-sm-12 col-md-12 col-xs-12 reviewRow" >
                        <!-- Nav tabs -->
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li role="presentation" class="active"><a href="#product_ratings" aria-controls="home" role="tab" data-toggle="tab">Customer Ratings</a></li>
                                                <li role="presentation" ><a href="#product_reviews" aria-controls="home" role="tab" data-toggle="tab">Customer Reviews</a></li>
                                                <li role="presentation"><a href="#product_review_frm" aria-controls="profile" role="tab" data-toggle="tab">Write a Review</a></li>
                                            </ul>
                        
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="product_ratings">
                                                    <h3>Customer Ratings :</h3>
                                                    <div id="ratingDisplayBottom"></div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane row" id="product_reviews">
                                                    <h3>Customer Reviews :</h3>
                                                    <div  class="reviewsScrollbar col-sm-12 col-md-12" page-scroll="getReviewList()">
                                                        <div id="reviewDisplayBottom" class="row">
                                                        {!!$reviews!!}
                                                        </div>    
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="product_review_frm">
                                                    <br>
                                                    <form class="frmReview" id="frmReview">
                                                        <div class="alert alert-success" role="alert"  ng-show="reviewSucessMsg">
                                                            <button type="button" class="close" ng-click="reviewSucessMsg=false" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            Review posted sucessfully
                                                        </div>

                                                        <textarea class="form-control" name="review" required></textarea>
                                                        <br>
                                                        <input type="button" ng-show="!reviewLoading" ng-click="addReview()" class="btn btnSmall" value="Add Review">
                                                        <i class="fa fa-spinner" ng-show="reviewLoading"></i>
                                                    </form>
                                                    <br>
                                                </div>
                                            </div>
                                                
                    </div>
                                    
                                    
                                    