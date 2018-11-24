var tiposValidos =
        [
            'image/jpeg',
            'image/png'
        ];
function validarTipos(file){
    for(var i=0; i<tiposValidos.length;i++){
        if(file.type===tiposValidos[i]){
            return true;
        }
    }
    return false;
}
        
function onChange(event){
    var file = event.target.files[0]; //seleccionamos el archivo
    if(validarTipos(file)){
        var tapaMiniatura = document.getElementById('tapaThumb');        
//        tapaMiniatura.src = window.URL.createObjectURL(file); esto es lo mismo que lo de abajo
        tapaThumb.src = window.URL.createObjectURL(file);
               
    }
}