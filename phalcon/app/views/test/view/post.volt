{% extends "test/master.volt" %}
{% block content %}
    <input type="hidden" id="url" value="{{ url('/test/view/pfnPostData') }}">
{% endblock %}
{% block js %}
    <script>
        $(function () {
            var json = {
                data: {
                    id: 1,
                    name: "啦啦啦"
                },
                ticket: 100
            };
            var url = $("#url").val();
            $.post(url, json, function (jsonData) {
                console.log(jsonData);
            });
        });
    </script>
{% endblock %}