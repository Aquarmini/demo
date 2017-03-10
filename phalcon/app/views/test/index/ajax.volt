{% extends "test/master.volt" %}
{% block content %}
    <p>查看F12</p>
{% endblock %}
{% block js %}
    <script>
        var json = {
            key: 123456
        };
        $.post("/test/index/pfnAjax/helper", json, function (jsonData) {
            console.log(jsonData);
        }, "json");
        var json = {
            key: 123456
        };
        $.post("/test/index/pfnAjax/trait", json, function (jsonData) {
            console.log(jsonData);
        }, "json");
    </script>
{% endblock %}