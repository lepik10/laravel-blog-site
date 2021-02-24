<div class="card">
    <h5>{{ $title }}</h5>
    <h6>{{ $subtitle }}</h6>
    <ul>
        @foreach($items as $item)
            <li>{{ $item }}</li>
        @endforeach

    </ul>
</div>
