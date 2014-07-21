var items = 1;
function anadirImagen(){
if (items<15){
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
}