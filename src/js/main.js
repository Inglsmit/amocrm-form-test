// open popup
const popup = document.getElementsByClassName( 'popup__wrapper' )[ 0 ];
const open_btn = document.getElementsByClassName( 'cta-button' )[ 0 ];
const close_btn = document.getElementsByClassName( 'close' )[ 0 ];

open_btn.addEventListener( 'click', function ( event ) {
	event.preventDefault();
	popup.classList.remove( 'hidden' );
} );

close_btn.addEventListener( 'click', function ( event ) {
	event.preventDefault();
	popup.classList.add( 'hidden' );
} );

// Form submit
const form = document.querySelector( "form" ),
	statusTxt = form.querySelector( ".message" );


form.onsubmit = ( e ) => {
	console.log( "SUBMIT" );
	e.preventDefault();
	statusTxt.style.color = "#0D6EFD";
	statusTxt.style.display = "block";
	statusTxt.innerText = "Сообщение отправляется...";
	form.classList.add( "disabled" );

	let xhr = new XMLHttpRequest();
	xhr.open( "POST", "send.php", true );
	xhr.onload = () => {
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			let response = xhr.response;
			if ( response.indexOf( "Заполните все поля" ) != -1 || response.indexOf( "Введите корректный email" ) != -1 || response.indexOf( "Что-то пошло не так. Обратитесь к админу." ) != -1 ) {
				statusTxt.style.color = "red";
			} else {
				form.reset();
				setTimeout( () => {
					statusTxt.style.display = "none";
				}, 3000 );
			}
			statusTxt.innerText = response;
			form.classList.remove( "disabled" );
		}
	}
	let formData = new FormData( form );
	xhr.send( formData );
}

// Phone validate
const input = document.querySelector( "#phone" );

const prefixNumber = ( str ) => {
	if ( str === "7" ) return "7 (";
	if ( str === "8" ) return "8 (";
	if ( str === "9" ) return "7 (9";
	return "7 (";
};

input.addEventListener( "input", ( e ) => {
	const value = input.value.replace( /\D+/g, "" );
	const numberLength = 11;

	let result;
	if ( input.value.includes( "+8" ) || input.value[ 0 ] === "8" ) {
		result = "";
	} else {
		result = "+";
	}

	for ( let i = 0; i < value.length && i < numberLength; i++ ) {
		switch ( i ) {
			case 0:
				result += prefixNumber( value[ i ] );
				continue;
			case 4:
				result += ") ";
				break;
			case 7:
				result += "-";
				break;
			case 9:
				result += "-";
				break;
			default:
				break;
		}
		result += value[ i ];
	}
	input.value = result;
} );