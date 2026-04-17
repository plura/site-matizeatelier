// Test utility — loaded only when ?test is present in the URL.
// Fills the contact form with random dummy data and opens the modal.

const rand = arr => arr[ Math.floor( Math.random() * arr.length ) ];

const names = [
	'Ana Silva',
	'João Ferreira',
	'Margarida Costa',
	'Rui Oliveira',
];

const emails = [
	'ana.silva@example.com',
	'joao.ferreira@example.com',
	'margarida.costa@example.com',
	'rui.oliveira@example.com',
];

const phones = [
	'+351 912 345 678',
	'+351 963 210 987',
	'+351 934 567 890',
	'+351 916 789 012',
];

const messages = [
	`Olá,\n\nGostaria de agendar uma visita ao atelier para conhecer o vosso trabalho.\nTenho disponibilidade durante a semana, preferencialmente de manhã.\n\nObrigado e até breve.`,
	`Boa tarde,\n\nEstou a renovar o meu apartamento e adorei o vosso portfólio.\nGostaria de saber mais sobre os serviços disponíveis e os respetivos preços.\n\nFico a aguardar resposta.\nCom os melhores cumprimentos.`,
	`Bom dia,\n\nVi o vosso trabalho numa publicação no Instagram e fiquei muito impressionado.\nTenho um projeto de decoração para um espaço comercial e gostaria de marcar uma reunião.\n\nObrigado.`,
	`Olá,\n\nSomos uma empresa de construção e estamos à procura de parceiros para projetos de interiores.\nTeríamos interesse em conversar sobre uma possível colaboração.\n\nAguardamos o vosso contacto.`,
];

function testForm() {
	const form = document.querySelector( '.contact-form[data-mtz-form]' );
	if ( ! form ) return;

	const fill = {
		mtz_name:    rand( names ),
		mtz_email:   rand( emails ),
		mtz_phone:   rand( phones ),
		mtz_message: rand( messages ),
	};

	Object.entries( fill ).forEach( ( [ name, value ] ) => {
		const el = form.querySelector( `[name="${ name }"]` );
		if ( el ) el.value = value;
	} );

	// On the contact page the modal doesn't exist — form is already visible
	document.querySelector( '#contact-modal' )?.showModal();
}

const type = new URLSearchParams( location.search ).get( 'type' );

if ( type === 'form' ) {
	testForm();
}
