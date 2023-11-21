document.addEventListener('DOMContentLoaded', () => {
    const heading = document.querySelector("header h1");
    heading.style.color = "#fff";
    heading.style.transition = "all 2s ease-in-out";

    const resultDiv = document.querySelector("div#result");

    const performAjaxCall = (url, event) => {
        event.preventDefault();
        fetch(url, { method: 'GET' })
            .then(resp => resp.text())
            .then(info => {
                resultDiv.innerHTML = info;
            })
            .catch(error => console.error('Error:', error));
    };

    const addButtonClickListener = (buttonId, context) => {
        const button = document.querySelector(`button#${buttonId}`);
        button.addEventListener("click", (event) => {
            const sanitizedVal = document.querySelector("input#country").value.replace(/[-&\/\\#,+()$@|~%!.'":;*?<>{}]/g, '');
            const sanitizedUrl = `world.php?country=${encodeURIComponent(sanitizedVal)}${context ? `&context=${context}` : ''}`;
            performAjaxCall(sanitizedUrl, event);
        });
    };

    addButtonClickListener("lookup");
    addButtonClickListener("citylookup", "cities");
});
