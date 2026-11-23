function editarProducto()
{
    const formEditar=document.querySelector("#formEditar");
    const precio=document.querySelector("#precio");
    const stockActual=document.querySelector("#stock_actual");
    const stockMinimo=document.querySelector("#stock_minimo");
    const categoria=document.querySelector("#id_categoria");
    const advertencia=document.querySelector("#advertencia");


    formEditar.addEventListener("submit",function(event)
    {
        if(precio.value.trim()==="" ||
           stockActual.value.trim()==="" ||
           stockMinimo.value.trim()==="" ||
           categoria.value==="")
        {
            event.preventDefault();
            advertencia.textContent="Complete todos los campos obligatorios";
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

editarProducto();