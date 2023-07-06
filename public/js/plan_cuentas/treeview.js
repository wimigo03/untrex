!function ($) {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$('#editar-dependiente').hide();
	$(document).on("click","#treeview ul.nav li.parent > a > span.sign", function(){
		$(".habilitado").removeClass("habilitado");
		$(this).parent().toggleClass("habilitado");
		$(this).find('i:first').toggleClass("icon-minus");
		$data = $(this).attr("data-value");
		$codigo = $(this).attr("data-codigo");
		if($codigo == '1'){
			$('#editar-dependiente').hide();
		} else {
			$('#editar-dependiente').show();
		}
		$texto = $(this).next().text();
		$("#parent_id").val($data);
		$("#codigo2").val($texto);
		$("#codigo").val($codigo);
		var id = $data;
		var url1 = $('#create-dependiente').attr('data-url');
		var url2 = $('#editar-dependiente').attr('data-url');
		if(typeof $data === 'undefined' || $data === null){
		} else{
			$.ajax({
				type: 'GET',
				url: '/plandecuentas/cargar/'+id,
				data: {
					id: id
				},
				success: function(data){
				document.getElementById('js_codigo_padre').value = data.codigo_padre;
				document.getElementById('js_nombre').value = data.nombre;
				document.getElementById('js_descripcion').value = data.descripcion;
				document.getElementById('js_cuenta_detalle').value = data.cuenta_detalle;
				document.getElementById('js_cheque').value = data.cheque;
				},
				error: function(xhr){
					console.log(xhr.responseText);
				}
			});
		}

		if(typeof url1 === 'undefined' || url1 === null){
		} else{
			var path1 = url1.replace('create-dependiente',id);
			var path2 = url2.replace('editar-dependiente',id);
			$('#create-dependiente').attr('href',path1);
			$('#editar-dependiente').attr('href',path2);
			$('#editar-dependiente').unbind('click');
		}
	});
	// Open Le current menu
	$("#treeview ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus");
	$("#treeview ul.nav li.current").parents('ul.children').addClass("in");
}(window.jQuery);