<div class="container pt-4 pb-4">
    <div class="d-flex justify-content-end">
        <a href="#" class="btn btn-outline-primary btn-back"><- Regresar atrás</a>
    </div>
</div>

<script type="text/javascript">
    let btn_back = document.querySelector(".btn-back");

    btn_back.addEventListener('click', function(e){
        e.preventDefault();
        window.history.back();
    });
</script>



