function muestraAlerta(){

	$("#miModal").modal("show");
	
};

function cerrarModal(){
	$("#miModal").modal("hide");
};

function preparar(){
	var contenido = '';
	contenido += '<div id="miModal" class="modal fade" role="dialog">'
 contenido += ' <div class="modal-dialog">'
    
   contenido += ' <div class="modal-content">'
    contenido += '  <div class="modal-header">'
   contenido += '     <button type="button" class="close" data-dismiss="modal">&times;</button>'
   contenido += '   </div>'
   contenido += '   <div class="modal-body">'
   contenido += '     <p>Texto del modal</p>'
   contenido += '   </div>'
   contenido += '   <div class="modal-footer">'
   contenido += '     <button type="button" class="btn btn-success" onclick="cerrarModal();">Cerrar</button>'
   contenido += '   </div>'
 contenido += '  </div>'
  contenido += '</div>'
contenido += '</div>';
document.body.innerHTML += contenido;
}

