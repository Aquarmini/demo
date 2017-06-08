{% extends "test/master.volt" %}
{% block content %}
{% endblock %}
{% block js %}
    <script>
        $(function () {
            var url = "http://phalcon.app/test/index/getParams";
            var json = {
                say: "hello world"
            };
            $.post(url, json, function (res) {
                console.log(res);
            });
        })
    </script>
{% endblock %}