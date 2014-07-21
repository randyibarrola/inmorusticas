var items = 1;
function anadirImagen(){
 div=document.getElementById("imagenes");
 img=document.getElementById("imagen0");
 items++;
 itemnuevo="<label for=\"imagen"+items+"\"> Nueva Imagen " + (items-1) + "</label>:";
 itemnuevo+="<input type=\"file\" id=\"imagen"+items+"\" name=\"imagenes[]";
 itemnuevo+="\"><br>";
 nodonuevo=document.createElement("span");
 nodonuevo.innerHTML=itemnuevo;
 div.insertBefore(nodonuevo,img);
}
var docs = 1;
function anadirDocumento(){
div=document.getElementById("documentos");
 img=document.getElementById("documento0");
 docs++;
 itemnuevo="<label for=\"documento"+docs+"\"> Nuevo Documento " + (docs-1) + "</label>:";
 itemnuevo+="<input type=\"file\" id=\"documento"+docs+"\" name=\"documentos[]";
 itemnuevo+="\"><br>";
 nodonuevo=document.createElement("span");
 nodonuevo.innerHTML=itemnuevo;
 div.insertBefore(nodonuevo,img);	
}