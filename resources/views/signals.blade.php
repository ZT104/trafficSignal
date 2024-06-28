<!DOCTYPE html>
<html>
<head>
    <title>Signal Lights</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
       .signal {
            width: 100px;
            height: 100px;
            margin: 10px;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        .green { background-color: green; }
        .yellow { background-color: yellow; }
        .red { background-color: red; }
    </style>
</head>
<body>
    <div>
        <label>Sequence (comma-separated signals like A,B,C,D):</label>
        <input type="text" id="sequence" />
        <label>Green Interval (seconds):</label>
        <input type="number" id="green_interval" />
        <label>Yellow Interval (seconds):</label>
        <input type="number" id="yellow_interval" />
        <button onclick="start()">Start</button>
        <button onclick="stop()">Stop</button>
    </div>
    <div id="signals">
        <div id="signalA" class="signal red">A</div>
        <div id="signalB" class="signal red">B</div>
        <div id="signalC" class="signal red">C</div>
        <div id="signalD" class="signal red">D</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let interval;
        let sequence = [];
        let greenInterval = 0;
        let yellowInterval = 0;
        let currentIndex = 0;

        function start() {
            sequence = $('#sequence').val().split(',');
            greenInterval = parseInt($('#green_interval').val());
            yellowInterval = parseInt($('#yellow_interval').val());

            $.ajax({
                url: '/start',
                method: 'POST',
                data: {
                    sequence: sequence,
                    green_interval: greenInterval,
                    yellow_interval: yellowInterval,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'started') {
                        runSignals();
                    }
                }
            });
        }

        function stop() {
            clearInterval(interval);
            $('.signal').removeClass('green yellow').addClass('red');
            $.post('/stop', {_token: $('meta[name="csrf-token"]').attr('content')}, function(response) {
                if (response.status === 'stopped') {
                    console.log('Signals stopped');
                }
            });
        }

        function runSignals() {
            currentIndex = 0;
            clearInterval(interval);
            interval = setInterval(changeSignal, (greenInterval + yellowInterval) * 1000);
            changeSignal();
        }

        function changeSignal() {
            $('.signal').removeClass('green yellow').addClass('red');
            const currentSignal = `#signal${sequence[currentIndex]}`;
            $(currentSignal).removeClass('red').addClass('green');

            setTimeout(() => {
                $(currentSignal).removeClass('green').addClass('yellow');
            }, greenInterval * 1000);

            setTimeout(() => {
                $(currentSignal).removeClass('yellow').addClass('red');
                currentIndex = (currentIndex + 1) % sequence.length;
            }, (greenInterval + yellowInterval) * 1000);
        }
    </script>
</body>
</html>
