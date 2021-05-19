<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>Z-App-Chart</title>
    <script src="{{mix('js/app.js')}}"></script>
    <link type="text/css" href="{{mix('css/app.css')}}"/>
</head>
<body>
<div id="chart-container">
</div>

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
    let yHead = []
    let xData = []
    let yData = []
    getBarChartData()


    function getBarChartData() {
        const params = {
            file_name: fileName
        };
        axios.get('/api/chart/to-bar-chart-data', {
            params: params
        }).then((res) => {
            if (res.data.code === 2000) {
                const {y_head, y_data, x_data, x_text} = res.data.data;
                xText = x_text
                yHead = y_head
                xData = x_data
                yData = y_data
                console.log(yData)
                createChart()
            }
        }).catch((err) => {
            //alert(err.toString())
        })
    }

    function createChart() {
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
                name: '',
                type: 'value'
            },
            toolbox: {
                show: true,
                feature: {
                    saveAsImage: {}
                },
            },
            series: [
                {
                    name: '',
                    data: [],
                    type: 'bar'
                },
            ]
        }

        let str = ''
        for (let i = 0; i < yHead.length; i++) {
            optionBarSimple.yAxis.name = yHead[i]
            optionBarSimple.series[0].name = yHead[i]
            optionBarSimple.series[0].data = yData[i]
            const uuid = generateUUID();
            str = '<div id=' + uuid + ' style="width: 100%;height: 300px"></div>'
            $('#chart-container').append(str)
            echarts.init(document.getElementById(uuid)).setOption(optionBarSimple)
        }

    }

    function generateUUID() {
        let d = new Date().getTime();
        if (window.performance && typeof window.performance.now === "function") {
            d += performance.now(); //use high-precision timer if available
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }


</script>
