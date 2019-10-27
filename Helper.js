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

/**
 * Generate SLug Function
 *
 * @param Text
 * @returns {string}
 */
function convertToSlug(Text) {
  return Text.toLowerCase()
    .replace(/ /g, '-')
    .replace(/-+/g, '-')
    .replace(/[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '');
}

function ajax(form, beforeSend, success, done) {
  var data = new FormData($(form)[0]);
  $.ajax({
    url: $(form).attr('action'),
    method: $(form).attr('method'),
    dataType: $(form).data('dataType') ? $(this).data('dataType') : 'json',
    data: data,
    contentType: false,
    cache: false,
    processData: false,
    beforeSend: beforeSend,
    success: success
  }).done(done);
}

function preview() {
  $('.preview').on('change', function() {
    preview_target = $(this).data('preview-target');
    document.getElementById(preview_target).src = window.URL.createObjectURL(
      this.files[0]
    );
  });
}
