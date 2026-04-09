// Matize — AJAX form handler
// Handles any form with [data-mtz-form]. Relies on mtzForms (ajaxUrl, nonce)
// localized via wp_localize_script() in plugin/includes/core/form.php.

document.addEventListener( 'DOMContentLoaded', () => {

	document.querySelectorAll( '[data-mtz-form]' ).forEach( form => {
		form.addEventListener( 'submit', async ( e ) => {
			e.preventDefault();

			const feedback = form.querySelector( '.contact-form__feedback' );
			const submit   = form.querySelector( '[type="submit"]' );
			const formName = form.dataset.formName ?? 'Form Submission';

			// Collect fields with type metadata
			const fields = {};
			form.querySelectorAll( '[name]' ).forEach( el => {
				if ( el.name === 'mtz_website' ) return; // honeypot — sent separately
				const type = el.tagName === 'TEXTAREA' ? 'textarea' : ( el.type || 'text' );
				fields[ el.name ] = {
					label:    el.closest( 'label' )?.firstChild?.textContent?.trim() ?? el.name,
					type,
					value:    el.value,
					required: el.required,
				};
			} );

			const body = new FormData();
			body.append( 'action',      'mtz_form' );
			body.append( 'nonce',       mtzForms.nonce );
			body.append( 'form_name',   formName );
			body.append( 'mtz_website', form.querySelector( '[name="mtz_website"]' )?.value ?? '' );

			Object.entries( fields ).forEach( ( [ key, field ] ) => {
				body.append( `fields[${ key }][label]`,    field.label );
				body.append( `fields[${ key }][type]`,     field.type );
				body.append( `fields[${ key }][value]`,    field.value );
				body.append( `fields[${ key }][required]`, field.required ? '1' : '' );
			} );

			submit.disabled = true;

			try {
				const res  = await fetch( mtzForms.ajaxUrl, { method: 'POST', body } );
				const data = await res.json();

				feedback.textContent = data.data?.message ?? '';
				feedback.className   = 'contact-form__feedback ' +
					( data.success ? 'contact-form__feedback--success' : 'contact-form__feedback--error' );

				if ( data.success ) form.reset();
			} catch {
				feedback.textContent = 'Something went wrong. Please try again.';
				feedback.className   = 'contact-form__feedback contact-form__feedback--error';
			} finally {
				submit.disabled = false;
			}
		} );
	} );

} );
