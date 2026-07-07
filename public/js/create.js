async function makeRequest(url, method, body) {
    try {
        const response = await fetch(url, { "method": method, "body": body });

        if (!response.ok) {
            console.log("Error");
        }

        const data = await response.text();
        console.log(data);
        return data;
    } catch (error) {
        console.error("Request failed", error);
        return null;
    }
}

async function onClick() {
    const input = document.getElementById("url").value;
    console.log(input);
    const payload = {
        "longUrl": input,
    };
    console.log(JSON.stringify(payload));
    const baseUrl = "http://localhost:8000";
    const data = await makeRequest(
        baseUrl + "/link",
        "POST",
        JSON.stringify(payload),
    );
    console.log(data);
    // const button = document.getElementById("shortenButton");
    // const newText = document.createTextNode()
}
