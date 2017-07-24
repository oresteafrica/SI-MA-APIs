$(document).ready(function($) {
//----------------------------------------------------------------------------------------------------------------------
$('.sisma_bu_dataset').click(function() {
var sisma_dataset_id = this.id;
var w = window.open();
$(w.document.body).html('<h2>Aguarde por favor</h2>');
$.ajax({
url: 'dataset.php',
data:'id='+sisma_dataset_id,
type: 'GET',
dataType: 'html',
beforeSend: function(a){ },
success: function(a){
$(w.document.body).html(a);
}, // success
error: function(a,b,c){
$(w.document.body).html('Erro de conexão ao servidor. Tente mais tarde.<br /><br />status = ' + a.status + 
	'<br />responseText = ' + a.responseText + '<br />b = ' + b + '<br />c = ' + c );
},
complete: function(a,b){ }
}); // ajax
}); // click
//----------------------------------------------------------------------------------------------------------------------
$('.sisma_bu_orgunit').click(function() {
sisma_w = window.open('');
sisma_w.document.write('<h2>Aguarde por favor</h2>');
sisma_w.location.href = 'orgunit.php?id='+this.id,'_blank';
}); // click
//----------------------------------------------------------------------------------------------------------------------
}); // $