
$(document).ready(function() {

 $('#search_friendcode').dataTable( {
        "bPaginate": true,
        "bStateSave": true,
	"bSotrt": true,
	"aLengthMenu": [[100, 200, 3000, -1], [100, 200, 300, "All"]],
        "iDisplayLength": 100
    } );

    /* Init DataTables */
    var oTable = $('#search_friendcode').dataTable();

    /* Apply the jEditable handlers to the table */
    $('td:nth-child(7)', oTable.fnGetNodes()).editable( '/search_friendcode/update', {
        "callback": function( sValue, y ) {
            var aPos = oTable.fnGetPosition( this );
            oTable.fnUpdate( sValue, aPos[0], aPos[1] );
        },
        "submitdata": function ( value, settings ) {
            return {
                "user_id": this.parentNode.getAttribute('id'),
                "column": oTable.fnGetPosition( this )[2]
            };
        },
        "height": "25px",
        "width": "100%"
    } );
} );

	$('#search_friendcode').removeClass('display').addClass('table table-striped table-bordered');


