
function margenganacia(precioCompra){
    const conIva = parseInt(precioCompra) * 1.16;
    var valores = 0;
    if(Math.ceil(conIva)%100 === 0){
      valores = Math.ceil(conIva);
    }else{
      valores= Math.round((conIva+100/2)/100)*100;
      var diferencia = valores - conIva;
      if (diferencia <= 50) {
        valores = valores;
      }else{
        valores = valores - 100;
      }
    }
    return valores;
 }