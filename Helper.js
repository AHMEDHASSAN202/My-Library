function downloadFile(urlToSend) {
  var req = new XMLHttpRequest();
  req.open('GET', urlToSend, true);
  req.responseType = 'blob';
  req.onload = function(event) {
    var blob = req.response;
    var fileName = req
      .getResponseHeader('content-disposition')
      .replace('attachment; filename=', '');
    var link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = fileName;
    link.click();
  };

  req.send();
}
