<template>
    <div id="quality" :style="{width: '100%',height: '100%'}">
    </div>
</template>

<script>
import * as echarts from "echarts";

export default {
    name: "QualityGauge",
    mounted() {
        this.initTable()
        window.addEventListener('resize', function () {
        })
    },
    methods: {
        initTable() {
            let myChart = echarts.init(document.getElementById('quality'));
            let option;
            option = {
                series: [{
                    type: 'gauge',
                    startAngle: 90,
                    endAngle: -270,
                    pointer: {
                        show: false
                    },
                    progress: {
                        show: true,
                        overlap: false,
                        roundCap: true,
                        clip: false,
                        itemStyle: {
                            borderWidth: 1,
                            borderColor: '#464646'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            width: 20
                        }
                    },
                    splitLine: {
                        show: false,
                        distance: 0,
                        length: 10
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        show: false,
                        distance: 20
                    },
                    data: [
                        {
                            value: 60,
                            name: '达标率',

                            /*title: {
                                offsetCenter: ['0%', '30%']
                            },
                            detail: {
                                offsetCenter: ['0%', '40%']
                            }*/
                        }
                    ],
                    title: {
                        fontSize: 14
                    },
                    detail: {
                        width: 40,
                        height: 10,
                        fontSize: 15,
                        color: 'auto',
                        borderColor: 'auto',
                        borderRadius: 20,
                        borderWidth: 1,
                        formatter: '{value}%'
                    }
                }]
            };
            myChart.setOption(option);
        },
    }
}
</script>

<style scoped>

</style>
