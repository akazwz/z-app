<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>Z-APP</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}"/>
    <script src="{{mix('js/app.js')}}"></script>
</head>
<header class="head">
    <h4>LINK TO PDF</h4>
</header>
<body class="card-body" style="width: 100%">
<div class="container" style="width: 100%">
    <div class="row clearfix">
        <div class="col-lg-6 col-12 column">
            <div id="input-url" class="input-url">
                <label> URL:
                    <input id="url" type="url" class="form-control-lg" accept="text/uri-list" placeholder="INPUT URL">
                </label>
            </div>
        </div>

        <div class="col-lg-6 col-12 column">
            <button type="button" class="btn btn-block btn-lg btn-info" onclick="checkURL()">
                <span>TO PDF </span>
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
    function checkURL() {
        const url = $('#url');
        const inputURL = $('#input-url');
        let data = {
            url: url.val()
        }
        axios.post('/is-url-valid', data).then(res => {
            if (res.status === 200) {
                self.location.href = '/chose-pdf-option';
            } else {
                alert('url error')
                inputURL.setAttribute('background', 'red')
            }
        })
    }
</script>
<style>
    .container {
        font-family: "Times New Roman", Times, serif;

    }

    .input-url {
        background: #6cb2eb;
        height: 200px;
        border-radius: 5px;
        text-align: center;
        font-size: 15px;
        margin-bottom: 20px;
    }

    .input-url:hover {
        background: transparent;
        height: 230px;
        transition: 0.5s;
        font-size: 20px;
    }

    .input-url label {
        line-height: 200px;
        display: inline;
    }

    .container button {
        height: 200px;
        margin-bottom: 20px;
        font-size: 30px;
    }

    .container button span {
        color: black;
        cursor: pointer;
        display: inline-block;
        position: relative;
        transition: 0.5s;
    }

    .container button span:after {
        content: '»';
        position: absolute;
        opacity: 0;
        top: 0;
        right: -20px;
        transition: 0.5s;
    }

    .container button:hover {
        height: 230px;
        background: white;
        transition: 0.5s;
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
