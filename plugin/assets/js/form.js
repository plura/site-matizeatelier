// Matize — AJAX form handler
// Handles any form with [data-mtz-form]. Relies on mtzForms (ajaxUrl, nonce)
// localized via wp_localize_script() in plugin/includes/core/form.php.

function mtzBuildFormData( form ) {

	const formName = form.dataset.formName ?? 'Form Submission';
	const fields   = {};

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

	return body;

}

export function mtzInitForm( form ) {

		// Clear is-invalid on input
		form.addEventListener( 'input', ( e ) => {
			if ( e.target.matches( 'input, textarea' ) ) {
				e.target.classList.remove( 'is-invalid' );
			}
		} );

		form.addEventListener( 'submit', async ( e ) => {
			e.preventDefault();

			const feedback = form.querySelector( '.contact-form__feedback' );
			const submit   = form.querySelector( '[type="submit"]' );

			const setFeedback = ( message, success ) => {
				feedback.textContent = message;
				feedback.classList.remove( 'contact-form__feedback--success', 'contact-form__feedback--error' );
				feedback.classList.add( success ? 'contact-form__feedback--success' : 'contact-form__feedback--error' );
			};

			// Client-side required check — mark invalid fields before sending
			let hasErrors = false;
			form.querySelectorAll( 'input[required], textarea[required]' ).forEach( el => {
				if ( ! el.value.trim() ) {
					el.classList.add( 'is-invalid' );
					hasErrors = true;
				}
			} );

			if ( hasErrors ) {
				setFeedback( 'Please fill in all required fields.', false );
				return;
			}

			const body = mtzBuildFormData( form );

			submit.disabled = true;

			try {
				const res  = await fetch( mtzForms.ajaxUrl, { method: 'POST', body } );
				const data = await res.json();

				setFeedback( data.data?.message ?? '', data.success );

				if ( data.success ) form.reset();
			} catch {
				setFeedback( 'Something went wrong. Please try again.', false );
			} finally {
				submit.disabled = false;
			}
		} );

}
