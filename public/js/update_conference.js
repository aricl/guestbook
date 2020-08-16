$('#updateConference').on('show.bs.modal', function (event) {
  let button = $(event.relatedTarget); // Button that triggered the modal
  let id = button.data('id'); // Extract info from data-* attributes

  // Create Request
  let request = new XMLHttpRequest();
  if (!request) {
    return false;
  }

  // Send request
  request.onreadystatechange = handleResponse;
  request.open('GET', '/admin/conferences/' + id);
  request.send();

  function handleResponse() {
    if (request.readyState === XMLHttpRequest.DONE) {
      if (request.status === 200) {
        let jsonResponseData = request.response;
        let responseDataArray = JSON.parse(jsonResponseData);

        let modal = $('#updateConference');
        // Loop over the data returned by the ajax request and populate input fields with the values returned
        for (let [datumKey, datumValue] of Object.entries(responseDataArray)) {
          modal.find('#' + datumKey + 'Field').val(datumValue);
        }
      } else {
        alert('There was a problem with the request.');
      }
    }
  }
})
