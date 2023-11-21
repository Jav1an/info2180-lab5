window.addEventListener('load', () => {
    const countryBtn = document.getElementById("lookupCountry");
    const cityBtn = document.querySelector("#lookupCity");
    const resultDiv = document.querySelector("#result");
    const inputField = document.querySelector("#country");

    const fetchData = (lookupType) => {
        const userInput = inputField.value.trim();
        const url = `world.php?country=${userInput}&lookup=${lookupType}`;

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
