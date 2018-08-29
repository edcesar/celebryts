const renderInfo = function({data}) {
    document.querySelector('.info').innerHTML = `
         <ul>
             <li>
                 ${data.nome} 
             </li>
             <ul>
                 <li>
                     ${data.endereco} 
                 </li>
                 <li>
                 <a href="${data.googleMaps}" target="_blank">Google Maps Link</a>   
                 </li>
             </ul>
         </ul>
     `;
 };

 document.querySelector("#btn-localizar").addEventListener("click", function() {
    const twitter = document.querySelector("#txt-localizar").value;
    fetch('api/localizador?target=' + twitter)
    .then(response => response.json())
    .then(data => {
        renderInfo(data);
    })
 });
