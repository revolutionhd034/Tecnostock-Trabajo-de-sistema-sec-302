function registrarProducto()
{
    const formRegistro=document.querySelector("#formRegistro");
    const codigo=document.querySelector("#codigo");
    const nombre=document.querySelector("#nombre");
    const precio=document.querySelector("#precio");
    const stockActual=document.querySelector("#stock_actual");
    const stockMinimo=document.querySelector("#stock_minimo");
    const categoria=document.querySelector("#id_categoria");
    const advertencia=document.querySelector("#advertencia");

    formRegistro.addEventListener("submit",function(event)
    {
        if(codigo.value.trim()==="" ||
           nombre.value.trim()==="" ||
           precio.value.trim()==="" ||
           stockActual.value.trim()==="" ||
           stockMinimo.value.trim()==="" ||
           categoria.value==="")
        {
            event.preventDefault();
            advertencia.textContent="Complete todos los campos obligatorios";
        }
        else if(nombre.value.trim().length<3)
        {
            event.preventDefault();
            advertencia.textContent="El nombre debe tener al menos 3 caracteres";
        }
        else if(Number(precio.value)<0)
        {
            event.preventDefault();
            advertencia.textContent="El precio no puede ser negativo";
        }
        else if(Number(stockActual.value)<0)
        {
            event.preventDefault();
            advertencia.textContent="El stock actual no puede ser negativo";
        }
        else if(Number(stockMinimo.value)<0)
        {
            event.preventDefault();
            advertencia.textContent="El stock mínimo no puede ser negativo";
        }
        else
        {
            advertencia.textContent="";
        }
    });
}

registrarProducto();