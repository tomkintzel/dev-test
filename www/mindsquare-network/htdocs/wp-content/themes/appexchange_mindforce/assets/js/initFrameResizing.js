function initFrameResizing() {
	window.addEventListener('message', function(event) {
		if((event.origin.match(/^https?:\/\/((staging\d*\.|www2\.)?(mindsquare|innotalent|blog\.mindsquare|mindsquare|maint\-care|mind\-force|mind\-forms|erlebe\-software|activate\-hr|mission\-mobile|rz10|compamind|mind\-logistik|freelancercheck|customer-first-cloud|appexchange\.mind\-force)\.de|go\.pardot\.com)$/i) || event.origin == window.location.origin) && event.data && !isNaN(event.data)) {
			var form = document.getElementsByClassName("pardotform");
			for(var i = 0;i < form.length;i++) {
				if(form[i].contentWindow == event.source) {
					form[i].style.height = event.data + 'px';
					break;
				}
			}
		}
	}, false);

	// Erzwinge eine Resize
	var resizeEvent = window.document.createEvent('UIEvents');
	resizeEvent.initUIEvent('resize', true, false, window, 0);
	window.dispatchEvent(resizeEvent);
}
initFrameResizing();

/**
 * Die gesammelten Nutzer-Informationen von den Pardot-Seiten werden
 * in diesem Bereich an Google-Analytics weitergesendet.
 * Dabei müssen die Daten von Pardot wie folgt aufgebaut sein:
 */
if(typeof ga === 'function') {
	window.addEventListener('message', function(event) {
		// Nur die Nachrichten von Pardot für ein GAEvent
		if((event.origin.match(/^https?:\/\/((staging\d*\.|www2\.)?(mindsquare|innotalent|blog\.mindsquare|mindsquare|maint\-care|mind\-force|mind\-forms|erlebe\-software|activate\-hr|mission\-mobile|rz10|compamind|mind\-logistik|freelancercheck|customer-first-cloud|appexchange\.mind\-force)\.de|go\.pardot\.com)$/i) || event.origin == window.location.origin) && (event.data.type||'') == 'GAEvent') {
			var pardotUrl = event.data.url;
			var lookupTime = event.data.lookupTime; // domainLookupEnd - navigationStart
			var responseTime = event.data.responseTime; // responseEnd - connectStart
			var renderingTime = event.data.renderingTime; // loadEventEnd - domLoading
			var loadTime = event.data.loadTime; // loadEventEnd - connectStart
			var completeTime = event.data.completeTime; // loadEventEnd - navigationStart
			if(lookupTime != 'undefined' && lookupTime > 0 && lookupTime < 60000)ga('send', 'event', 'Pardot', 'DNS Auflösung', pardotUrl, lookupTime);
			if(responseTime != 'undefined' && responseTime > 0 && responseTime < 60000)ga('send', 'event', 'Pardot', 'Antwortzeit', pardotUrl, responseTime);
			if(renderingTime != 'undefined' && renderingTime > 0 && renderingTime < 60000)ga('send', 'event', 'Pardot', 'Rendering', pardotUrl, renderingTime);
			if(loadTime != 'undefined' && loadTime > 0 && loadTime < 60000)ga('send', 'event', 'Pardot', 'Anwortzeit und Rendering', pardotUrl, loadTime);
			if(completeTime != 'undefined' && completeTime > 0 && completeTime < 60000)ga('send', 'event', 'Pardot', 'Gesamtzeit', pardotUrl, completeTime);
		}
	});
}

/*
 * Cookies werden zwischen Pardot und Webseite ausgetauscht
 *
 */ 
window.addEventListener('message', function(event) {
	if((event.origin.match(/^https?:\/\/((staging\d*\.|www2\.)?(mindsquare|innotalent|blog\.mindsquare|mindsquare|maint\-care|mind\-force|mind\-forms|erlebe\-software|activate\-hr|mission\-mobile|rz10|compamind|mind\-logistik|freelancercheck|customer-first-cloud|appexchange\.mind\-force)\.de|go\.pardot\.com)$/i) || event.origin == window.location.origin) && (event.data.type||'') == 'cookie') {
		setCookie( 'visitor_id80912', event.data.visitor_id, 3650 );
		setCookie( 'visitor_id80912-hash', event.data.visitor_id_hash, 3650 );
	}
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}