<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>Z-App-Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.1.1/dist/echarts.min.js"></script>
</head>
<body>
<h1 style="text-align: center">Hello赵文卓</h1>
<div id="bar-simple" style="width: 1000px;height: 300px"></div>
<div id="bar-simple-earthwork" style="width: 1000px;height: 300px"></div>
</body>
</html>
<script>
    const barSimpleChart = echarts.init(document.getElementById('bar-simple'));
    const optionBarSimple = {
        title: {
            left: 'center',
            text: '工作面积'
        },
        tooltip: {},
        xAxis: {
            name: '日期',
            type: 'category',
            data: ['05-01', '05-02', '05-03', '05-04', '05-05', '05-06', '05-07', '05-08', '05-11', '05-12']
        },
        yAxis: {
            name: '面积/m²',
            type: 'value'
        },
        series: [
            {
                name: '工作面积',
                data: [200, 300, 170, 149, 244, 456, 657, 213, 34, 566],
                type: 'bar',
                animation: false,
            },
        ]
    }
    barSimpleChart.setOption(optionBarSimple)

    const barEarthworkChart = echarts.init(document.getElementById('bar-simple-earthwork'));
    const optionBarEarthwork = {
        title: {
            left: 'center',
            text: '工作土方量'
        },
        tooltip: {},
        xAxis: {
            name: '日期',
            type: 'category',
            data: ['05-01', '05-02', '05-03', '05-04', '05-05', '05-06', '05-07', '05-08', '05-11', '05-12']
        },
        yAxis: {
            name: '土方量/m³',
            type: 'value'
        },
        series: [
            {
                name: '工作土方量',
                data: [20, 300, 170, 149, 244, 46, 157, 213, 34, 366],
                type: 'bar',
                animation: false,
            },
        ]
    }
    barEarthworkChart.setOption(optionBarEarthwork)
</script>
