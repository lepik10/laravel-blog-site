@extends('layout')

@section('content')
    <div class="row">
        <div class="col-sm-8">
            <h1>{{ $post->title }}</h1>
            <p>{{ $post->content }}</p>
            <div>
                <img style="max-width: 200px" src="{{ $post->image ? $post->image->url : '' }}" alt="">
            </div>

            <x-tags :tags="$post->tags"></x-tags>
            <h4>Comments</h4>
            <x-comment-form :route="route('posts.comments.store', ['post' => $post->id])"></x-comment-form>
            <x-comment-list :comments="$post->comments"></x-comment-list>
            <p>Currently read by {{ $counter }} people</p>
        </div>
        <div class="col-sm-4">
            @include('posts._activity')
        </div>
    </div>

@endsection
