jQuery(document).ready(function($){

	(function(){

		$('#config-imb').on('submit', function(){
			$('<div id="message" class="updated notice is-dismissible"><p>Configurações <strong>atualizadas</strong>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dispensar este aviso.</span></button></div>')
			.insertAfter('h1');
		});

		function imbOptionChecked(option1, option2){
			$(option1).on('change', function(){
				if($(this).prop('checked')){
					$(option2+':text').val('true');
				}
				else{
					$(option2+':text').val('false');
				}
			})
		}

		imbOptionChecked('#option_olx', '#option_olx_ativo');
		imbOptionChecked('#option_zap', '#option_zap_ativo');
		imbOptionChecked('#option_viva_real', '#option_viva_real_ativo');
		imbOptionChecked('#option_imovel_web', '#option_imovel_web_ativo');

		function imbLoadOption(option1, option2){
			if($(option1).prop('checked')){
				$(option2+':text').val('true');
			}
			else{
				$(option2+':text').val('false');
			}
		}

		imbLoadOption('#option_olx', '#option_olx_ativo');
		imbLoadOption('#option_zap', '#option_zap_ativo');
		imbLoadOption('#option_viva_real', '#option_viva_real_ativo');
		imbLoadOption('#option_imovel_web', '#option_imovel_web_ativo');


		$('.btn-export').on('click', function(event){
			event.preventDefault();
			const urlXml = $(this).attr('href');

			$.ajax({
				url: urlXml,
				beforeSend : function(){
					$('#resultado').html('Exportando...');
				},
				success: function(result){
					$('#resultado').html(result);
				}
			})
			.fail(function(jqXHR, textStatus, msg){
				$('#resultado').html(msg);
			}); 
		});
	}())
	
})