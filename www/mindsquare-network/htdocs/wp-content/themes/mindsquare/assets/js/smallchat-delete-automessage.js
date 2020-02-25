if ( document.cookie.match(/smallchat-automessage/) ) {
	Smallchat.automessages = {};
} else {
	for ( let key in Smallchat.automessages ) {
		setTimeout( function () {
			document.cookie = "smallchat-automessage=1; path=/";
		}, Smallchat.automessages[key].wait * 1000 );	
	}
}