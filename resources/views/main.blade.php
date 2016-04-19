@inject('statistics', 'App\Services\Statistics')
        <!doctype html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
</head>
<body>
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
    <li><a href="/phones/invalid">Invalid phones</a></li>
    <li><a href="/locations">Locations</a></li>
    <li><a href="/export">Export</a></li>
    <li><a href="/details">Details</a></li>
    <li><a href="/logs">Logs</a></li>
</ul>
@yield('content')
</body>
</html>