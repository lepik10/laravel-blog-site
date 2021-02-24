@extends('layout')

@section('content')
    <div class="row">
        <div class="col-8">
            <h1>Posts</h1>
            @forelse($posts as $post)

                <h3>
                    @if($post->trashed())
                        <del>
                    @endif
                    <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a></h3>
                @if($post->trashed())
                    </del>
                        @endif
                <x-updated :date="$post->created_at" :name="$post->user->name" :userId="$post->user->id"></x-updated>
                <x-tags :tags="$post->tags"></x-tags>
                @if($post->comments_count)
                    <p>{{ $post->comments_count }} comments</p>
                @else
                    <p>No comments yet!</p>
                @endif

                @can('update', $post)
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}">Edit</a>
                @endcan
                @if(!$post->trashed())
                @can('delete', $post)
                    <form method="POST" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                @endcan
                @endif

            @empty
                <p>Nothing!!</p>
            @endforelse
        </div>
        <div class="col-4">
            @include('posts._activity')
        </div>
    </div>
@endsection
