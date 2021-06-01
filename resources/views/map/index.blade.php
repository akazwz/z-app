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
    /*const map = new AMap.Map("container", {

        resizeEnable: true,
        zoom: 10,
        center: [117.5140277, 39.3873718],
    });*/
    $.ajax({
        url: 'http://127.0.0.1:8000/api/work-area',
        method: 'get',
        success: function (res) {
            let path = res;
            const color = [
                "#ff3a33", "#8d4c13", "#8d4813",
                "#ffcc33", "#468d13", "#8d8713",
                "#33a7ff", "#138d40", "#13718d",
                "#ff3a33", "#8d4c13", "#8d4813",
                "#ffcc33", "#468d13", "#8d8713",
                "#33a7ff", "#138d40", "#13718d",
                "#ff3a33", "#8d4c13", "#8d4813",
                "#ffcc33", "#468d13", "#8d8713",
                "#33a7ff", "#138d40", "#13718d",
                "#ff3a33", "#8d4c13", "#8d4813",
                "#ffcc33", "#468d13", "#8d8713",
                "#33a7ff", "#138d40", "#13718d",
            ]
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
                /*polygon = new AMap.Polygon({
                    strokeColor: '#FF33FF',
                    fillColor: color[i],
                    map: map,
                    fillOpacity: 0.4,
                    path: path[i][1],
                });

                polyline = new AMap.Polyline({
                    map: map,
                    path: path[i][1],     //设置折线的节点数组
                    strokeColor: color[i],
                    strokeOpacity: 1,
                    strokeWeight: 3,
                    strokeDasharray: [10, 5]
                });*/
            }
            alert(arr)
            console.log(arr)
            /*/!*polyline.setMap(map)*!/
            const distance = Math.round(AMap.GeometryUtil.distanceOfLine(path));

            // 计算区域面积
            const area = Math.round(AMap.GeometryUtil.ringArea(path));
            const text = new AMap.Text({
                position: new AMap.LngLat(path[0][0], path[0][1]),
                text: '区域面积' + area + '平方米' + '折线长' + distance + '米',
                offset: new AMap.Pixel(-20, -20)
            });
            map.add(text);
            map.setFitView();
            alert('面积:' + area + '距离:' + distance)*/
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
