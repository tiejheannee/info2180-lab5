window.onload = function() {
    let button = document.getElementById("lookup");
    let resultDiv = document.getElementById("result");
    let input = document.getElementById("country");

    button.addEventListener("click", function() {
        let country = input.value.trim();

        resultDiv.innerHTML = `
            <div class="loader"></div>
            <p style="font-size:20px; text-align:center; margin-top:10px;">Searching...</p>
        `;

        fetch("world.php?country=" + country)
            .then(response => response.text())
            .then(data => {
                resultDiv.innerHTML = data;

                if (data.trim() === "") {
                    resultDiv.innerHTML = `
                        <p style="text-align:center; font-size:24px; color:#444;">
                            No results found :(
                        </p>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `
                    <p style="color:red; font-size:24px; text-align:center;">
                        Something went wrong. Please try again.
                    </p>
                `;
            });
    });
};