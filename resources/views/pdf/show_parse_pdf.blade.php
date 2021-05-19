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
    <div id="show-parse-pdf">
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
        self.location.href = '/chart'
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
            if (res.data.code === 2000) {
                details = res.data.data.details
                texts = res.data.data.texts
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
            }
        }).catch((err) => {
            //alert(err.toString())
        })
    }

    function addHtml() {
        let details;
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
</style>
