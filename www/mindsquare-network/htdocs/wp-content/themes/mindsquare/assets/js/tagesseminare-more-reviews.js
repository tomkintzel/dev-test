jQuery(document).ready(function(){
	jQuery("#showMoreReviews").click(function() {
		let moreReviewsLink = jQuery("#showMoreReviews");
		if(moreReviewsLink.text().indexOf("Weitere") >= 0 ) {
			moreReviewsLink.text("- Weniger Kundenstimmen anzeigen");
		} else {
			moreReviewsLink.text("+ Weitere Kundenstimmen ansehen");
		}
	});
});