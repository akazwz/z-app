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


    let xText = ''
    let yText = ''
    let xData = []
    let yData = []
    getBarChartData()

    function getBarChartData() {
        const params = {
            file_name: fileName
        };
        axios.get('/api/chart/to-bar-chart-data', {
            params: params
        }).then( (res) => {
            if (res.data.code === 2000) {
                xText = res.data.data.x_text
                yText = res.data.data.y_text
                xData = res.data.data.x_data
                yData = res.data.data.y_data
                createChart()
            }
        }).catch( (err) => {
        })
    }

    function createChart() {
        const barSimpleChart = echarts.init(document.getElementById('bar-simple'));
        const optionBarSimple = {
            title: {
                left: 'center',
                text: 'Z-APP'
            },
            tooltip: {},
            xAxis: {
                name: xText,
                type: 'category',
                data: xData
            },
            yAxis: {
                name: yText,
                type: 'value'
            },
            series: [
                {
                    name: yText,
                    data: yData,
                    type: 'bar'
                },
            ]
        }
        barSimpleChart.setOption(optionBarSimple)
    }


</script>
