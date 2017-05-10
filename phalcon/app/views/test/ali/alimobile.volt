{% extends "test/weui.volt" %}
{% block content %}
    <div class="weui-cells__title">日志信息</div>
    <div class="weui-cells" id="logs">
    </div>
{% endblock %}
{% block js %}
    <script>
        function push(msg) {
            var item = '        <div class="weui-cell">\
                <div class="weui-cell__bd">\
                <p>' + msg + '</p>\
                </div>\
                </div>';
            $("#logs").append(item);
        }

        document.addEventListener('AlipayJSBridgeReady', function () {
            push("AlipayJSBridgeReady");
        }, false);

        // 初始化设备库
        AlipayJSBridge.call('openAPDeviceLib', {'connType': 'blue'}, function (res) {
            push("result.status:" + res.result.status);
            push("isSupportBLE:" + res.isSupportBLE);
            push("bluetoothState:" + res.bluetoothState);

            if (res.result.status != "ok" || res.bluetoothState != 'on') {
                weui.alert("请打开蓝牙设备！");
                return;
            }

            // 配置设备
            AlipayJSBridge.call('configAPDeviceLib', {
                'connType': 'blue',
                'dataType': 'hex',
                'filtType': 'deviceUuid',
                'serviceUUID': 'f000ffc0-dd84-ed9c-7bfe-8121e9b75f97',
                'writeUUID': 'f000ffc7-dd84-ed9c-7bfe-8121e9b75f97',
                'readUUID': 'f000ffc1-dd84-ed9c-7bfe-8121e9b75f97'
//                'descriptorUUID': '00002902-0000-1000-8000-00805f9b34fb'
            }, function (res) {
                push("configAPDeviceLib:" + JSON.stringify(res));
            });
        });

    </script>
{% endblock %}