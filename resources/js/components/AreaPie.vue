<template>
    <div id="area" :style="{width: '100%',height: '100%'}">
    </div>
</template>

<script>
import * as echarts from "echarts";

export default {
    name: "AreaPie",
    mounted() {
        this.initTable()
        window.addEventListener('resize', function () {
        })
    },
    methods: {
        initTable() {
            let myChart = echarts.init(document.getElementById('area'));
            let option;
            option = {
                legend: {
                    top: '5%',
                    left: 'center'
                },
                series: [
                    {
                        name: '作业地点',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: false,
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: '40',
                                fontWeight: 'bold'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: [
                            {value: 1048, name: '北京'},
                            {value: 735, name: '深圳'},
                            {value: 580, name: '山东'},
                            {value: 484, name: '新疆'},
                        ]
                    }
                ]
            }
            myChart.setOption(option);
        },
    }
}
</script>

<style scoped>

</style>
