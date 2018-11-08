
$(document).ready(function() {

 $('#sys_reward').dataTable( {
        "bPaginate": true,
        "bStateSave": true,
	"bSotrt": true,
	"aLengthMenu": [[100, 200, 3000, -1], [100, 200, 300, "All"]],
        "iDisplayLength": 100 
    } );

    /* Init DataTables */
    var oTable = $('#sys_reward').dataTable();

    /* Apply the jEditable handlers to the table */
    $('td:nth-child(7),td:nth-child(6)', oTable.fnGetNodes()).editable( '/reward/updatereward', {
        "callback": function( sValue, y ) {
            var aPos = oTable.fnGetPosition( this );
            oTable.fnUpdate( sValue, aPos[0], aPos[1] );
	    window.location.reload();
        },
        "submitdata": function ( value, settings ) {
            return {
                "resource_id": this.parentNode.getAttribute('id'),
                "column": oTable.fnGetPosition( this )[2]
            };
        },
        "height": "25px",
        "width": "100%"
    } );

     //editable
    $('#sys_reward').dataTable().makeEditable({
        "sAddURL":              "/reward/addreward",
        "sDeleteURL":           "/reward/delete",
        "sAddNewRowFormId":     "formAddNewRow"

    }); 


    $( ".reward_expiration" ).datepicker();


$('#sys_reward').removeClass( 'display' ).addClass('table table-striped table-bordered');

} );

