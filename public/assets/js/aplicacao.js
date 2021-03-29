$(document).ready(function() {
	// cria as maskaras pre definidas
	$('.data').mask('11/11/1111');
    $('.horas').mask('00:00');
    $('.cep').mask('99999-999');
    $('.cpf').mask('999.999.999-99', {reverse: true});
    $('.moeda').mask('000.000.000.000.000,00', {reverse: true});
	$('.telefone').mask('(00) 000000000');
	$('.celular').mask('(00) 0000-0000',
		{onKeyPress: function(phone, event, currentField, options){
			var new_sp_phone = phone.match(/^(\(1[1-9]\) 9(5[0-9]|6[0-9]|7[01234569]|8[0-9]|9[0-9])[0-9]{1})/g);
			new_sp_phone ? $(currentField).mask('(00) 00000-0000', options) : $(currentField).mask('(00) 0000-0000', options)
		}}
	);

  	//inicializa a validação do formulário
	$("#form1").validate();

	$(".nav a").on("click", function(){
		$(".nav").find(".active").removeClass("active");
		$(this).parent().addClass("active");
	});

	$('a[href^="#"]').on('click', function(e) {
		e.preventDefault();
		var id = $(this).attr('href'),
		targetOffset = $(id).offset().top;
		$('html, body').animate({
			scrollTop: targetOffset - 100
		}, 500);
	});
});
