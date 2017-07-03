{% extends "test/master.volt" %}
{% block content %}
{% endblock %}
{% block js %}
    <script>
        $(function () {
            var url = "/test/api/pfnValidation";
            var json = {
                id: 4,
                say: "hello world",
                body: {
                    test: 11,
                    test2: 22
                }
            };
            $.post(url, json, function (res) {
                console.log(res);
            });
        })
    </script>
{% endblock %}