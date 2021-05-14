<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>Z-App-Chart</title>
    <script src="{{mix('js/app.js')}}"></script>
</head>
<body>
<div id="calendar" style="width: 100%;height: 300px"></div>
<div id="bar-simple" style="width: 100%;height: 300px"></div>
<div id="bar-simple-earthwork" style="width: 100%;height: 300px"></div>
<div id="bar-race" style="width: 100%;height: 300px"></div>
<div id="bar-race-duration" style="width: 100%;height: 300px"></div>
<div id="gauge" style="width: 50%;height: 400px"></div>
</body>
</html>
<script>
    const calendarChart = echarts.init(document.getElementById('calendar'));

    function getVirtualData(year) {
        year = year || '2021'
        const date = +echarts.number.parseDate(year + '-01-01');
        const end = +echarts.number.parseDate(year + '-05-11');
        const dayTime = 3600 * 24 * 1000;
        const data = [];
        for (let time = date; time <= end; time += dayTime) {
            data.push([
                echarts.format.formatTime('yyyy-MM-dd', time),
                Math.floor(Math.random() * 1000)
            ]);
        }
        return data
    }

    const optionCalendar = {
        title: {
            left: 'center',
            text: '全部设备每日总工作面积'
        },
        tooltip: {},
        visualMap: {
            min: 0,
            max: 1000,
            type: 'piecewise',
            orient: 'horizontal',
            left: 'center',
            top: 65
        },
        calendar: {
            top: 120,
            range: '2021',
            dayLabel: {
                show: true,
                firstDay: 1,
                nameMap: 'cn'
            },
            monthLabel: {
                show: true,
                nameMap: 'cn'
            },
            yearLabel: {
                show: true,
                nameMap: 'cn'
            },

        },
        series: [{
            type: 'heatmap',
            coordinateSystem: 'calendar',
            data: getVirtualData(2021)
        }]
    };
    calendarChart.setOption(optionCalendar);
    calendarChart.on('click', 'series', function (params) {
        alert(params.data[0])
    });

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
                type: 'bar'
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
                type: 'bar'
            },
        ]
    }
    barEarthworkChart.setOption(optionBarEarthwork)


    const barRaceChart = echarts.init(document.getElementById('bar-race'));
    const data = [];
    for (let i = 0; i < 5; ++i) {
        data.push(Math.round(Math.random() * 1000),)
    }
    const optionBarRace = {
        title: {
            left: 'center',
            text: '设备工作面积排行'
        },
        xAxis: {
            max: 'dataMax'
        },
        yAxis: {
            type: 'category',
            data: [
                'A',
                'B',
                'C',
                'D',
                'E',
            ],
            inverse: true,
            animationDuration: 300,
            animationDurationUpdate: 300,
        },
        series: [
            {
                realtimeSort: true,
                name: '工作面积/天',
                type: 'bar',
                data: data,
                label: {
                    show: true,
                    position: 'right',
                    valueAnimation: true
                },
            }
        ],
        legend: {
            top: 30,
            show: true
        },
        animation: false,
        animationDuration: 0,
        animationDurationUpdate: 3000,
        animationEasing: 'linear',
        animationEasingUpdate: 'linear'
    };
    barRaceChart.setOption(optionBarRace)


    const durationBarRaceChart = echarts.init(document.getElementById('bar-race-duration'));
    const dataDuration = [];
    for (let i = 0; i < 5; ++i) {
        dataDuration.push(Math.round(Math.random() * 23),)
    }
    const optionBarRaceDuration = {
        title: {
            left: 'center',
            text: '设备工作时长排行'
        },
        xAxis: {
            max: 'dataMax'
        },
        yAxis: {
            type: 'category',
            data: [
                'A',
                'B',
                'C',
                'D',
                'E',
            ],
            inverse: true,
            animationDuration: 300,
            animationDurationUpdate: 300,
        },
        series: [
            {
                realtimeSort: true,
                name: '工作时长/天',
                type: 'bar',
                data: dataDuration,
                label: {
                    show: true,
                    position: 'right',
                    valueAnimation: true
                },
            }
        ],
        legend: {
            top: 30,
            show: true
        },
        animationDuration: 0,
        animationDurationUpdate: 3000,
        animationEasing: 'linear',
        animationEasingUpdate: 'linear'
    };
    durationBarRaceChart.setOption(optionBarRaceDuration)


    const gaugeChart = echarts.init(document.getElementById('gauge'));
    const optionGauge = {
        title: {
            text: '全部设备平均工作质量',
            left: 'center'
        },
        series: [{
            type: 'gauge',
            startAngle: 180,
            endAngle: 0,
            min: 0,
            max: 1,
            splitNumber: 8,
            axisLine: {
                lineStyle: {
                    width: 6,
                    color: [
                        [0.25, '#FF6E76'],
                        [0.5, '#FDDD60'],
                        [0.75, '#58D9F9'],
                        [1, '#7CFFB2']
                    ]
                },
            },
            pointer: {
                icon: 'path://M12.8,0.7l12,40.1H0.7L12.8,0.7z',
                length: '12%',
                width: 20,
                offsetCenter: [0, '-60%'],
                itemStyle: {
                    color: 'auto'
                }
            },
            axisTick: {
                length: 12,
                lineStyle: {
                    color: 'auto',
                    width: 2
                },
            },
            splitLine: {
                length: 20,
                lineStyle: {
                    color: 'auto',
                    width: 5
                },
            },
            axisLabel: {
                color: '#464646',
                fontSize: 15,
                distance: -60,
                formatter: function (value) {
                    switch (value) {
                        case 0.875:
                            return '优';
                        case 0.625:
                            return '中';
                        case 0.375:
                            return '良';
                        case 0.125:
                            return '差';
                    }
                }
            },
            title: {
                offsetCenter: [0, '-20%'],
                fontSize: 20
            },
            detail: {
                fontSize: 20,
                offsetCenter: [0, '0%'],
                valueAnimation: true,
                formatter: function (value) {
                    return Math.round(value * 100) + '分';
                },
                color: 'auto'
            },
            data: [{
                value: Math.random(),
                name: '工作质量'
            }]
        }]
    }
    gaugeChart.setOption(optionGauge)
</script>
