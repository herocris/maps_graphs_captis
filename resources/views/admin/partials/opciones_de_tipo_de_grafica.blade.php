
<div class="form-group row">
    <label for="tipo_decomiso" class="col-sm-5 col-form-label">Tipo de gráfica</label>
    <div class="tw-toggle2 col-sm-5">
      <input checked type="radio" id="tipo_grafica" name="tipo_grafica" value="bar" data-toggle="tooltip" data-placement="bottom" title="barra">
      <label class="toggle toggle-yes"><i class="fas fa-chart-bar"></i></label>
      <input type="radio" id="tipo_grafica" name="tipo_grafica" value="pie" data-toggle="tooltip" data-placement="bottom" title="pastel">
      <label class="toggle toggle-yes"><i class="fas fa-chart-pie"></i></label>
      <input type="radio" id="tipo_grafica" name="tipo_grafica" value="line" data-toggle="tooltip" data-placement="bottom" title="línea">
      <label class="toggle toggle-yes"><i class="fas fa-chart-line"></i></label>
      <input type="radio" id="tipo_grafica" name="tipo_grafica" value="doughnut" data-toggle="tooltip" data-placement="bottom" title="dona">
      <label class="toggle toggle-yes"><i class="fas fa-circle-notch"></i></label>
  
      
      <span></span>  
    </div>
</div>



<style>
    .tw-toggle2 {
    /* background: #95A5A6; */
    display: inline-block;
    padding: 2px 5px;
    border-radius: 20px;
    position:relative;
    border: 2px solid; #95A5A6;
    width: 60%;
    height: 35px
    }

    .tw-toggle2 label {
    text-align: center;
    font-family: sans-serif;
    display: inline-block;
    color: #95A5A6;
    position:relative;
    z-index:1;
    margin: 0;
    text-align: center;
    padding: 0px 3px;
    font-size: 20px;
    /* cursor: pointer; */
    }

    .tw-toggle2 input {
    /* display: none; */
    position: absolute;
    z-index: 3;
    opacity: 0;
    cursor: pointer;
    width: 15%;
    height: 75%;
    }

    .tw-toggle2 span {
    height: 26px;
    width: 26px;
    line-height: 21px;
    border-radius: 50%;
    background:#fff;
    display:block;
    position:absolute;
    left: 22px;
    top: 3px;
    transition:all 0.3s ease-in-out;
    }

    .tw-toggle2 input[value="bar"]:checked ~ span{
    background:#27ae60;
    left:5px;
    color:#fff;
    }
    .tw-toggle2 input[value="pie"]:checked ~ span{
    background:#27ae60;
    left: 35px;
    }
    .tw-toggle2 input[value="line"]:checked ~ span{
    background:#27ae60;
    left: 63px;
    }
    .tw-toggle2 input[value="doughnut"]:checked ~ span{
    background:#27ae60;
    left: 94px;
    }
   

    .tw-toggle2 input[value="bar"]:checked + label,.tw-toggle2 input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle2 input[value="pie"]:checked + label,.tw-toggle2 input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle2 input[value="line"]:checked + label,.tw-toggle2 input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle2 input[value="doughnut"]:checked + label,.tw-toggle2 input[value="true"]:checked + label{
    color:#fff;
    }
   

   

</style>