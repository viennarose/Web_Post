@php
    use App\Models\Likes;
@endphp

@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h4>Posts</h4>
    </div>
    <div class="col-lg-6 offset-lg-3">
        @foreach ($posts as $item)
        <div class="card card-outline card-primary">
            <div class="card-header">
                <span class="card-title">{{ $item->title }}</span>
            </div>
            <div class="card-body">
                <p>{{ $item->description }}</p>
            </div>
            <div class="card-footer">
                @php
                    $likes = Likes::where('post_id', $item->id)
                                ->where('user_id', Auth::id())
                                ->get();
                @endphp
                @if (count($likes) < 1)
                    <button onclick="like({{ $item->id }})" class="btn text-info"><i id="heart-{{ $item->id }}" class="far fa-grin-hearts"></i> {{ $item->Likes }}</span></button>
                @else
                    <button onclick="like({{ $item->id }})" class="btn text-info"><i id="heart-{{ $item->id }}" class="fas fa-grin-hearts"></i> <span id="like-{{ $item->id }}" >{{ $item->Likes}}</span></button>
                @endif
                    <button id="share" class="btn text-danger"><i class="fas fa-comment"></i></button>
                    <button id="share" class="btn text-danger"><i class="fas fa-share-alt"></i></button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
        });

        function like(id) {
            let count = 0;
            $.ajax({
                url : '/likes/un-like',
                type : 'GET',
                data : {
                    post_id : id,
                    user_id : "{{ Auth::id() }}",
                },
                success : function(result) {
                    var data = JSON.parse(result);

                    console.log(data);

                    if(data['result'] == 'liked'){
                        $.ajax({
                            url : '/likes/like',
                            type : 'POST',
                            data : {
                                _token : "{{ csrf_token() }}",
                                post_id : id,
                            },
                            success : function(response) {
                                $('#heart-' + id).removeClass('far fa-grin-hearts').addClass('fas fa-grin-hearts');

                                console.log('first')
                                var increment = parseInt($('#like-' + id).text()) + 1;
                                $('#like-' + id).text(increment);
                                console.log(count = 1);

                                if(count == 1){
                                    window.location.reload();
                                }
                            },
                            error : function(error) {
                                alert('Error liking');
                            }
                        });
                    } else{
                        if(data['result'] == 'unliked'){
                            $('#heart-' + id).removeClass('fas fa-grin-hearts').addClass('far fa-grin-hearts');
                            var decrement = parseInt($('#like-' + id).text()) - 1;
                            $('#like-' + id).text(decrement);
                            console.log('second');
                            count = 0;
                            console.log("count: "+count);
                        }
                    }
                },
                error : function(error) {
                    alert('Error liking');
                }
             });

        }
        </script>
@endpush
