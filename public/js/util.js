$('.btndeletep').on('click', function(e) {
   var idpro = $('input:hidden[name=idp]').val();

   if (idpro > 0)
   {
     if(confirm("¿Desea eliminar el prospecto?")){
       $.ajax({
         url: $(this).data('url'),
         type: 'POST',
         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
         data:{idp:idpro}
       });
     }
   }
 });
