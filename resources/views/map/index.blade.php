<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <title>面积计算</title>
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js">
    </script>
    <link rel="stylesheet"
          href="https://a.amap.com/jsapi_demos/static/demo-center/css/demo-center.css"
          type="text/css">
    <style>
        html, body, #container {
            height: 100%
        }
    </style>
</head>
<body>
<div id="container">
</div>
<div class="input-card">
    <div class="input-item">
        <label for="lnglat">左击获取经纬度：</label>
        <input type="text" readonly id="lnglat">
    </div>
</div>
<script type="text/javascript" src="https://webapi.amap.com/maps?v=2.0&key=9c0e1e7ff6fe839045ad54459e9a0b2e"></script>
<script type="text/javascript">
    //初始化地图对象，加载地图
    var map = new AMap.Map("container", {
        resizeEnable: true,
        zoom: 10
    });

    $.ajax({
        url: 'http://127.0.0.1:8000/api/work-data',
        method: 'get',
        success: function (res) {
            console.log(res)
            var polygon = new AMap.Polygon({
                map: map,
                fillOpacity:0.4,
                path: res
            });
            // 计算区域面积
            var area = Math.round(AMap.GeometryUtil.ringArea(res));
            var text = new AMap.Text({
                position: new AMap.LngLat(119.28481418,25.76838843),
                text: '区域面积' + area + '平方米',
                offset: new AMap.Pixel(-20, -20)
            })
            map.add(text);
            map.setFitView();
        }
    })

    /*map.on('click', function (e) {
        document.getElementById("lnglat").value = e.lnglat.getLng() + ',' + e.lnglat.getLat()
    });*/
    /*const polygon = new AMap.Polygon({
        map: map,
        fillOpacity: 0.4,
        path: path
    });

    polyline = new AMap.Polyline({
        path: path,     //设置折线的节点数组
        strokeColor: "red",
        strokeOpacity: 1,
        strokeWeight: 3,
        strokeDasharray: [10, 5]
    });

    polyline.setMap(map)

    const distance = Math.round(AMap.GeometryUtil.distanceOfLine(path));

    // 计算区域面积
    const area = Math.round(AMap.GeometryUtil.ringArea(path));
    const text = new AMap.Text({
        position: new AMap.LngLat(117.51449695, 39.38731743),
        text: '区域面积' + area + '平方米' + '折线长' + distance + '米',
        offset: new AMap.Pixel(-20, -20)
    });
    map.add(text);
    map.setFitView();
    alert('面积:' + area + '距离:' + distance)*/
</script>
</body>
</html>
