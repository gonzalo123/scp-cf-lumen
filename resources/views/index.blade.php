<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cloud foundry Silex demo</title>

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">

    <link href="css/app.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">
    <div class="header clearfix">
        <h3 class="text-muted">Cloud Foundry demo</h3>
    </div>

    <div class="jumbotron">
        <p class="lead">User: {{ $user['NAME'] }} {{ $user['SURNAME'] }}</p>
        <p class="lead">Cache TTL: {{ $ttl }}</p>
        <p class="lead">timestamp: <span id="timestamp"></span></p>
        <p><a id="refresh" class="btn btn-lg btn-success" href="#" role="button">Refresh</a></p>
    </div>

    <footer class="footer">
        <p>&copy; 2017 gonzalo123</p>
    </footer>

</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>

<script>
    $(function () {
        var refreshTimestamp = function () {
            $.getJSON("/timestamp", function (data) {
                $('#timestamp').html(data);
            });
        };
        $.ajaxSetup({cache: false});

        refreshTimestamp();
        $('#refresh').click(refreshTimestamp);
    });
</script>
</body>
</html>