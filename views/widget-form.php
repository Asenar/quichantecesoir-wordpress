<p>
	<label for="title">
		Titre du widget (optionel)
		<br />
		<input
			type="text" tabindex="1"
			name="<?php echo $this->get_field_name('title'); ?>"
			id="<?php echo $this->get_field_id('title'); ?>"
			value="<?php echo esc_attr($instance['title']); ?>"
		/>
	</label>
</p>
<p>
	<label for="title">
		Nom de l'artiste
		<br />
		<input
			type="text" tabindex="1"
			name="<?php echo $this->get_field_name('artist_name'); ?>"
			id="<?php echo $this->get_field_id('artist_name'); ?>"
			value="<?php echo esc_attr($instance['artist_name']); ?>"
		/>
	</label>
</p>

<p>
	<label for="title">
		Url
		<br />
		<input
			type="text" tabindex="1"
			name="<?php echo $this->get_field_name('url'); ?>"
			id="<?php echo $this->get_field_id('url'); ?>"
			value="<?php echo esc_attr($instance['url']); ?>"
		/>
	</label>
</p>

<p>
	<label for="title">
		<input
			type="hidden" value="0"
			name="<?php echo $this->get_field_name('images'); ?>"
		/>
		<input
			type="checkbox" tabindex="1"
			name="<?php echo $this->get_field_name('images'); ?>"
			id="<?php echo $this->get_field_id('images'); ?>"
			value="1"
<?php echo $instance['images']?'checked="checked"':''; ?>
		/>
		cocher pour afficher les images
	</label>
</p>

<p>
	<label for="title">
		<input
			type="hidden" value="0"
			name="<?php echo $this->get_field_name('table'); ?>"
		/>
		<input
			type="checkbox" tabindex="1"
			name="<?php echo $this->get_field_name('table'); ?>"
			id="<?php echo $this->get_field_id('table'); ?>"
			value="1"
<?php echo $instance['table']?'checked="checked"':''; ?>
		/>
		Cocher pour afficher en mode tableau
	</label>
</p>

<p>
	<label for="custom_order">
		Ordre des infos
		<br />
		<input
			type="text" tabindex="1"
			name="<?php echo $this->get_field_name('custom_order'); ?>"
			id="<?php echo $this->get_field_id('custom_order'); ?>"
			value="<?php echo esc_attr($instance['custom_order']); ?>"
		/>
	</label>
<div class="help-block">
défaut: <code><?php echo Qccs::$default_order; ?></code>
</div>
<div class="help-block">
cases dispo: <code><?php echo Qccs::$available_cells; ?></code>
</div>
</p>

<p>
	<label for="title">
		Limite d'affichage
		<br />
		<input
			type="text" tabindex="1"
			name="<?php echo $this->get_field_name('display_limit'); ?>"
			id="<?php echo $this->get_field_id('display_limit'); ?>"
			value="<?php echo esc_attr($instance['display_limit']); ?>"
			size="4"
		/>
	</label>
	<br />
	<small>Laissez vide pour afficher le nombre par défaut</small>
</p>

