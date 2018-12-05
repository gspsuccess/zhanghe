$(function () {
    $("#table").bootstrapTable({ // 对应table标签的id
        url: "/index/applyVerify/index", // 获取表格数据的url
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
                field: 'apply_type',
                title: '类别',
                align: 'center',
                valign: 'middle'
            },{
                field: 'member.realname',
                title: '申请人',
                align: 'center',
                valign: 'middle'
            },{
                field: 'create_time',
                title: '申请时间',
                align: 'center',
                valign: 'middle'
            },{
                field: 'subcribe.amount',
                title: '申请水量',
                align: 'center',
                valign: 'middle'
            },{
                field: 'subcribe.starttime',
                title: '申请配水起始时间',
                align: 'center',
                valign: 'middle'
            },{
                field: 'subcribe.endtime',
                title: '申请配水结束时间',
                align: 'center',
                valign: 'middle'
            },{
                field: 'status',
                title: '状态',
                align: 'center',
                valign: 'middle'
            },  {
                title: "操作",
                align: 'center',
                valign: 'middle',
                width: 60, // 定义列的宽度，单位为像素px
                formatter: actionFormatter
            }
        ],
        onLoadSuccess: function () {  //加载成功时执行

            $('a.edit').click(function(){
                var id = $(this).attr('data-id'),
                    url = '/index/applyVerify/edit';

                $.get(url, {'id': id}, function (res) {
                    var data = res.data;
                    if (0 === res.code) {
                        var status = $('#status'),
                            realname = $('#realname'),
                            id = $('#id'),
                            step_id = $('#step_id');

                        var status_str = '';
                        var status_arr = [{"val":2,"name":"通过审核"},{"val":3,"name":"不通过审核"}];
                        for(var item of status_arr)
                        {
                            status_str += '<option value="' + item.val + '">' + item.name + '</option>';
                        }
                        status.html(status_str);
                        realname.val(data.member.realname);
                        id.val(data.id);

                        $('#znzg-modal-label').text('审核记录');
                        $('#znzg-modal').modal();
                    } else {
                        var msg = res.msg ? res.msg : '无效操作';
                        layer.alert(msg, {title: '友情提示', icon: 2});
                    }

                }, 'json');
            });
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
        result += "<a data-id='" + row.id + "' class='btn btn-xs green edit' title='审核该条记录'><span class='glyphicon glyphicon-cog'></span></a>";

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
                validators: {
                    notEmpty: {
                        message: '发票抬头不能为空'
                    }
                }
            },
            amount: {
                message: '发票金额不能为空',
                validators: {
                    notEmpty: {
                        message: '发票金额不能为空'
                    }
                }
            }, contacts: {
                validators: {
                    notEmpty: {
                        message: '联系人不能为空'
                    }
                }
            },phone: {
                validators: {
                    notEmpty: {
                        message: '联系电话不能为空'
                    }
                }
            }, address: {
                validators: {
                    notEmpty: {
                        message: '邮寄地址不能为空'
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
            var url = '/index/applyVerify/edit';
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
        var url = '/index/bill/delete';

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
});