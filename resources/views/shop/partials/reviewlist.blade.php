@if($reviews->total())
    <ul class="list-unstyled reviewsListing">
    @foreach($reviews as $review)
        <li class="grid-item">{{$review->review}}<br>
            <div class="text-right reviewDispDate" >On {{\CommonHelper::dateDisplay(strtotime($review->created_at))}}</div>
        </li>
    @endforeach
    
    </ul>
    @if ($reviews->nextPageUrl())
        <p class="grid-item next text-center" page-next ><i class="fa fa-spinner"></i></p>
    @endif
    {{--<p>{{$reviews->links()}}</p>--}}
@else
    <p>No review yet.</p>
@endif