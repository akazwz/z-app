<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>Z-App-Chart</title>
    <script src="{{mix('js/app.js')}}"></script>
    <link type="text/css" href="{{mix('css/app.css')}}"/>
</head>
<body>
<div id="bar-simple" style="width: 100%;height: 300px"></div>
</body>
</html>
<script>
    function getQueryVariable(variable) {
        const query = window.location.search.substring(1);
        const vars = query.split("&");
        for (let i = 0; i < vars.length; i++) {
            const pair = vars[i].split("=");
            if (pair[0] === variable) {
                return pair[1];
            }
        }
        return false;
    }

    fileName = getQueryVariable('file_name')
    if (fileName === false) {
        self.location.href = '/chart'
    }
    params = {
        file_name: fileName
    }
    $(document).ready(function () {
        getBarChartData()
    })

    function getBarChartData() {
        axios.get('/api/chart/to-bar-chart-data', {
            params: params
        }).then( (res) => {
            alert(res.data)
        }).catch( (err) => {

        })
    }


    const barSimpleChart = echarts.init(document.getElementById('bar-simple'));
    const optionBarSimple = {
        title: {
            left: 'center',
            text: 'Z-APP'
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
                type: 'bar'
            },
        ]
    }
    barSimpleChart.setOption(optionBarSimple)
</script>
