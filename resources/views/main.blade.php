@inject('statistics', 'App\Services\Statistics')
<p>Объявлений в базе: {{ $statistics->offerCount() }}</p>
<input name="olx_id" placeholder="поиск по OLX ID" pattern="^[\w\d]{5,}$" title="например: imaHC">
<button onclick="olxSearch(document.getElementsByName('olx_id')[0].value)">поиск</button>
<script>
    function olxSearch(id) {
        window.location = '/offer/olx_id/' + id;
    }
</script>
<ul>
    <li><a href="/offers">Offers</a></li>
    <li><a href="/phones">Phones</a></li>
    <li><a href="/phones">Locations</a></li>
</ul>
@yield('content')
