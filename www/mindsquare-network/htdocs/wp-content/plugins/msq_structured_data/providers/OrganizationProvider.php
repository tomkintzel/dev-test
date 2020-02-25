<?php

class OrganizationProvider extends DataProvider {
	const PROVIDED_OBJECT = 'organization';

	public function before( WpmPlaceholder &$placeholder ) {
		parent::before( $placeholder );

		switch ( $placeholder->getFormat() ) {
			case 'short':
				$placeholder->addToWhitelist( [ '@type', 'name', 'url', 'logo' ] );
				break;
		}
	}

	public static function get( $id = null, $publisherLogo = false ) {
		if ( !is_numeric( $id ) ) {
			$id = get_current_blog_id();
		}

		switch_to_blog( 37 );
		$organization = get_field( 'sd_organization', 'options' );
		restore_current_blog();

		$result = [
			'@context'        => 'https://schema.org',
			'@type'           => 'Organization',
			'address'         => '{{location.default}}',
			'brand'           => '{{fachbereich.brand id="{{fachbereich.ids}}"}}',
			'subOrganization' => '{{organization id="{{fachbereich.ids exclude-ids=\'37\'}}" format="short"}}',
			'location'        => '{{location.all}}'
		];

		$keys = [
			'name',
			'legal_name',
			'description',
			'url',
			'telephone',
			'email',
			'fax_number',
			'number_of_employees',
			'founding_date',
			'founders',
			'founding_location' => [
				'address_country',
				'address_locality',
				'address_region',
				'postal_code',
				'street_address'
			]
		];

		MSQ_SD_Utils::mapKeys( $keys, $organization, $result, 'MSQ_SD_Utils::toCamelCase' );

		// @type-Attribut zu Firmengründern hinzufügen
		if ( isset( $result['founders'] ) ) {
			foreach ( $result['founders'] as &$founder ) {
				$founder['@type'] = 'Person';
			}
		}

		// @type-Attribut zu Gründungsort hinzufügen und Gründungsort in "address" stecken
		if ( isset( $result['foundingLocation'] ) ) {
			$address = array_splice( $result['foundingLocation'], 0 );
			$result['foundingLocation'] = [
				'@type'   => 'Place',
				'address' => $address
			];
		}

		if ( isset ( $organization['tax_id'] ) ) {
			$result['taxID'] = $organization['tax_id'];
		}

		if ( isset( $organization['awards'] ) ) {
			$result['awards'] = array_column( $organization['awards'], 'name' );
		}

		if ( $id != 37 ) {
			$result = array_merge( $result, [
				"name"               => new WpmPlaceholder( 'fachbereich', [ 'name' ], [ 'id' => $id ] ),
				"parentOrganization" => "{{organization id='37' format='short'}}",
				"description"        => BlogProvider::getDescription( $id ),
				"url"                => BlogProvider::getUrl( $id ),
				"brand"              => "{{fachbereich.brand id='$id'}}",
				"telephone"          => BlogProvider::getTelephone( $id ),
				"email"              => BlogProvider::getEmail( $id )
			] );

			unset( $result['subOrganization'] );
		}

		if ( !empty( $result ) ) {
			$result['logo'] = new WpmPlaceholder( 'fachbereich', [ 'logo' ], [
				'id'             => $id,
				'publisher-logo' => $publisherLogo
			] );
		}

		return $result;
	}
}