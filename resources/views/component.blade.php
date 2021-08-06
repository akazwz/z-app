<div id="container" style="text-align: center">
    <x-alert title="title"></x-alert>
    <x-my-component></x-my-component>
    <button onclick="handleBtnClick()">click me</button>
</div>

<script>
    const container = document.getElementById('container');
    const myComponentP = document.getElementById('my-component-p');
    const handleBtnClick = () => {
        myComponentP.innerText = 'this is modified by component'
    };
</script>

