// Run this code only after the page has fully loaded
window.onload = function() {

    // Grab references to commonly used elements on the page
    let button = document.getElementById("lookup");          // Button for country search
    let resultDiv = document.getElementById("result");       // Container where results will be displayed
    let input = document.getElementById("country");          // User input field

    // Handle clicks for the "Lookup" button (Country search)
    button.addEventListener("click", function() {
        let country = input.value.trim(); // Get the user input and remove accidental spaces

        // Display a small loading animation while waiting for data
        resultDiv.innerHTML = `
            <div class="loader"></div>
            <p style="font-size:20px; text-align:center; margin-top:10px;">Searching...</p>
        `;

        // Send a request to the PHP backend to fetch country information
        fetch("world.php?country=" + country)
            .then(response => response.text())  // Convert server response to text
            .then(data => {
                resultDiv.innerHTML = data;     // Display returned HTML in the results div

                // If backend returned nothing, display a friendly message
                if (data.trim() === "") {
                    resultDiv.innerHTML = `
                        <p style="text-align:center; font-size:24px; color:#444;">
                            No results found :(
                        </p>
                    `;
                }
            })
            .catch(error => {
                // If something goes wrong (e.g., network issue), show an error message
                resultDiv.innerHTML = `
                    <p style="color:red; font-size:24px; text-align:center;">
                        Something went wrong. Please try again.
                    </p>
                `;
            });
    });

    // Get reference to the second button for city searches
    let citiesBtn = document.getElementById("lookup-cities");

    // Handle clicks for the "Lookup Cities" button
    citiesBtn.addEventListener("click", function() {
        let country = input.value.trim(); // Reuse the same input field

        // Show a loading state while fetching city data
        resultDiv.innerHTML = `
            <div class="loader"></div>
            <p style="font-size:20px; text-align:center; margin-top:10px;">Searching cities...</p>
        `;

        // Request city information from the backend by sending a special lookup parameter
        fetch("world.php?country=" + country + "&lookup=cities")
            .then(response => response.text())
            .then(data => {
                // If no cities were found, let the user know
                if (data.trim() === "") {
                    resultDiv.innerHTML = `
                        <p style="text-align:center; font-size:24px; color:#444;">
                            No cities found :(
                        </p>
                    `;
                } else {
                    // Otherwise show the returned HTML table
                    resultDiv.innerHTML = data;
                }
            })
            .catch(error => {
                // Handle fetch or server errors gracefully
                resultDiv.innerHTML = `
                    <p style="color:red; font-size:24px; text-align:center;">
                        Something went wrong. Please try again.
                    </p>
                `;
            });
    });

};