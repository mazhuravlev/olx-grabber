<ul>
    @foreach($offers as $offer)
        <li data-id="{{ $offer->id }}" title="{{ $offer->description }}">
            <a href="/offer/{{ $offer->id }}">
                {{ $offer->title }}
            </a>
            {{ $offer->price_string }}
            @foreach($offer->phone as $phone)
                {{ $phone }}
            @endforeach
        </li>
    @endforeach
</ul>

{{ $offers->render() }}