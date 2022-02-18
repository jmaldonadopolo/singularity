/*=============================================
Área de arrastre de imágenes
=============================================*/

if($("#columnasSlide").html() == 0){

	$("#columnasSlide").css({"height":"100px"});

}

else{

	$("#columnasSlide").css({"height":"auto"});

}

/*=====  Área de arrastre de imágenes  ======*/

/*=============================================
Subir Imagen
=============================================*/

$("#columnasSlide").on("dragover", function(e){

	e.preventDefault();
	e.stopPropagation();

	$("#columnasSlide").css({"background":"url(views/images/pattern.jpg)"})

})
////////////////////

if($("#columnasSlideinicio").html() == 0){

	$("#columnasSlideinicio").css({"height":"100px"});

}

else{

	$("#columnasSlideinicio").css({"height":"auto"});

}

/*=====  Área de arrastre de imágenes  ======*/

/*=============================================
Subir Imagen
=============================================*/

$("#columnasSlideinicio").on("dragover", function(e){

	e.preventDefault();
	e.stopPropagation();

	$("#columnasSlideinicio").css({"background":"url(views/images/pattern.jpg)"})

})
/*=====  Subir Imagen  ======*/
/*=============================================
Soltar Imagen
=============================================*/

/*=====  Soltar Imagen  ======*/

/*=============================================
Eliminar Item Slide
=============================================*/

$(".eliminarSlide").click(function(){
	///
	Swal.fire({
	  title: 'Eliminar la foto?',
	  text: "si elimina no podrá recuperar",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, Eliminar!',
	  cancelButtonText: 'Cancelar!'
	}).then((result) => {
	  if (result.value) {
		  if($(".eliminarSlide").length == 1){

		$("#columnasSlide").css({"height":"100px"});

	}

	idSlide = $(this).parent().attr("id");
	rutaSlide = $(this).attr("ruta");

	$(this).parent().remove();
	$("#item"+idSlide).remove();

	var borrarItem = new FormData();

	borrarItem.append("idSlide", idSlide);
	borrarItem.append("rutaSlide", rutaSlide);
	console.log(borrarItem.get("idSlide"));
	console.log(borrarItem.get("rutaSlide"));
	$.ajax({

		url:"elimina_foto.php",
		method: "POST",
		data: borrarItem,
		cache: false,
		contentType: false,
		processData: false,
		success: function(respuesta){
			console.log(respuesta);
		}

	})
		Swal.fire(
		  'Eliminado!',
		  'La foto ha sido eliminado.',
		  'success'
		)
	  }
	})
	///

	

})


/*=====  Eliminar Item Slide  ======*/

/*=============================================
Editar Item Slide
=============================================*/



/*=====  Editar  Item Slide  ======*/

/*=============================================
Ordenar Item Slide
=============================================*/

var almacenarOrdenId = new Array();
var ordenItem = new Array();

$("#ordenarSlide").click(function(){

	$("#ordenarSlide").hide();
	$("#guardarSlide").show();

	$("#columnasSlide").css({"cursor":"move"})
	$("#columnasSlide span").hide()

	$("#columnasSlide").sortable({
		revert: true,
		connectWith: ".bloqueSlide",
		handle: ".handleImg",
		stop: function(event){

			for(var i= 0; i < $("#columnasSlide li").length; i++){

				almacenarOrdenId[i] = event.target.children[i].id;
				ordenItem[i]  =  i+1;  			

			}

		}

	});

});

$("#guardarSlide").click(function(){

	$("#ordenarSlide").show();
	$("#guardarSlide").hide();

	for(var i= 0; i < $("#columnasSlide li").length; i++){

		var actualizarOrden = new FormData();
		actualizarOrden.append("actualizarOrdenSlide", almacenarOrdenId[i]);
		actualizarOrden.append("actualizarOrdenItem", ordenItem[i]);
		//console.log(actualizarOrden.get("actualizarOrdenSlide"));
		//console.log(actualizarOrden.get("actualizarOrdenItem"));
		$.ajax({

			url:"ordenar_foto.php",
			method: "POST",
			data: actualizarOrden,
			cache: false,
			contentType: false,
			processData: false,
			success: function(respuesta){
				location.reload();
			}

		})

	}

})

/*=====  Ordenar Item Slide  ======*/


var almacenarOrdenIdinicio = new Array();
var ordenIteminicio = new Array();

$("#ordenarSlideinicio").click(function(){

	$("#ordenarSlideinicio").hide();
	$("#guardarSlideinicio").show();

	$("#columnasSlideinicio").css({"cursor":"move"})
	$("#columnasSlideinicio span").hide()

	$("#columnasSlideinicio").sortable({
		revert: true,
		connectWith: ".bloqueSlideinicio",
		handle: ".handleImginicio",
		stop: function(event){

			for(var i= 0; i < $("#columnasSlideinicio li").length; i++){

				almacenarOrdenIdinicio[i] = event.target.children[i].id;
				ordenIteminicio[i]  =  i+1;  			

			}

		}

	});

});

$("#guardarSlideinicio").click(function(){

	$("#ordenarSlideinicio").show();
	$("#guardarSlideinicio").hide();

	for(var i= 0; i < $("#columnasSlideinicio li").length; i++){

		var actualizarOrden = new FormData();
		actualizarOrden.append("actualizarOrdenSlide", almacenarOrdenIdinicio[i]);
		actualizarOrden.append("actualizarOrdenItem", ordenIteminicio[i]);
		//console.log(actualizarOrden.get("actualizarOrdenSlide"));
		//console.log(actualizarOrden.get("actualizarOrdenItem"));
		$.ajax({

			url:"orden_foto_inicio.php",
			method: "POST",
			data: actualizarOrden,
			cache: false,
			contentType: false,
			processData: false,
			success: function(respuesta){
				//location.reload();
				//console.log(respuesta);
			}

		})

	}

})


