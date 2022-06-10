!function ($) {
    
    // Le left-menu sign
    /* for older jquery version
    $('#treeview ul.nav li.parent > a > span.sign').click(function () {
        $(this).find('i:first').toggleClass("icon-minus");
    }); */
    
    $(document).on("click","#PlanCuentaIndex #treeview ul.nav li.parent > a > span.sign", function(){
		$(".habilitado").removeClass("habilitado");
		$(this).parent().toggleClass("habilitado");;	
        $(this).find('i:first').toggleClass("icon-minus");      

		if($("#idplancuentas").length == 0){
			$data = $(this).attr("data-value");
			$codigo = $(this).attr("data-codigo");
			$nombre = $(this).attr("data-nombre");
			$cuenta = $(this).attr("data-cuenta");		
			$moneda = $(this).attr("data-moneda");
			$descripcion = $(this).attr("data-descripcion");
			$cheque = $(this).attr("data-cheque");
			$auxiliar = $(this).attr("data-auxiliar");
			console.log($data,$codigo,$nombre,$cuenta,$moneda,$descripcion,$cheque,$auxiliar);
			if($cuenta=="1")	$cuenta = '<i class="fa fa-check"></i>';
			else				$cuenta = '<i class="fa fa-close"></i>';
			if($cheque=="1")	$cheque = '<i class="fa fa-check"></i>';
			else				$cheque = '<i class="fa fa-close"></i>';
			if($auxiliar=="1")	$auxiliar = '<i class="fa fa-check"></i>';
			else				$auxiliar = '<i class="fa fa-close"></i>';
			
			$("#padre").val($data);
			$("#codigo").val($codigo);
			$("#nombre").text($nombre);
			$("#cuenta_detalle").html($cuenta);
			$("#moneda").text($moneda);
			$("#descripcion").text($descripcion);
			$("#cheque").html($cheque);
			$("#auxiliar").html($auxiliar);
		}
		$("#buttons-plan-cuentas").show();
		$("#buttons-plan-cuentas #deshabilitar-plan-cuentas").hide();
		$("#buttons-plan-cuentas #habilitar-plan-cuentas").hide();
		if($(this).attr("data-status")=="1")		$("#buttons-plan-cuentas #deshabilitar-plan-cuentas").show();
		else										$("#buttons-plan-cuentas #habilitar-plan-cuentas").show();
		if($(this).attr("data-auxiliar")=="1")		$("#buttons-plan-cuentas #auxiliar-plan-cuentas").show();
		else										$("#buttons-plan-cuentas #auxiliar-plan-cuentas").hide();
    });
	$(document).on("click","#create-plan-cuentas", function(){    
		id = $("#padre").val();
		var url = $('#create-plan-cuentas').attr('data-url');
		var path = url.replace('plan-cuentas-create',id);
		$('#create-plan-cuentas').attr('href',path);
		
    });
	$(document).on("click","#edit-plan-cuentas", function(){    
		id = $("#padre").val();
		var url = $('#edit-plan-cuentas').attr('data-url');
		var path = url.replace('plan-cuentas-edit',id);
		$('#edit-plan-cuentas').attr('href',path);
		
    });
	$(document).on("click","#deshabilitar-plan-cuentas", function(){    
		id = $("#padre").val();
		var url = $('#deshabilitar-plan-cuentas').attr('data-url');
		var path = url.replace('plan-cuentas-deshabilitar',id);
		$('#deshabilitar-plan-cuentas').attr('href',path);
		
    });	
	$(document).on("click","#habilitar-plan-cuentas", function(){    
		id = $("#padre").val();
		var url = $('#habilitar-plan-cuentas').attr('data-url');
		var path = url.replace('plan-cuentas-habilitar',id);
		$('#habilitar-plan-cuentas').attr('href',path);
		
    });		
	$(document).on("click","#auxiliar-plan-cuentas", function(){    
		id = $("#padre").val();
		var url = $('#auxiliar-plan-cuentas').attr('data-url');
		var path = url.replace('plan-cuentas-auxiliar',id);
		$('#auxiliar-plan-cuentas').attr('href',path);
		
    });	
	
    // Open Le current menu
    $("#treeview ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus");
    $("#treeview ul.nav li.current").parents('ul.children').addClass("in");

}(window.jQuery);