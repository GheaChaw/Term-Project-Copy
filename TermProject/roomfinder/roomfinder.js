//function to put housing listings on the page
function getHouse(housingField){
    fetch('roomshowing.php')
    .then(response => response.json())
    .then(houses => {
        console.log("This is from database!");
        console.log("test");
        console.log(houses);
        
        let listItems = "";

        houses.forEach(house =>{
            listItems += `<li><a href="../room/room_withmap.html" id = "${house.id}">
            <img src= "${house.image}" width="230" height="230" alt="House Picture"> 
            <p class = "imageDesc">${house.address}</p></a></li>`;
        });

        housingField.innerHTML = listItems;

    })
    .catch(error => {
        housingField.innerHTML = `<li><p>Error loading houses: ${error.message}</p></li>`;
    });
}



//waits till the contents are loaded first
document.addEventListener('DOMContentLoaded', function(){
    //get the button and the filter popup
    const filterButton = document.getElementById("filterHouse");
    const filterPopup = document.getElementById("popupFilter");
    const filterClose = document.getElementById("xButton");

    //getting the place to throw in a house box
    const housingField = document.getElementById("housingField");

    getHouse(housingField);


    //when the filter button is clicked, make filter popup visible and not visible when clicked again
    filterButton.addEventListener("click", function() {
        if (filterPopup.style.display === "none") {
            //show the filter popup
            filterPopup.style.display = "flex";   
            } 
        else{
            //hiding it by clicking the filter button
            filterPopup.style.display = "none";  
        }
    });

    //when the close button is clicked on the filter form, it hides the selection
    filterClose.addEventListener("click",function(){
        if(filterPopup.style.display === "flex") {
            filterPopup.style.display = "none";
        }
    })

});