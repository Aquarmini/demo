{% extends "test/master.volt" %}
{% block content %}
    <form>
        <div class="form-group">
            <label for="exampleInputPassword1">Msg</label>
            <input type="text" class="form-control" id="msg" placeholder="Msg">
        </div>
        <a onclick="send()" class="btn btn-default">Submit</a>
    </form>
    <script>
        var wsUri = "ws://127.0.0.1:9501";
        websocket = new WebSocket(wsUri);
        websocket.onopen = function (evt) {
            console.log("OPEN");
        };
        websocket.onclose = function (evt) {
            console.log("CLOSE");
        };
        websocket.onmessage = function (evt) {
            console.log(evt.data);
        };
        websocket.onerror = function (evt) {
            console.log("ERROR");
        };
        function send() {
            var msg = $("#msg").val();
            websocket.send(msg);
        }
    </script>
{% endblock %}