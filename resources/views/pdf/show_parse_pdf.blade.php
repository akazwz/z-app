<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>Z-APP</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}"/>
    <script src="{{mix('js/app.js')}}"></script>
</head>
<header class="head">
    <h4>PDF</h4>
</header>
<body class="card-body" style="width: 100%">
<div class="container" style="width: 100%">
    <div class="spinner" id="loading">
        <h1>Loading</h1>
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
    </div>
    <div id="show-parse-pdf" style="display: none">
        <div>
            <p>PDF-DETAILS:</p>
            <div id="pdf-details">
                Author : <strong id="author"></strong> <br>
                Comments : <strong id="comments"></strong> <br>
                Company : <strong id="company"></strong> <br>
                CreationDate : <strong id="creation-date"></strong> <br>
                Creator : <strong id="creator"></strong> <br>
                Keywords : <strong id="keywords"></strong> <br>
                ModDate : <strong id="mod-date"></strong> <br>
                Pages : <strong id="pages"></strong> <br>
                Producer : <strong id="producer"></strong> <br>
                SourceModified : <strong id="source-modified"></strong> <br>
                Subject : <strong id="subject"></strong> <br>
                Title : <strong id="title"></strong> <br>
            </div>
            <br>
            <p>PDF-TEXTS:</p>
            <div id="pdf-text">
            </div>
        </div>
    </div>
</div>
</body>
<footer class="foot">
    <p>DESIGN BY <a href="https://akazwz.com">ZWZ</a></p>
</footer>
</html>
<script>
    function getQueryVariable(variable) {
        const query = window.location.search.substring(1);
        const vars = query.split("&");
        for (let i = 0; i < vars.length; i++) {
            const pair = vars[i].split("=");
            if (pair[0] === variable) {
                return pair[1];
            }
        }
        return false;
    }

    let fileName = getQueryVariable('file_name')
    if (fileName === false) {
        window.location.href = '/chart'
    }

    getParsePDFData()

    function getParsePDFData() {
        const params = {
            file_name: fileName
        };
        axios.get('/api/pdf/parse', {
            params: params
        }).then((res) => {
            let details;
            let texts;
            let str = '';
            if (res.data.code === 2000) {
                const {details: details1, texts: texts1} = res.data.data;
                details = details1
                const {
                    Creator,
                    Comments,
                    Title,
                    Subject,
                    Keywords,
                    Company,
                    CreationDate,
                    Pages,
                    Author,
                    SourceModified,
                    ModDate,
                    Producer
                } = details;
                $('#author').text(Author)
                $('#comments').text(Comments)
                $('#company').text(Company)
                $('#creation-date').text(CreationDate)
                $('#creator').text(Creator)
                $('#keywords').text(Keywords)
                $('#mod-date').text(ModDate)
                $('#pages').text(Pages)
                $('#producer').text(Producer)
                $('#source-modified').text(SourceModified)
                $('#subject').text(Subject)
                $('#title').text(Title)
                texts = texts1
                for (let i = 0; i < texts.length; i++) {
                    str += '<details><summary> ' + 'PAGE' + (i + 1) + '</summary>' + texts[i] + '</details>'
                }
                $('#loading').css('display', 'none')
                $('#show-parse-pdf').css('display', 'block')
                $('#pdf-text').html(str)
            }
        }).catch((err) => {
            alert(err.toString())
        })
    }
</script>
<style>
    .container {
        font-family: "Times New Roman", Times, serif;
    }

    #loadingDiv {
        text-align: center;
        font-size: 30px;
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

    .spinner {
        width: 100px;
        height: 100px;

        position: relative;
        margin: 100px auto;
    }

    .double-bounce1, .double-bounce2 {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #6cb2eb;
        opacity: 1;
        position: absolute;
        top: 40px;
        left: 0;

        -webkit-animation: bounce 2.0s infinite ease-in-out;
        animation: bounce 2.0s infinite ease-in-out;
    }

    .double-bounce2 {
        -webkit-animation-delay: -1.0s;
        animation-delay: -1.0s;
    }

    @-webkit-keyframes bounce {
        0%, 100% {
            -webkit-transform: scale(0.0)
        }
        50% {
            -webkit-transform: scale(1.0)
        }
    }

    @keyframes bounce {
        0%, 100% {
            transform: scale(0.0);
            -webkit-transform: scale(0.0);
        }
        50% {
            transform: scale(1.0);
            -webkit-transform: scale(1.0);
        }
    }
</style>
