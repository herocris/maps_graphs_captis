var MYLIBRARY = MYLIBRARY || (function(){
    return {
        init : function(variables) {
            var decomiso=variables[0];
            var decomiso_municipio=variables[1];
            var presentaciones_drogas=variables[2];
            var departamentos=variables[3];
            var municipios=variables[4];
            var presentaciones_precursores=variables[5];

            //alert(departamentos);

            ////////////////////
            $('#transporte-b').click(function(e){
                if($('#pais_pro').val()==87){
                    $('#pais_pro').attr("required","");
                    $('#pais_pro').parsley().validate();
                    $("#dep_pro").attr("required","");
                    $('#dep_pro').parsley().validate();
                    $("#mun_pro").attr("required","");
                    $('#mun_pro').parsley().validate();
                }else{
                    //$("#pais_pro").removeAttr("required");
                    $("#dep_pro").removeAttr("required");
                    $("#mun_pro").removeAttr("required");
                }
            
                if($('#pais_des').val()==87){
                    $('#pais_des').attr("required","");
                    $('#pais_des').parsley().validate();
                    $("#dep_des").attr("required","");
                    $('#dep_des').parsley().validate();
                    $("#mun_des").attr("required","");
                    $('#mun_des').parsley().validate();
                }else{
                    //$("#pais_des").removeAttr("required");
                    $("#dep_des").removeAttr("required");
                    $("#mun_des").removeAttr("required");
                }
            });
            
            
            $('#transporte-b2').click(function(e){
                if($('#pais_pro_').val()==87){
                    $('#pais_pro_').attr("required","");
                    $('#pais_pro_').parsley().validate();
                    $("#dep_pro_").attr("required","");
                    $('#dep_pro_').parsley().validate();
                    $("#mun_pro_").attr("required","");
                    $('#mun_pro_').parsley().validate();
                }else{
                    $("#pais_pro_").removeAttr("required");
                    $("#dep_pro_").removeAttr("required");
                    $("#mun_pro_").removeAttr("required");
                }
            
                if($('#pais_des_').val()==87){
                    $('#pais_des_').attr("required","");
                    $('#pais_des_').parsley().validate();
                    $("#dep_des_").attr("required","");
                    $('#dep_des_').parsley().validate();
                    $("#mun_des_").attr("required","");
                    $('#mun_des_').parsley().validate();
                }else{
                    $("#pais_des_").removeAttr("required");
                    $("#dep_des_").removeAttr("required");
                    $("#mun_des_").removeAttr("required");
                }
            });
            
            
            
            $( "#inputFecha_" ).datepicker({
                changeYear:true,
                dayNamesShort: $.datepicker.regional[ "es" ].dayNamesShort,
                dayNames: $.datepicker.regional[ "es" ].dayNames,
                monthNamesShort: $.datepicker.regional[ "es" ].monthNamesShort,
                monthNames: $.datepicker.regional[ "es" ].monthNames,
                dateFormat: "yy-mm-dd",
                maxDate: new Date() 
              });
            
            function c_font_map(vari){
                var contentString =
                            '<div id="content" style="width:40px;color:#17202A">' +
                            ''+vari+''+
                            '</div>';
                return contentString;
            }    
            
            $('#droga-table').DataTable({
                "processing": true,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
        
                "columnDefs": [
                    {
                        "targets": [4, 5, 6, 7],
                        "visible": false,
                        "searchable": false
                    },
                ],
                language: {
                          "decimal": "",
                          "emptyTable": "No hay información",
                          "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                          "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                          "infoPostFix": "",
                          "thousands": ",",
                          "lengthMenu": "Mostrar _MENU_ Entradas",
                          "loadingRecords": "Cargando...",
                          "processing": "Procesando...",
                          "search": "Buscar:",
                          "zeroRecords": "Sin resultados encontrados",
                          "paginate": {
                              "first": "Primero",
                              "last": "Ultimo",
                              "next": "Siguiente",
                              "previous": "Anterior"
                          }
                      },
            });
        
            $('#precursor-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
        
                "columnDefs": [
                    {
                        "targets": [4, 5, 6, 7],
                        "visible": false,
                        "searchable": false
                    },
                ],
                language: {
                          "decimal": "",
                          "emptyTable": "No hay información",
                          "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                          "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                          "infoPostFix": "",
                          "thousands": ",",
                          "lengthMenu": "Mostrar _MENU_ Entradas",
                          "loadingRecords": "Cargando...",
                          "processing": "Procesando...",
                          "search": "Buscar:",
                          "zeroRecords": "Sin resultados encontrados",
                          "paginate": {
                              "first": "Primero",
                              "last": "Ultimo",
                              "next": "Siguiente",
                              "previous": "Anterior"
                          }
                      },
            });
        
        
        
            $('#arma-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
        
                "columnDefs": [
                    {
                        "targets": [2, 3, 4],
                        "visible": false,
                        "searchable": false
                    },
                ],
                language: {
                          "decimal": "",
                          "emptyTable": "No hay información",
                          "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                          "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                          "infoPostFix": "",
                          "thousands": ",",
                          "lengthMenu": "Mostrar _MENU_ Entradas",
                          "loadingRecords": "Cargando...",
                          "processing": "Procesando...",
                          "search": "Buscar:",
                          "zeroRecords": "Sin resultados encontrados",
                          "paginate": {
                              "first": "Primero",
                              "last": "Ultimo",
                              "next": "Siguiente",
                              "previous": "Anterior"
                          }
                      },
            });
        
            $('#municion-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
        
                "columnDefs": [
                    {
                        "targets": [2, 3, 4],
                        "visible": false,
                        "searchable": false
                    },
                ],
                language: {
                          "decimal": "",
                          "emptyTable": "No hay información",
                          "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                          "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                          "infoPostFix": "",
                          "thousands": ",",
                          "lengthMenu": "Mostrar _MENU_ Entradas",
                          "loadingRecords": "Cargando...",
                          "processing": "Procesando...",
                          "search": "Buscar:",
                          "zeroRecords": "Sin resultados encontrados",
                          "paginate": {
                              "first": "Primero",
                              "last": "Ultimo",
                              "next": "Siguiente",
                              "previous": "Anterior"
                          }
                      },
            });
        
            $('#detenido-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
        
                "columnDefs": [
                    {
                        "targets": [10, 11, 12, 13, 14, 15, 16],
                        "visible": false,
                        "searchable": false
                    },
                ],
                language: {
                          "decimal": "",
                          "emptyTable": "No hay información",
                          "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                          "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                          "infoPostFix": "",
                          "thousands": ",",
                          "lengthMenu": "Mostrar _MENU_ Entradas",
                          "loadingRecords": "Cargando...",
                          "processing": "Procesando...",
                          "search": "Buscar:",
                          "zeroRecords": "Sin resultados encontrados",
                          "paginate": {
                              "first": "Primero",
                              "last": "Ultimo",
                              "next": "Siguiente",
                              "previous": "Anterior"
                          }
                      },
            });
        
            $('#transporte-table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "pageLength": 10,
        
                "columnDefs": [
                    {
                        "targets": [11, 12, 13, 14, 15, 16, 17, 18],
                        "visible": false,
                        "searchable": false
                    },
                ],
                language: {
                          "decimal": "",
                          "emptyTable": "No hay información",
                          "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                          "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                          "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                          "infoPostFix": "",
                          "thousands": ",",
                          "lengthMenu": "Mostrar _MENU_ Entradas",
                          "loadingRecords": "Cargando...",
                          "processing": "Procesando...",
                          "search": "Buscar:",
                          "zeroRecords": "Sin resultados encontrados",
                          "paginate": {
                              "first": "Primero",
                              "last": "Ultimo",
                              "next": "Siguiente",
                              "previous": "Anterior"
                          }
                      },
        
            });


            
            
            /////////////////////////
            
        }
    };
}());


