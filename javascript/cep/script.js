document.addEventListener("DOMContentLoaded",function(){

})

async function consultaCEP(cep){
    const response = fetch("https://viacep.com.br/ws/"+cep+"/json/", {
        method: 'GET',
        credentials: 'same-origin'
    });   
   return await response;
}

const form = document.querySelector('form');

form.addEventListener("submit",function(e){
    e.preventDefault();
    let cep = e.target.cep.value;
    let result = consultaCEP(cep);
    result.then(T => T.json()).then(function(data){
        document.querySelector("#teste").innerHTML = JSON.stringify(data);
    })
})