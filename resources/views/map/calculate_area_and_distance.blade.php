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
            const start = new Date().getTime()
            let path = res;
            let pathData;
            let dateData;
            let arr = [];
            for (let i = 0; i < path.length; i++) {
                dateData = path[i][0]
                pathData = path[i][1];
                const distance = Math.round(AMap.GeometryUtil.distanceOfLine(pathData));
                const area = Math.round(AMap.GeometryUtil.ringArea(pathData));
                const data = [dateData, area, distance]
                arr.push(data)
            }
            const stop = new Date().getTime()
            const calculateTime = (stop - start)
            console.log(calculateTime)
            alert(arr)
        }
    })

    function postData(data) {
        $.ajax({
            url: 'http://127.0.0.1:8000/api/work-area-distance',
            method: 'post',
            data: {data: data},
            success: function () {
                alert('上传数据成功')
            },
            error: function () {
                alert('上传数据错误')
            }
        })
    }
</script>
</body>
</html>
