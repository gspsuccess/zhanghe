$(function () {
    $("#table").bootstrapTable({ // 对应table标签的id
        url: "/index/user/index", // 获取表格数据的url
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
                field: 'realname',
                title: '昵称',
                align: 'center',
                valign: 'middle'
            },{
                field: 'phone',
                title: '手机号',
                align: 'center',
                valign: 'middle'
            },{
                field: 'project.name',
                title: '所属项目',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'massif.name',
                title: '所属地块',
                align: 'center',
                valign: 'middle'
            },{
                field: 'create_time',
                title: '创建时间',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'flag',
                title: '状态',
                align: 'center',
                valign: 'middle'
            },  {
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
                remove(data,'/index/user/delete');
            });

            $('a.edit').click(function(){
                var id = $(this).attr('data-id');

                var project_id = $(this).attr('data-project');
                fillMassifs(project_id);

                edit(id,['project_id','massif_id','realname','phone','id'],'/index/user/edit');
            });

            $('a.flag').click(function(){
                var id = $(this).attr('data-id');
                flag(id,'/index/user/flag');
            });

            $('a.relation').click(function(){
                var id = $(this).attr('data-id');
                selectDevices(id);
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
        result += "<a data-id='" + row.id + "' data-project='" + row.project_id + "' class='btn btn-xs blue edit' title='编辑'><span class='glyphicon glyphicon-pencil'></span></a>";
        result += "<a data-id='" + row.id + "' class='btn btn-xs red remove' title='删除'><span class='glyphicon glyphicon-remove'></span></a>";
        result += "<a data-id='" + row.id + "' class='btn btn-xs btn-warning relation' title='关联设备'><span class='glyphicon glyphicon-list'></span></a>";

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
            realname: {
                message: '昵称不合法',
                validators: {
                    notEmpty: {
                        message: '昵称不能为空'
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
            var url = $('#id').val()?'/index/user/edit':'/index/user/add';
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
        var url = '/index/user/delete';

        if(0 === ids)
        {
            layer.msg('并未选中任何记录');
            return;
        }

        remove(data,url);
    });

    $('#project_id').change(function(){
        var project_id = $('#project_id').val();
        fillMassifs(project_id);
    });

    /**
     * 填充地块下拉列表
     * @param project_id
     */
    function fillMassifs(project_id)
    {
        var url = '/index/ajax/massifs';
        $.post(url, {'project_id': project_id}, function (res) {
            console.log(res);
            $('#massif_id').html(res.data);
        }, 'json');
    }

    /**
     * 获取可关联的设备列表
     * @param id
     */
    function selectDevices(id)
    {
        var url = '/index/user/devices';
        $('#user_id').val(id);
        $.get(url, {'id': id}, function (res) {
            var html = '';
            for(var item of res)
            {
                html += '<div class="checkbox"><label><input type="checkbox" name="ids[]" value="' + item.id + '"' + item.checked + '>' + item.name + '</label></div>';
            }
            $('#modal-body-devices').html(html);
            $('#devices-modal').modal();
        }, 'json');
    }

    var devices_form = $('#devices-form');
    $("#submitBtn-devices").click(function () {
        var url = '/index/user/devices';
        $.ajax({
            url: url,
            async: false,//同步，会阻塞操作
            type: 'POST',//PUT DELETE POST
            data: devices_form.serialize(),
            success: function (res) {
                console.log(res);
                layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function () {
                    window.location.reload();
                });
            }, error: function () {

                layer.msg('出错了');
            }
        })
    });

    /**
     * 点击搜索时触发条件
     */
    $("#search_btn").click(function() {
        $('#table').bootstrapTable('refresh',{query:{pagesize:10,offset:0}});
    });
});