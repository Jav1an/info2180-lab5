window.addEventListener('load', () => {
    const countryBtn = document.getElementById("lookupCountry");
    const cityBtn = document.querySelector("#lookupCity");
    const resultDiv = document.querySelector("#result");
    const inputField = document.querySelector("#country");

    const toTitleCase = (str) => {
        return str.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    };

    const fetchData = (lookupType) => {
        const userInput = toTitleCase(inputField.value.trim());

        
        let url;

        if (lookupType === "country") {
            url = `world.php?country=${userInput}&lookup=${lookupType}`;
        } else if (lookupType === "city") {
            
            url = `world.php?city=${userInput}&lookup=${lookupType}`;
        } else {
            
            url = `world.php?lookup=${lookupType}`;
        }

        fetch(url)
            .then(response => {
                if (response.ok) {
                    return response.text();
                } else {
                    return Promise.reject('Something was wrong with the fetch request!');
                }
            })
            .then(data => {
                resultDiv.innerHTML = data;
            })
            .catch(error => console.log(`ERROR HAS OCCURRED: ${error}`));
    };

    countryBtn.addEventListener("click", (e) => {
        e.preventDefault();
        fetchData("country");
    });

    cityBtn.addEventListener("click", (e) => {
        e.preventDefault();
        fetchData("city");
    });
});
