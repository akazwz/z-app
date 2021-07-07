<!doctype html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>计算面积距离</title>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js">
    </script>
</head>
<body>
<div id="container" style="text-align: center; font-family: SimSun-ExtB,fantasy">
    <p>计算面积距离</p>
</div>

<script type="text/javascript" src="https://webapi.amap.com/maps?v=2.0&key=9c0e1e7ff6fe839045ad54459e9a0b2e"></script>
<script type="text/javascript">
    $.ajax({
        url: 'http://127.0.0.1:8000/api/work-data',
        method: 'get',
        success: function (res) {
            let path = res;
            const distance = Math.round(AMap.GeometryUtil.distanceOfLine(path));
            const area = Math.round(AMap.GeometryUtil.ringArea(path));
            alert(distance)
        }
    })
</script>
</body>
</html>
