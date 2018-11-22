$(function () {
    $("#table").bootstrapTable({ // 对应table标签的id
        url: "/index/role/index", // 获取表格数据的url
        method: 'post',
        cache: false, // 设置为 false 禁用 AJAX 数据缓存， 默认为true
        striped: true,  //表格显示条纹，默认为false
        pagination: true, // 在表格底部显示分页组件，默认false
        pageList: [10, 20], // 设置页面可以显示的数据条数
        pageSize: 10, // 页面数据条数
        pageNumber: 1, // 首页页码
        sidePagination: 'server', // 设置为服务器端分页
        queryParams: function (params) { // 请求服务器数据时发送的参数，可以在这里添加额外的查询参数，返回false则终止请求
            return {
                pagesize: params.limit, // 每页要显示的数据条数
                offset: params.offset, // 每页显示数据的开始行号
                name:$('#search_name').val()
            }
        },
        sortName: 'id', // 要排序的字段
        sortOrder: 'desc', // 排序规则
        columns: [
            {
                checkbox: true, // 显示一个勾选框
                align: 'center' // 居中显示
            }, {
                field: 'id', // 返回json数据中的name
                title: '编号', // 表格表头显示文字
                align: 'center', // 左右居中
                valign: 'middle' // 上下居中
            }, {
                field: 'title',
                title: '名称',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'status',
                title: '状态',
                align: 'center',
                valign: 'middle'
            }, {
                title: "操作",
                align: 'center',
                valign: 'middle',
                width: 160, // 定义列的宽度，单位为像素px
                formatter: actionFormatter
            }
        ],
        onLoadSuccess: function () {  //加载成功时执行

            $('a.remove').click(function(){
                var id = $(this).attr('data-id');
                var data = {id:id};
                remove(data,'/index/role/delete');
            });

            $('a.edit').click(function(){
                var id = $(this).attr('data-id');
                edit(id,['title','remark','id'],'/index/role/edit');
            });

            $('a.flag').click(function(){
                var id = $(this).attr('data-id');
                flag(id,'/index/role/flag');
            });

            $('a.access').click(function(){
                var id = $(this).attr('data-id');
                access(id,'/index/role/access');
            })
        },
        onLoadError: function () {  //加载失败时执行
            console.info("加载数据失败");
        }
    });

    /**
     * 操作栏的格式化
     * @param value
     * @param row
     * @param index
     * @returns {string}
     */
    function actionFormatter(value, row, index) {
        var result = "";
        result += "<a data-id='" + row.id + "' class='btn btn-xs green flag' title='改变状态'><span class='glyphicon glyphicon-cog'></span></a>";
        result += "<a data-id='" + row.id + "' class='btn btn-xs blue edit' title='编辑'><span class='glyphicon glyphicon-pencil'></span></a>";
        result += "<a data-id='" + row.id + "' class='btn btn-xs red remove' title='删除'><span class='glyphicon glyphicon-remove'></span></a>";
        result += "<a data-id='" + row.id + "' class='btn btn-xs btn-warning access' title='分配权限'><span class='glyphicon glyphicon-user'></span></a>";
        return result;
    }

    var form = $('#znzg-form');
    form.bootstrapValidator({
        message: '输入值不合法',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            title: {
                message: '角色名称不合法',
                validators: {
                    notEmpty: {
                        message: '角色名称不能为空'
                    }
                }
            }
            , remark: {
                validators: {
                    notEmpty: {
                        message: '角色介绍不能为空'
                    }
                }
            }
        }
    });

    /**
     * 提交表单
     */
    $("#submitBtn").click(function () {
        //进行表单验证
        var bv = form.data('bootstrapValidator');
        bv.validate();
        if (bv.isValid()) {
            var url = $('#id').val()?'/index/role/edit':'/index/role/add';
            $.ajax({
                url: url,
                async: false,//同步，会阻塞操作
                type: 'POST',//PUT DELETE POST
                data: form.serialize(),
                success: function (res) {
                    layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function () {
                        window.location.reload();
                    });
                }, error: function () {

                    layer.msg('出错了');
                }
            })
        }
    });

    /**
     * 批量删除数据
     */
    $('.btn-delete').click(function(){
        var ids = getSelectIds();
        var data = {id:ids};
        var url = '/index/role/delete';

        if(0 === ids)
        {
            layer.msg('并未选中任何记录');
            return;
        }
        remove(data,url);
    });

    /**
     * 点击搜索时触发条件
     */
    $("#search_btn").click(function() {
        $('#table').bootstrapTable('refresh',{query:{pagesize:10,offset:0}});
    });

    /**
     * 获取权限列表
     * @param id
     * @param url
     */
    function access(id,url)
    {
        $("#role_id").val(id);
        // 获取权限信息
        $.getJSON(url, {'type' : 'get', 'id' : id}, function(res){
            if(0 === res.code){
                zNodes = res.data;  //将字符串转换成obj

                //页面层
                index = layer.open({
                    type: 1,
                    area:['480px', '500px'],
                    title:'权限分配',
                    skin: 'layui-layer-demo', //加上边框
                    content: $('#role')
                });

                //设置zetree
                var setting = {
                    check:{
                        enable:true
                    },
                    data: {
                        simpleData: {
                            enable: true
                        }
                    }
                };

                $.fn.zTree.init($("#treeType"), setting, zNodes);
                var zTree = $.fn.zTree.getZTreeObj("treeType");
                zTree.expandAll(true);

            }else{
                layer.alert(res.msg, {title: '友情提示', icon: 2});
            }
        });
    }

    //确认分配权限
    $("#postform").click(function(){
        var zTree = $.fn.zTree.getZTreeObj("treeType");
        var nodes = zTree.getCheckedNodes(true);
        var rules = '';
        $.each(nodes, function (n, value) {
            if(n>0){
                rules += ',';
            }
            rules += value.id;
        });

        var id = $("#role_id").val();

        //写入库
        $.post('/index/role/access', {'type' : 'post', 'id' : id, 'rules' : rules}, function(res){
            layer.close(index);
            if(0 === res.code){
                layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function(){
                    window.location.reload();
                });
            }else{
                layer.alert(res.msg, {title: '友情提示', icon: 2});
            }

        }, 'json')
    });
});