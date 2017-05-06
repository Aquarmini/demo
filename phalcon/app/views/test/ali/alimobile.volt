{% extends "test/master.volt" %}
{% block content %}
    <div id="state"></div>
{% endblock %}
{% block js %}
    <script>
        document.write(1);
        document.addEventListener('AlipayJSBridgeReady', function () {
            document.write(typeof AlipayJSBridge);
        }, false);
    </script>
{% endblock %}