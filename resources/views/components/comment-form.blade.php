<div class="mb-2 mt-2">
    @auth
        <form method="POST" action="{{ $route }}">
            @csrf

            <textarea name="content"></textarea><br>

            <button type="submit">Add comment</button>
        </form>
        <x-errors></x-errors>
    @else
        <a href="{{ route('login') }}">Sign-in to post comments!</a>
    @endauth
</div>
<hr>
