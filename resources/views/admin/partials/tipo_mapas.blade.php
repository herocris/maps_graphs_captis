
<div class="form-group row">
    <label for="tipo_decomiso" class="col-sm-5 col-form-label">Tipo de mapa</label>
    <div class="tw-toggle3 col-sm-3.5">
      <input checked type="radio" id="tipo_map" name="tipo_map" value="departamentos" data-toggle="tooltip" data-placement="bottom" title="departamentos">
      <label class="toggle toggle-yes"><i class="fas fa-globe-americas"></i></label>
      <input type="radio" id="tipo_map" name="tipo_map" value="ubicaciones" data-toggle="tooltip" data-placement="bottom" title="ubicaciones">
      <label class="toggle toggle-yes"><i class="fas fa-map-marker-alt"></i></label>
      <input type="radio" id="tipo_map" name="tipo_map" value="calor" data-toggle="tooltip" data-placement="bottom" title="calor">
      <label class="toggle toggle-yes"><i class="fas fa-fire"></i></label>

  
      
      <span></span>  
    </div>
</div>



<style>
    .tw-toggle3 {
    /* background: #95A5A6; */
    display: inline-block;
    padding: 2px 5px;
    border-radius: 20px;
    position:relative;
    border: 2px solid; #95A5A6;
    width: 90px;
    height: 35px
    }

    .tw-toggle3 label {
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

    .tw-toggle3 input {
    /* display: none; */
    position: absolute;
    z-index: 3;
    opacity: 0;
    cursor: pointer;
    width: 25%;
    height: 79%;
    }

    .tw-toggle3 span {
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

    .tw-toggle3 input[value="departamentos"]:checked ~ span{
    background:#27ae60;
    left:5px;
    color:#fff;
    }
    .tw-toggle3 input[value="ubicaciones"]:checked ~ span{
    background:#34BEEC;
    left: 31px;
    }
    .tw-toggle3 input[value="calor"]:checked ~ span{
    background:#FF7400 ;
    left: 55px;
    }
   
   

    .tw-toggle3 input[value="departamentos"]:checked + label,.tw-toggle3 input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle3 input[value="ubicaciones"]:checked + label,.tw-toggle3 input[value="true"]:checked + label{
    color:#fff;
    }
    .tw-toggle3 input[value="calor"]:checked + label,.tw-toggle3 input[value="true"]:checked + label{
    color:#fff;
    }
   
   

   

</style>