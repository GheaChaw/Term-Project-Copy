function getIdFromUrl(){
    const params = new URLSearchParams(window.location.search);
    return params.get("id");
}

//waits till the contents are loaded first
document.addEventListener('DOMContentLoaded', function(){

    const id = getIdFromUrl();
    if(!id){
        alert("No house id provided in the URL.");
        return;
    }

    let overview = document.querySelector(".overview p");
    let address = document.querySelector(".address p");
    let image = document.querySelector(".img img");
    let contact = document.querySelector(".tooltip-text");
    let features = document.querySelector("#t1 ul");
    let facts = document.querySelector("#t2 ul");

    fetch(`roomspecific.php?id=${id}`)
    .then(response => response.json())
    .then(house => {
        console.log(house);
        // overview.innerText = house.description;
        // address.innerText = house.address;
        // image.src = house.image;
        // contact.textContent = house.contact;

        // let featuresList = "";
        // let factsList = "";
        // let appliances = [];
        
        // if(house.distance != -1){
        //     featuresList += `<li>${house.distance} miles away from campus</li>`;
        // }

        // if(house.parking != 'No Parking'){
        //     featuresList += `<li>${house.parking}</li>`;
        // }

        // if(house.elevator != 'N/A'){
        //     featuresList += `<li>${house.elevator}</li>`;
        // }

        // if(house.other_accessibility != 'N/A'){
        //     featuresList += `<li>${house.other_accessibility}</li>`;
        // }

        // if(house.ramp != 'N/A'){
        //     featuresList += `<li>${house.ramp}</li>`;
        // }
        
        // features.innerHTML = featuresList;

        // if(house.price != -1){
        //     factsList += `<li>${house.price} a month</li>`;
        // }

        // if(house.bedrooms != -1){
        //     factsList += `<li>${house.bedrooms} bedrooms </li>`;
        // }

        // if(house.bathrooms != -1){
        //     factsList += `<li>${house.bathrooms} bathrooms </li>`;
        // }

        // if (house.washdry !== 'N/A') {
        //     appliances.push(house.washdry);
        // }
        
        // if (house.wifi !== 'N/A') {
        //     appliances.push(house.wifi);
        // }
        
        // if (house.heater !== 'N/A') {
        //     appliances.push(house.heater);
        // }
        
        // if (house.dishwasher !== 'N/A') {
        //     appliances.push(house.dishwasher);
        // }
        
        // if (house.ac !== 'N/A') {
        //     appliances.push(house.ac);
        // }

        // if (appliances.length > 0) {
        //     factsList += `<li>Appliances: ${appliances.join(', ')}</li>`;
        // }

        // facts.innerHTML = factsList;

    })

    //error handling if there's any mistakes
    .catch(error => {
        document.body.innerHTML = `<p>Error loading house details: ${error.message}</p>`;
    });

});