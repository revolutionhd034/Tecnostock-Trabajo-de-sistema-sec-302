function inicioSecion()
{
    const form=document.querySelector(".form");
    const correo=document.querySelector("#correo");
    const contraseña=document.querySelector("#contraseña");
    let advertencia=document.querySelector("#advertencia");

    form.addEventListener("submit",function(event)
    {
        if(correo.value.trim()==="" || contraseña.value.trim()==="")
        {
            event.preventDefault();
            advertencia.textContent="Credenciales Incorrectas";
        }
    });
}

inicioSecion();

