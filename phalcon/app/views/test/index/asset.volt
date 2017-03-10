{% extends "test/master.volt" %}
{% block content %}
    控制器中加载js资源
{% endblock %}
{% block js %}
    {{ assets.outputJs() }}
{% endblock %}
