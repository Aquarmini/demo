{% extends "test/master.volt" %}
{% block content %}
    <div id="state"></div>
    <input type="hidden" id="url" value="{{ url('/test/view/pfnLongPolling') }}">
{% endblock %}
{% block js %}
    <script>
        $(function () {
            (function longPolling() {
                var url = $("#url").val();
//                alert(Date.parse(new Date()) / 1000);
                $.ajax({
                    url: url,
                    data: {"timed": Date.parse(new Date()) / 1000},
                    dataType: "text",
                    timeout: 20000,//5秒超时，可自定义设置
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $("#state").append("[state: " + textStatus + ", error: " + errorThrown + " ]<br/>");
                        if (textStatus == "timeout") { // 请求超时
                            longPolling(); // 递归调用
                        } else { // 其他错误，如网络错误等
                            longPolling();
                        }
                    },
                    success: function (data, textStatus) {
                        if (data.status == 0) {
                            $("#state").append("[state: " + textStatus + ", data: { " + data + "} ]<br/>");
                            longPolling();
                            return;
                        }
                        $("#state").append("[state: " + textStatus + ", data: { " + data + "} ]<br/>");

                        if (textStatus == "success") { // 请求成功
                            longPolling();
                        }
                    }
                });

            })();
        });
    </script>
{% endblock %}