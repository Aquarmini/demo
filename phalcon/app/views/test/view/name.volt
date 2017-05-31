{% extends "test/master.volt" %}
{% block content %}
    {#<input type="text" value="{{ route('route.index.target') }}">#}
    <input type="text" value="{{ url('/route/index/target') }}">
    <input type="text" value="{{ url('route.index.target') }}">
    <input type="text" value="{{ url(['for':'route.index.target']) }}">
{% endblock %}