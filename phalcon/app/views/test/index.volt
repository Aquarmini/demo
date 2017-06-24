{% extends "test/master.volt" %}
{% block content %}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h3>小工具</h3>
                <a type="button" class="btn btn-default" href="{{ url('test/tools/code2') }}">二维码生成器</a>
                <a type="button" class="btn btn-default" href="{{ url('test/tools/img') }}">图片裁剪</a>
                <a type="button" class="btn btn-default" href="{{ url('test/tools/time') }}">时间戳转化</a>
            </div>
            <div class="col-md-12">
                <h3>基本测试</h3>
                <a type="button" class="btn btn-default" href="{{ url('test/index/log') }}">写入日志</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/session') }}">SESSION测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/ext') }}">扩展是否存在</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/uniqid') }}">uniqid</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/is_numeric') }}">is_numeric</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/pingbi') }}">屏蔽字列表</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/str') }}">Str方法</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/vue') }}">Vue.js</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/word') }}">字符++</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/zhuru') }}">sql注入测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/ip') }}">ip所在地</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/date') }}">date测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/file/index') }}">文件上传测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/fetch') }}">PDO::Fetch</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/prepare') }}">数据库prepare</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/qx') }}">测试权限</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/path') }}">相对路径->绝对路径</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/logical') }}">逻辑运算</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/print') }}">格式化输出</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/ar') }}">不定输入参数</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/list') }}">list返回测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/strpos') }}">strpos</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/null') }}">null比较</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/js') }}">APP js交互</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/cookie') }}">COOKIE测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/strpad') }}">strpad</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/arrayColumn') }}">array_column</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/asset') }}">资源管理</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/addQueue') }}">加入消息队列</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/ajax') }}">Ajax返回测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/cache') }}">缓存测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/socket') }}">SOCKET.IO</a>
                <a type="button" class="btn btn-default" href="{{ url('test/traits/log') }}">Trait Log</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/pclose') }}">无阻塞调用脚本</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/upstreamTest') }}">负载均衡测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/locationIp') }}">所在的IP</a>
                <a type="button" class="btn btn-default" href="{{ url('test/view/post') }}">Post测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/file/ajax') }}">跨域测试</a>
                <a type="button" class="btn btn-default"
                   href="{{ url('test/searchengine/index') }}">双峰控制器测试路由searchengine</a>
                <a type="button" class="btn btn-default"
                   href="{{ url('test/search_engine/index') }}">双峰控制器测试路由search_engine</a>
            </div>
            <div class="col-md-12">
                <h3>
                    模型
                </h3>
                <a type="button" class="btn btn-default" href="{{ url('test/model/init') }}">数据初始化</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/index') }}">基本用法</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/add') }}">新增</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/edit') }}">编辑</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/hasMany') }}">HasMany</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/belongsTo') }}">BelongsTo</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/hasManyToMany') }}">HasManyToMany</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/sql') }}">DB类</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/page?page=1') }}">分页</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/page2?page=1') }}">SQL 分页</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/page3?page=1') }}">QueryBuilder
                    分页</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/trans') }}">事务测试</a>
                <a type="button" class="btn btn-default" href="{{ url('test/model/cache') }}">CacheModel</a>
                <a type="button" class="btn btn-default" href="{{ url('test/view/longPolling') }}">长轮询</a>

            </div>
            <div class="col-md-12">
                <h3>
                    框架结构测试
                </h3>
                <a type="button" class="btn btn-default" href="{{ url('test/traits/index') }}">Trait</a>
                <a type="button" class="btn btn-default" href="{{ url('test/traits/dispatch') }}">Dispatch</a>
                <a type="button" class="btn btn-default" href="{{ url('test/swoole/websocket') }}">Swoole.Websocket</a>
                <a type="button" class="btn btn-default" href="{{ url('/myrouter/4/index/router/11/2') }}">自定义路由</a>
                <a type="button" class="btn btn-default" href="{{ url('test/index/firephp') }}">firephp</a>
            </div>
            <div class="col-md-12">
                <h3>
                    第三方服务
                </h3>
                <a type="button" class="btn btn-default" href="{{ url('test/api/yunpian') }}">云片短信</a>
                <a type="button" class="btn btn-default" href="{{ url('test/qiniu/index') }}">七牛流新建</a>
                <a type="button" class="btn btn-default" href="{{ url('test/qiniu/get') }}">七牛流获取</a>
                <a type="button" class="btn btn-default" href="{{ url('test/qiniu/room') }}">七牛连麦</a>
                <a type="button" class="btn btn-default" href="{{ url('test/api/huanxin') }}">环信</a>
            </div>
            <div class="col-md-12">
                <h3>
                    微信相关
                </h3>
                <a type="button" class="btn btn-default" href="{{ url('test/wx/info') }}">获取微信信息</a>
                <a type="button" class="btn btn-default" href="{{ url('test/wx/index') }}">WAP跳转到微信</a>
                <a type="button" class="btn btn-default" href="{{ url('test/wx/pay') }}">微信JsAPiPay</a>
                <a type="button" class="btn btn-default" href="{{ url('test/wx/wechat') }}">overtrue/wechat扩展测试</a>
            </div>
            <div class="col-md-12">
                <h3>
                    阿里相关
                </h3>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/index') }}">WAP支付</a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/info') }}">用户信息</a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/checkSign') }}">验签接口</a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/sms') }}">短信接口</a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/login') }}">支付宝登录</a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/alimobile') }}">支付宝jsApi</a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/openSearchUpdate') }}">
                    OpenSearch更新文档
                </a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/openSearchName') }}">
                    OpenSearch 搜索索引为name的文档
                </a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/openSearchNear') }}">
                    OpenSearch 搜索附近的文档
                </a>
                <a type="button" class="btn btn-default" href="{{ url('test/ali/openSearchV2Near') }}">
                    OpenSearch v2版本 搜索附近的文档
                </a>
            </div>
            <div class="col-md-12">
                <h3>
                    PayPal 相关
                </h3>
                <a type="button" class="btn btn-default" href="{{ url('test/paypal/index') }}">信用卡</a>
                <a type="button" class="btn btn-default" href="{{ url('test/paypal/createPayment') }}">WEB支付</a>
            </div>
            <div class="col-md-12">
                <h3>前端技术</h3>
                <a type="button" class="btn btn-default" href="{{ url('test/h5/particles') }}">particles.js</a>
            </div>
            <div class="col-md-12">
                <h3>搜索引擎</h3>
                <a type="button" class="btn btn-default" href="{{ url('test/search_engine/xsAddDoc') }}">讯搜 - 添加文档</a>
                <a type="button" class="btn btn-default" href="{{ url('test/search_engine/xsSearchDoc') }}">
                    讯搜 - 搜索文档
                </a>
                <a type="button" class="btn btn-default" href="{{ url('test/search_engine/esAddDoc') }}">
                    Elasticsearch - 添加文档
                </a>
            </div>
        </div>
    </div>
{% endblock %}