<div>
    <p id="my-component-p">this is my component</p>
    <button onclick="handleBtnClickMyComponent()">click my component</button>
</div>
<script>
    const handleBtnClickMyComponent = () => {
        alert('this is my component click')
        const alertP = document.getElementById('alert-p');
        alertP.innerText = 'this is modified by my component';
    };
</script>
