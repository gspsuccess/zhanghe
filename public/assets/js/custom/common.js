/**
 * 操作栏的格式化
 * @param fields  //要填充数据的字段数组
 * @param data    //提供数据的对象
 */
function fillValue(fields,data)
{
    for(var field of fields)
    {
        $('#' + field).val(data[field]);
    }
}

/**
 * 获取将要修改的记录
 * @param id
 * @param fields
 * @param url
 */
function edit(id,fields,url) {
    $.get(url, {'id': id}, function (res) {
        console.log(res);
        var data = res.data;
        if (0 === res.code) {
            fillValue(fields,data);
            $('#znzg-modal-label').text('修改记录');
            $('#znzg-modal').modal();
        } else {
            var msg = res.msg ? res.msg : '无效操作';
            layer.alert(msg, {title: '友情提示', icon: 2});
        }

    }, 'json');
}

/**
 * 删除记录
 * @param data
 * @param url
 */
function remove(data,url) {
    var str = '确定要删除该记录吗？';
    layer.confirm(str, {btn: ['确定', '取消'], title: "提示", icon: 3}, function () {
        $.post(url, data, function (res) {
            console.log(res);
            if (0 === res.code) {
                layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function () {
                    window.location.reload();
                });
            } else {
                var msg = res.msg ? res.msg : '无效操作';
                layer.alert(msg, {title: '友情提示', icon: 2});
            }

        }, 'json');
    })
}

/**
 * 更改数据状态
 * @param id
 * @param url
 */
function flag(id,url){
    var str = '确定要更改该条记录的状态吗？';
    layer.confirm(str, {btn: ['确定', '取消'], title: "提示", icon: 3}, function () {
        $.post(url, {'id': id}, function (res) {
            console.log(res);
            if (0 === res.code) {
                layer.alert(res.msg, {title: '友情提示', icon: 1, closeBtn: 0}, function () {
                    window.location.reload();
                });
            } else {
                var msg = res.msg ? res.msg : '无效操作';
                layer.alert(msg, {title: '友情提示', icon: 2});
            }

        }, 'json');
    })
}

/**
 * 获取要删除的数据ID列表
 * @returns {*}
 */
function getSelectIds()
{
    var rows = $("#table").bootstrapTable('getSelections', function (row) {
        return row;
    });
    var ids = [];

    if(0 === rows.length)
    {
        return 0;
    }
    else
    {
        for(var item of rows)
        {
            ids.push(item.id);
        }

        return ids;
    }
}

/**
 * 清除弹窗原数据
 */
$(function(){
    $("#znzg-modal").on("hidden.bs.modal", function() {
        document.getElementById("znzg-form").reset();
    });
});