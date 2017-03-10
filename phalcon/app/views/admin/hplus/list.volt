{% extends "admin/layouts/hplus.volt" %}
{% block content %}
    <link href="{{ static_url('/lib/hplus/css/plugins/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet">
    <body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>基本</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#">选项1</a>
                        </li>
                        <li><a href="#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="row row-lg">
                    <div class="col-sm-12">
                        <input type="hidden" id="postUrl" value="{{ url('/admin/hplus/pfnList') }}">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户名</th>
                                <th>角色ID</th>
                            </tr>
                            </thead>
                            <tbody id="list"></tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div id="page"></div>
                </div>
            </div>
        </div>
    </div>

    </body>
{% endblock %}
{% block js %}
    <script src="{{ static_url('/lib/hplus/js/jquery.pagination.js') }}"></script>
    <script>
        var pageSize = 10;
        var pageIndex = 0;

        $(function () {
            bindData();
        });

        //这个事件是在翻页时候用的
        function pageselectCallback(page_id) {
            pageIndex = page_id;
            bindData();
        }

        function bindData() {
            var url = $("#postUrl").val();
            var json = {
                "pageIndex": pageIndex,
                "pageSize": pageSize
            };

            $.post(url, json, function (jsonData) {
                console.log(jsonData.data.data);
                var data = "";
                if (jsonData.status == "1") {
                    $.each(jsonData.data.data, function (i, v) {
                        data += '<tr class="gradeX">';
                        data += '<td>' + v.id + '</td>';
                        data += '<td>' + v.name + '</td>';
                        data += '<td>' + v.role_id + '</td>';
                        data += '</tr>';
                    })
                    $("#list").html(data);
                    initpagination(jsonData.data.count);
                }
                else {
                    alert(jsonData.message);
                }
            }, 'json');
        }

        function initpagination(count) {
            //加入分页的绑定
            $("#page").pagination(count, {
                callback: pageselectCallback,
                prev_text: '上一页',
                next_text: '下一页',
                items_per_page: pageSize,
                num_display_entries: 6,
                current_page: pageIndex,
                num_edge_entries: 2
            });
        }
    </script>
{% endblock %}