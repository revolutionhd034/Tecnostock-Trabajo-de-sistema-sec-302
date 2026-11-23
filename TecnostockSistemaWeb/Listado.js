function busqueda()
{
    const formBuscar=document.querySelector("#formBuscar");
    const buscar=document.querySelector("#buscar");
    const advertencia=document.querySelector("#advertencia");
    

    formBuscar.addEventListener("submit",function(event)
    {
        if(buscar.value.trim()==="")
        {
            event.preventDefault();
            advertencia.textContent="Rellene el campo";
        }
        else
        {
            advertencia.textContent="";
        }
    });
}

busqueda();