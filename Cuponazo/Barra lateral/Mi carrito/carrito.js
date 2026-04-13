<button onclick="addToCart()">Añadir al carrito</button> 

//CODIGO JAVASCRIPT FUNCION PARA AÑADIR AL CARRITO , FALTA CAMBIAR VALORES DE PRODUCTOS 

 function addToCart() { 

  const producto = { 

    id: 1, 

    nombre: "Camiseta", 

    precio: 20 

  }; 

 

  // Obtener carrito actual 

  let carrito = JSON.parse(localStorage.getItem("carrito")) || []; 

 

  // Añadir producto 

  carrito.push(producto); 

 

  // Guardar de nuevo 

  localStorage.setItem("carrito", JSON.stringify(carrito)); 

 

  alert("Producto añadido al carrito"); 

} 



 
