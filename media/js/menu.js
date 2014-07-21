Effect.Abrir = function(element) {
 element = $(element);
 new Effect.Appear(element, arguments[1] || {});
};

Effect.Cerrar = function(element) {
 element = $(element).hide();
 new Effect.Fade(element, arguments[1] || {});
 //element.hide();
}; 
 