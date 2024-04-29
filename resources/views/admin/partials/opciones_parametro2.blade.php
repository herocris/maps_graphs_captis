<div class="form-group row">
    <label for="tipo_decomiso" class="col-sm-4 col-form-label">Decomiso</label>
    <div class="tw-toggle col-sm-7">
      <input checked type="radio" id="tipo_decomiso" name="tipo_decomiso" value="Droga" data-toggle="tooltip" data-placement="bottom" title="Droga">
      <label class="toggle toggle-yes" id="hdl"><i class="fas fa-cannabis"></i></label>
      <input type="radio" id="tipo_decomiso" name="tipo_decomiso" value="Precursor" data-toggle="tooltip" data-placement="bottom" title="Precursor">
      <label class="toggle toggle-yes"><i class="fas fa-flask"></i></label>
      <input type="radio" id="tipo_decomiso" name="tipo_decomiso" value="Arma" data-toggle="tooltip" data-placement="bottom" title="Arma">
      <label class="toggle toggle-yes"><i class="fas fa-crosshairs"></i></label>
      <input type="radio" id="tipo_decomiso" name="tipo_decomiso" value="Municion" data-toggle="tooltip" data-placement="bottom" title="Munición">
      <label class="toggle toggle-yes"><i class="fas fa-parachute-box"></i></label>
      <input type="radio" id="tipo_decomiso" name="tipo_decomiso" value="Detenido" data-toggle="tooltip" data-placement="bottom" title="Detenido">
      <label class="toggle toggle-yes"><i class="fas fa-user-ninja"></i></label>
      <input type="radio" id="tipo_decomiso" name="tipo_decomiso" value="Transporte" data-toggle="tooltip" data-placement="bottom" title="Transporte">
      <label class="toggle toggle-yes"><i class="fas fa-truck"></i></label>
      
      <span></span>  
    </div>
</div>



<style>
    .tw-toggle {
    /* background: #95A5A6; */
    display: inline-block;
    padding: 2px 5px;
    border-radius: 20px;
    position:relative;
    border: 2px solid #95A5A6;
    width: 60%;
    height: 35px
    }

    .tw-toggle label {
    text-align: center;
    font-family: sans-serif;
    display: inline-block;
    color: #95A5A6;
    position:relative;
    z-index:1;
    margin: 0;
    padding: 0px 3px;
    font-size: 20px;
    /* cursor: pointer; */
    }

    .tw-toggle input {
    /* display: none; */
    position: absolute;
    z-index: 3;
    opacity: 0;
    cursor: pointer;
    width: 15%;
    height: 75%;
    }

    .tw-toggle span {
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

    .tw-toggle input[value="Droga"]:checked ~ span{
    background:#27ae60;
    left:5px;
    color:#fff;
    }
    .tw-toggle input[value="Precursor"]:checked ~ span{
    background:#27ae60;
    left: 33px;
    }
    .tw-toggle input[value="Arma"]:checked ~ span{
    background:#27ae60;
    left: 61px;
    }
    .tw-toggle input[value="Municion"]:checked ~ span{
    background:#27ae60;
    left: 90px;
    }
    .tw-toggle input[value="Detenido"]:checked ~ span{
    background:#27ae60;
    left: 118px;
    }
    .tw-toggle input[value="Transporte"]:checked ~ span{
    background:#27ae60;
    left: 147px;
    }

    .tw-toggle input[value="Droga"]:checked + label,.tw-toggle input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle input[value="Precursor"]:checked + label,.tw-toggle input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle input[value="Arma"]:checked + label,.tw-toggle input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle input[value="Municion"]:checked + label,.tw-toggle input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle input[value="Detenido"]:checked + label,.tw-toggle input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle input[value="Transporte"]:checked + label,.tw-toggle input[value="true"]:checked + label{
    color:#fff;
    }

   

</style>