<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>WeUI</title>
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css"/>

</head>
<body>
{% block content %}{% endblock %}
<script src="{{ static_url('lib/jquery-1.12.4/jquery.min.js') }}"></script>
<script type="text/javascript" src="//res.wx.qq.com/open/libs/weuijs/1.1.1/weui.min.js"></script>
{% block js %}{% endblock %}
</body>
</html>