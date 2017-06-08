{% extends "test/master.volt" %}
{% block content %}
{% endblock %}
{% block js %}
    <script>
        $(function () {
            var url = "http://demo.phalcon.lmx0536.cn/test/index/pfnAjax";
            var json = {
                say: "hello world"
            };
            $.post(url, json, function (res) {
                console.log(res);
            });
        })
    </script>
{% endblock %}