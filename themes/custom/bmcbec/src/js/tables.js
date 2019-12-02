/** =========================================================
 * OWI / ONE WAY INNOVATION
 * http://www.onewayinnovations.com
 * Javascript Validation for Datatables Behavior and Export
 * =========================================================
 * Copyright 2017 | 20170825
 * ========================================================= */
 
function dataTablesIni(){
    $('#ResultTable').DataTable({
       responsive: true,
       dom: 'Bfrtip',
       buttons: [
          {
             text: 'Exportar Tabla',
             action: function(){ openExportTable(); }
          },
       ],
       "language": {
          "decimal": ",",
          "thousands": ".",
          "emptyTable":     "No hay información",
          "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
          "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
          "infoFiltered":   "(filtrado de _MAX_ registros)",
          "infoPostFix":    "",
          "thousands":      ",",
          "lengthMenu":     "Mostrar _MENU_ entradas",
          "loadingRecords": "Cargando...",
          "processing":     "Procesando...",
          "search":         "Buscar:",
          "zeroRecords":    "Ningún registro coincide",
          "paginate": {
             "first":      "Primero",
             "last":       "Última",
             "next":       "Siguiente",
             "previous":   "Anterior"
          },
          "aria": {
             "sortAscending":  ": activar para organizar la columna de forma ascendente",
             "sortDescending": ": activar para organizar la columna de forma descendente"
          }
       }
    });
}

function openExportTable(){

    var a = '<html><head><link rel="stylesheet" type="text/css" href="dataTables/new/Bootstrap-3.3.7/css/bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/DataTables-1.10.15/css/dataTables.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/AutoFill-2.2.0/css/autoFill.bootstrap.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/Buttons-1.4.0/css/buttons.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/ColReorder-1.3.3/css/colReorder.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/FixedColumns-3.2.2/css/fixedColumns.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/FixedHeader-3.1.2/css/fixedHeader.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/KeyTable-2.3.0/css/keyTable.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/Responsive-2.1.1/css/responsive.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/RowGroup-1.0.0/css/rowGroup.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/RowReorder-1.2.0/css/rowReorder.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/Scroller-1.4.2/css/scroller.bootstrap.min.css"/><link rel="stylesheet" type="text/css" href="dataTables/new/Select-1.2.2/css/select.bootstrap.min.css"/><script src="jquery.min.js"></script><script type="text/javascript" src="dataTables/new/Bootstrap-3.3.7/js/bootstrap.min.js"></script><script type="text/javascript" src="dataTables/new/JSZip-3.1.3/jszip.min.js"></script><script type="text/javascript" src="dataTables/new/pdfmake-0.1.27/build/pdfmake.min.js"></script><script type="text/javascript" src="dataTables/new/pdfmake-0.1.27/build/vfs_fonts.js"></script><script type="text/javascript" src="dataTables/new/DataTables-1.10.15/js/jquery.dataTables.min.js"></script><script type="text/javascript" src="dataTables/new/DataTables-1.10.15/js/dataTables.bootstrap.min.js"></script><script type="text/javascript" src="dataTables/new/AutoFill-2.2.0/js/dataTables.autoFill.min.js"></script><script type="text/javascript" src="dataTables/new/AutoFill-2.2.0/js/autoFill.bootstrap.min.js"></script><script type="text/javascript" src="dataTables/new/Buttons-1.4.0/js/dataTables.buttons.min.js"></script><script type="text/javascript" src="dataTables/new/Buttons-1.4.0/js/buttons.bootstrap.min.js"></script><script type="text/javascript" src="dataTables/new/Buttons-1.4.0/js/buttons.colVis.min.js"></script><script type="text/javascript" src="dataTables/new/Buttons-1.4.0/js/buttons.flash.min.js"></script><script type="text/javascript" src="dataTables/new/Buttons-1.4.0/js/buttons.html5.min.js"></script><script type="text/javascript" src="dataTables/new/Buttons-1.4.0/js/buttons.print.min.js"></script><script type="text/javascript" src="dataTables/new/ColReorder-1.3.3/js/dataTables.colReorder.min.js"></script><script type="text/javascript" src="dataTables/new/FixedColumns-3.2.2/js/dataTables.fixedColumns.min.js"></script><script type="text/javascript" src="dataTables/new/FixedHeader-3.1.2/js/dataTables.fixedHeader.min.js"></script><script type="text/javascript" src="dataTables/new/KeyTable-2.3.0/js/dataTables.keyTable.min.js"></script><script type="text/javascript" src="dataTables/new/Responsive-2.1.1/js/dataTables.responsive.min.js"></script><script type="text/javascript" src="dataTables/new/Responsive-2.1.1/js/responsive.bootstrap.min.js"></script><script type="text/javascript" src="dataTables/new/RowGroup-1.0.0/js/dataTables.rowGroup.min.js"></script><script type="text/javascript" src="dataTables/new/RowReorder-1.2.0/js/dataTables.rowReorder.min.js"></script><script type="text/javascript" src="dataTables/new/Scroller-1.4.2/js/dataTables.scroller.min.js"></script><script type="text/javascript" src="dataTables/new/Select-1.2.2/js/dataTables.select.min.js"></script><script type="text/javascript" src="_bmcbec-js-datatable-config.js"></script><link rel="stylesheet" type="text/css" href="site.css"><link rel="stylesheet" type="text/css" href="_bmcbec-charts.css"><link rel="stylesheet" type="text/css" href="css.css?family=Varela+Round"></head><body></body></html>';

    var featureTable = document.getElementById("table2export");
    var tablePopup = window.open();
        tablePopup.document.write('<!DOCTYPE html>');        
        tablePopup.document.write(a);
        tablePopup.document.write(featureTable.outerHTML);
        tablePopup.document.write('<script type="text/javascript">dataTablesIniExport();</script>');
        tablePopup.document.close();
}

function dataTablesIniExport(){
    $('#ResultTable1').DataTable({
       responsive: true,
       dom: 'Brti',
       buttons: [
        /*'excel',*/
          {
             extend: 'pdf',
             filename: 'bmc-bec-export',
         download: 'open',
             title: 'BOLSA MERCANTIL DE COLOMBIA \n GESTOR DEL MERCADO DE GAS NATURAL',
             text: 'PDF',
             exportOptions: {
                modifier: {
                }
             }
          },
          {
             extend: 'csv',
             filename: 'bmc-bec-export',
    download: 'open',
             text: 'CSV',
          },
          {
             extend: 'print',
             filename: 'bmc-bec-export',
             text: 'Imprimir',
         title: 'Gestor del Mercado de Gas Natural en Colombia',
         messageTop: 'Bolsa Mercantil de Colombia',
         messageBottom: null,
            customize: function ( win ) {
                $(win.document.body).css( 'font-size', '10pt' );
                $(win.document.body).find( 'table' ).addClass( 'compact' ).css( 'font-size', 'inherit' );
            }
          },
          /*{
             extend: 'excel',
             filename: 'bmc-bec-export',
             text: 'Excel',
          },*/
          {
         extend: 'excelHtml5',
         download: 'open',
             filename: 'bmc-bec-export',
             text: 'Excel',
          }
       ],
       "language": {
          "decimal": ",",
          "thousands": ".",
          "emptyTable":     "No hay información",
          "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
          "infoEmpty":      "Mostrando 0 a 0 de 0 registros",
          "infoFiltered":   "(filtrado de _MAX_ registros)",
          "infoPostFix":    "",
          "thousands":      ",",
          "lengthMenu":     "Mostrar _MENU_ entradas",
          "loadingRecords": "Cargando...",
          "processing":     "Procesando...",
          "search":         "Buscar:",
          "zeroRecords":    "Ningún registro coincide",
          "paginate": {
             "first":      "Primero",
             "last":       "Última",
             "next":       "Siguiente",
             "previous":   "Anterior"
          },
          "aria": {
             "sortAscending":  ": activar para organizar la columna de forma ascendente",
             "sortDescending": ": activar para organizar la columna de forma descendente"
          }
       }
    });
}