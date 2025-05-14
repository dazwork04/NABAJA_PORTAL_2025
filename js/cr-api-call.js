const generateReport = (host, requestBody) => {
    $('#modal-load-init').modal('show');

    const url = `http://${encodeURI(host)}:8091/api/Report`
    fetch(url, {
        method: 'POST',
        body: JSON.stringify(requestBody),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
    }).then(response => {
        if (!response.ok) throw new Error('Something went wrong. Contact your administrator.');
        return response.blob()
    }).then(data => {
        var file = new Blob([data], { type: 'application/pdf' });
        var fileURL = URL.createObjectURL(file);

        window.open(fileURL);
    }).catch(err => {
        console.log(err)
    })
    .finally(() => {
        $('#modal-load-init').modal('hide');
    })

}