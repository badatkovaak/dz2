async function makeRequest(url, method, body) {
    try {
        const response = await fetch(url, { "method": method, "body": body });

        if (!response.ok) {
            console.log("Error");
        }

        const data = await response.text();
        return data;
    } catch (error) {
        console.error("Request failed", error);
        return null;
    }
}

async function onClick() {
    // const urlInput = document.getElementById("url").value;
    // console.log(urlInput);
    // const typeInput = document.getElementById("")
    // const payload = {
    //     "longUrl": urlInput,
    //     "type": typeInput,
    //     "date": dateInput,
    // };
    // console.log(JSON.stringify(payload));
    // const baseUrl = "http://localhost:8000";
    // const data = await makeRequest(
    //     baseUrl + "/link",
    //     "POST",
    //     JSON.stringify(payload),
    // );
    // console.log(data);
    // const button = document.getElementById("shortenButton");
    // const newText = document.createTextNode()
}

const form = document.getElementById("shorten-form");
form.addEventListener("submit", (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);
    console.log(event);
    const urlInput = formData.get("url");
    const typeInput = formData.get("type");
    const dateInput = formData.get("date");
    const payload = {
        "longUrl": urlInput,
        "type": typeInput,
        "date": dateInput,
    };
    console.log(payload);
    const baseURL = "http://localhost:8000";
    makeRequest(
        baseURL + "/link",
        "POST",
        JSON.stringify(payload),
    ).then((data) => {
        console.log(data);
    });
});
