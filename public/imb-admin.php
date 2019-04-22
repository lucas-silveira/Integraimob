<div class="wrap">
	<h1 class="wp-heading-inline">Integraimob</h1>
	<h3><strong>Configuração Gerais</strong></h3>
	<table class="wp-list-table widefat">
		<tr>
			<td>
				<p class="legenda">Selecione as caixas para ativar a integração.</p>
				<form id="config-imb" method="post">
				<p><input type="checkbox" id="option_olx" name="option_olx" <?php ativo('option_olx_ativo'); ?> />
				<input type="text" class="hidden" id="option_olx_ativo" name="option_olx_ativo" value="">
				<label for="option_olx">OLX</label></p>

				<p><input type="checkbox" id="option_zap" name="option_zap" <?php ativo('option_zap_ativo'); ?> />
					<input type="text" class="hidden" id="option_zap_ativo" name="option_zap_ativo" value="">
				<label for="option_zap">ZAP Imóveis</label></p>

				<p><input type="checkbox" id="option_viva_real" name="option_viva_real" <?php ativo('option_viva_real_ativo'); ?> />
					<input type="text" class="hidden" id="option_viva_real_ativo" name="option_viva_real_ativo" value="">
				<label for="option_viva_real">Viva Real</label></p>

				<p><input type="checkbox" id="option_imovel_web" name="option_imovel_web" <?php ativo('option_imovel_web_ativo'); ?> />
					<input type="text" class="hidden" id="option_imovel_web_ativo" name="option_imovel_web_ativo" value="">
				<label for="option_imovel_web">Imóvel Web</label></p>

				<?php submit_button(); ?>
				</form>
			</td>
		</tr>
	</table>
	<span class="space-40"></span>
	<a class="btn-export" href="<?php echo plugins_url(); ?>/integraimob/integraimob.php?export=true">Exportar Imóveis</a>
	<span class="space-40"></span>
	<div id="resultado"></div>
</div>