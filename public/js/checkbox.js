
  $('#chkall').on('click', function(e) {
    if ($(this).is(':checked', true))
    {
      $(".sub_chk").prop('checked', true);
    } else {
      $(".sub_chk").prop('checked', false);
    }
  });

	$('#chkall1-30').on('click', function(e) {
    if ($(this).is(':checked', true))
    {
      $(".sub_chk2").prop('checked', true);
    } else {
      $(".sub_chk2").prop('checked', false);
    }
  });

	$('#chkall31-90').on('click', function(e) {
    if ($(this).is(':checked', true))
    {
      $(".sub_chk3").prop('checked', true);
    } else {
      $(".sub_chk3").prop('checked', false);
    }
  });

	$('.btnagendar').on('click', function(e) {
     var allVal = [];
     var perfil = $('select[name=searchTxt]').val();

     $(".sub_chk:checked").each(function() {
       allVal.push($(this).attr('data-id'));
     });

     $(".sub_chk2:checked").each(function() {
       allVal.push($(this).attr('data-id'));
     });

     $(".sub_chk3:checked").each(function() {
       allVal.push($(this).attr('data-id'));
     });

     if (allVal.length <= 0)
     {
       alert("Por favor selecciona un credito");
     } else {
         var selected_values = allVal.join(",");
         $.ajax({
           url: $(this).data('url'),
           type: 'POST',
           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           data: {ids:selected_values , perfil: perfil},
           success: function(data) {
             if (data['success']) {
               alert(data['success']);
             } else {
               alert('Algo salio mal, por favor intente nuevamente!!');
             }
           }
         });
     }
   });

   $('.btnpdfv').on('click', function(e) {
      var allVal = [];
      var perfil = $('select[name=searchTxt]').val();

      $(".sub_chk:checked").each(function() {
        allVal.push($(this).attr('data-id'));
      });

      if (allVal.length <= 0)
      {
        alert("Por favor selecciona un credito");
      } else {
          var selected_values = allVal.join(",");
          $.ajax({
            url: $(this).data('url'),
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {ids:selected_values , perfil: perfil},
            //xhrFields is what did the trick to read the blob to pdf
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response, status, xhr) {

              var filename = "";
                  var disposition = xhr.getResponseHeader('Content-Disposition');

                   if (disposition) {
                      var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                      var matches = filenameRegex.exec(disposition);
                      if (matches !== null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                  }
                  var linkelem = document.createElement('a');
                  try {
                       var blob = new Blob([response], { type: 'application/octet-stream' });

                      if (typeof window.navigator.msSaveBlob !== 'undefined') {
                          //   IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                          window.navigator.msSaveBlob(blob, filename);
                      } else {
                          var URL = window.URL || window.webkitURL;
                          var downloadUrl = URL.createObjectURL(blob);

                          if (filename) {
                              // use HTML5 a[download] attribute to specify filename
                              var a = document.createElement("a");

                              // safari doesn't support this yet
                              if (typeof a.download === 'undefined') {
                                  window.location = downloadUrl;
                              } else {
                                  a.href = downloadUrl;
                                  a.download = filename;
                                  document.body.appendChild(a);
                                  a.target = "_blank";
                                  a.click();
                              }
                          } else {
                              window.location = downloadUrl;
                          }
                      }

                  } catch (ex) {
                      console.log(ex);
                  }
              }
          });
      }
    });
