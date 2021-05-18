<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>Z-APP</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}"/>
</head>
<header class="head">
    <h4>WELCOME TO Z-APP</h4>
</header>
<body class="card-body" style="width: 100%">
<div class="container" style="width: 100%">
    <div class="row clearfix">
        <div class="col-lg-6 col-12 column">
            <button type="button" class="btn btn-block btn-lg btn-info" onclick="toPDF()">
                <span>PDF </span>
            </button>
        </div>
        <div class="col-lg-6 col-12 column">
            <button type="button" class="btn btn-block btn-lg btn-info">
                <span>IMAGE </span>
            </button>
        </div>
        <div class="col-lg-6 col-12 column">
            <button type="button" class="btn btn-block btn-lg btn-info">
                <span>FILE </span>
            </button>
        </div>
        <div class="col-lg-6 col-12 column">
            <button type="button" class="btn btn-block btn-lg btn-info">
                <span>EXCEL </span>
            </button>
        </div>
        <div class="col-lg-6 col-12 column">
            <button type="button" class="btn btn-block btn-lg btn-info" onclick="toChart()">
                <span>CHART </span>
            </button>
        </div>
        <div class="col-lg-6 col-12 column">
            <button type="button" class="btn btn-block btn-lg btn-info">
                <span>PROFILE </span>
            </button>
        </div>
    </div>
</div>
</body>
<footer class="foot">
    <p>DESIGN BY <a href="https://akazwz.com">ZWZ</a></p>
</footer>
</html>
<script>
    function toPDF() {
        self.location.href = '/pdf';
    }
    function toChart() {
        self.location.href = '/chart';
    }
</script>
<style>
    .container button {
        height: 200px;
        margin-bottom: 20px;
        font-family: "Times New Roman", Times, serif;
        font-size: 30px;
        transition: 0.5s;
    }

    .container button span {
        color: black;
        cursor: pointer;
        display: inline-block;
        position: relative;
    }

    .container button span:after {
        content: '»';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
    }

    .container button:hover {
        height: 230px;
        background: white;
        font-size: 35px;
    }

    .container button:hover span {
        padding-right: 25px;
    }

    .container button:hover span:after {
        opacity: 1;
        right: 0;
    }

    .head h4 {
        text-align: center;
        font-family: "Times New Roman", Times, serif;
        margin-bottom: 20px;
    }

    .foot p {
        text-align: center;
        font-family: "Times New Roman", Times, serif;
    }
</style>
