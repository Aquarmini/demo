{% extends "test/master.volt" %}
{% block content %}
{% endblock %}
{% block js %}
    <script src="{{ static_url('/lib/socket.io-client/socket.io.min.js') }}"></script>
    <script>
        var socket = io('http://localhost');
        socket.on('connect', function () {
        });
        socket.on('event', function (data) {
        });
        socket.on('disconnect', function () {
        });
    </script>
{% endblock %}