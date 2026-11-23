function movimiento()
{
    const formMovimiento=document.querySelector("#formMovimiento");
    const producto=document.querySelector("#id_producto");
    const tipo=document.querySelector("#tipo");
    const cantidad=document.querySelector("#cantidad");
    const advertencia=document.querySelector("#advertencia");

    formMovimiento.addEventListener("submit",function(event)
    {
        if(producto.value==="" || tipo.value==="" || cantidad.value.trim()==="")
        {
            event.preventDefault();
            advertencia.textContent="Complete todos los campos obligatorios";
        }
        else if(Number(cantidad.value)<=0)
        {
            event.preventDefault();
            advertencia.textContent="La cantidad debe ser mayor a 0";
        }
        else
        {
            advertencia.textContent="";
        }
    });
}

movimiento();