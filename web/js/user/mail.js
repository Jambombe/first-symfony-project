$('#sendMailBtn').click(function(){

    var spamUrl = $(this).data('spam-url');

   $.ajax({
       url: spamUrl,
       type: 'get',
       success: function(response){
           if (response.status == 'ok'){
               // alert('Email envoyé');
               swal('Email envoyé !', '', 'success');
           } else {
               // alert('Erreur :/');
               swal("Une erreur est survenue lors de l'envoi :/", '', 'error');
           }
       }
   });
});