var container_1 = document.getElementById("container_1");
var myChart_1 = echarts.init(container_1);
var app_1 = {};
option_1 = null;
option_1 = {
    title: {
        text: '漳河灌区预约配水统计图',
        subtext: '仅供临时展示'
    },
    tooltip: {
        trigger: 'axis'
    },
    legend: {
        data: ['预约量', '配水量']
    },
    toolbox: {
        show: true,
        feature: {
            dataView: {show: true, readOnly: false},
            magicType: {show: true, type: ['line', 'bar']},
            restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    calculable: true,
    xAxis: [
        {
            type: 'category',
            data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
        }
    ],
    yAxis: [
        {
            type: 'value'
        }
    ],
    series: [
        {
            name: '预约量',
            type: 'bar',
            data: [120, 200, 244, 362, 1200, 1420, 2491, 3209, 2276, 1908, 255, 124],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'},
                    {type: 'min', name: '最小值'}
                ]
            },
            markLine: {
                data: [
                    {type: 'average', name: '平均值'}
                ]
            }
        },
        {
            name: '配水量',
            type: 'bar',
            data: [142, 216, 224, 401, 1320, 1568, 2333, 3675, 2198, 2076, 321, 119],
            markPoint: {
                data: [
                    {type: 'max', name: '最大值'},
                    {type: 'min', name: '最小值'}
                ]
            },
            markLine: {
                data: [
                    {type: 'average', name: '平均值'}
                ]
            }
        }
    ]
};
if (option_1 && typeof option_1 === "object") {
    myChart_1.setOption(option_1, true);
}


var container_2 = document.getElementById("container_2");
var myChart_2 = echarts.init(container_2);
var app_2 = {};
option_2 = null;
option_2 = {
    title: {
        text: '年度充值统计',
        subtext: '仅供展示',
        x: 'center'
    },
    tooltip: {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
    },
    series: [
        {
            name: '访问来源',
            type: 'pie',
            radius: '55%',
            center: ['50%', '60%'],
            data: [
                {value: 12000, name: '1月'},
                {value: 235551, name: '2月'},
                {value: 11235, name: '3月'},
                {value: 33212, name: '4月'},
                {value: 56433, name: '5月'},
                {value: 98765, name: '6月'},
                {value: 65342, name: '7月'},
                {value: 13598, name: '8月'},
                {value: 15482, name: '9月'},
                {value: 31011, name: '10月'},
                {value: 23497, name: '11月'},
                {value: 13511, name: '12月'}
            ],
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};

if (option_2 && typeof option_2 === "object") {
    myChart_2.setOption(option_2, true);
}