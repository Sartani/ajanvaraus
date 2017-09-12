/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$('#ReserveTime').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var aika = button.data('time-to-reserve') // Extract info from data-* attributes
  var reserved_time = button.data('time-to-reserve')
  var day = button.data('day');
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  //document.getElementById("alert-reserve-success").innerHTML = ('Varattava aika:' + reserved_time); 
  modal.find('.modal-title').text('Varaa aika ' + day +'na klo ' + aika  );
  //modal.find('.modal-body input').val(aika);
})

function ReserveButtonConfirm() {
    $(document).ready(function () {


        document.getElementById("alert-reserve-success").style.display = 'block';
        document.getElementById("reserve-button-confirm").disabled = true;
        console.log("bah");
    });

}
function ReserveButtonClose() {
    $(document).ready(function () {
        document.getElementById("reserve-button-confirm").disabled = false;
        document.getElementById("alert-reserve-success").style.display = 'none';
    });
}
