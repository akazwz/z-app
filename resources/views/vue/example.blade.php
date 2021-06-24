<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <!--1、 引入支持 Bootstrap 的 CSS 样式文件 -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .parent {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            grid-template-rows: repeat(4, 1fr);
            grid-column-gap: 0px;
            grid-row-gap: 0px;
            min-height: 500px;
            max-height: 1000px;
        }

        .parent div {
            max-height: 1000px;
            text-align: center;
        }

        .div1 {
            grid-area: 1 / 1 / 3 / 3;
            background: #1f6fb2;
        }

        .div2 {
            grid-area: 1 / 3 / 3 / 5;
            background: #1b1e21;
        }

        .div3 {
            grid-area: 1 / 5 / 3 / 7;
            background: #2a9055;
        }

        .div4 {
            grid-area: 1 / 7 / 6 / 9;
            background: #387ac1;
        }

        .div5 {
            grid-area: 3 / 1 / 5 / 7;
            background: #636b6f;
        }
    </style>
<body onresize="{function }">
<div id="app" class="parent">
    <div class="div1">A
    </div>
    <div class="div2">B
        <quality-gauge></quality-gauge>
    </div>
    <div class="div3">C
        <area-pie></area-pie>
    </div>
    <div class="div4">D</div>
    <div class="div5">E</div>
</div>

<!-- 2、引入支持Vue框架和Vue组件的app.js文件 -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
<script>
    window.addEventListener('resize', function() {
    })
</script>
<script>
    import QualityGauge from "../../js/components/QualityGauge";
    export default {
        components: {QualityGauge}
    }
</script>
