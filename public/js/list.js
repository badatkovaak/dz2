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

function onClick(id) {
    return () => {
        const baseUrl = "http://localhost:8000";
        const _ = makeRequest(baseUrl + "/link/" + id, "DELETE");
        window.location.reload();
    };
}
