$(function () {
    $("#table").bootstrapTable({ // 对应table标签的id
        url: "/index/census/waters", // 获取表格数据的url
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
                field: 'value_start',
                title: '开始表底',
                align: 'center',
                valign: 'middle'
            },{
                field: 'value_end',
                title: '结束表底',
                align: 'center',
                valign: 'middle'
            },{
                field: 'amount',
                title: '取水量',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'money',
                title: '应缴费用',
                align: 'center',
                valign: 'middle'
            },{
                field: 'user.realname',
                title: '所属用户',
                align: 'center',
                valign: 'middle'
            }, {
                field: 'create_time',
                title: '创建时间',
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
                remove(data,'/index/device/delete');
            });

            $('a.detail').click(function(){
                var id = $(this).attr('data-id');
                show(id,'/index/waters/show');
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
        result += "<a data-id='" + row.id + "' class='btn btn-xs green detail' title='查看详情'><span class='glyphicon glyphicon-cog'></span></a>";
        result += "<a data-id='" + row.id + "' class='btn btn-xs red remove' title='删除'><span class='glyphicon glyphicon-remove'></span></a>";

        return result;
    }

    /**
     * 批量删除数据
     */
    $('.btn-delete').click(function(){
        var ids = getSelectIds();
        var data = {id:ids};
        var url = '/index/waters/delete';

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