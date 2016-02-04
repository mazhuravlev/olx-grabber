<p>
    <a href="{{ $offer->href }}">
        {{ $offer->title }}
    </a>
</p>
<p>
    {{ $offer->description }}
</p>
<p>
    @foreach($offer->phone as $phone)
        {{ $phone }}
    @endforeach
</p>

<p>
    {{ var_dump($offer->toArray()) }}
</p>