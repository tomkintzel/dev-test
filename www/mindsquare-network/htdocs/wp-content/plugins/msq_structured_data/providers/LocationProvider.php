<?php

class LocationProvider extends DataProvider {
	const PROVIDED_OBJECT = 'location';
	private $locations = [];
	private $defaultLocation;

	public function __construct() {
		switch_to_blog( 37 );
		$locations = get_field( 'sd_standorte', 'options' );
		restore_current_blog();

		if ( !empty( $locations ) ) {
			foreach ( $locations as $location ) {
				$this->locations[] = [
					'addressCountry'  => $location['address_country'],
					'addressRegion'   => $location['address_region'],
					'addressLocality' => $location['address_locality'],
					'postalCode'      => $location['postal_code'],
					'streetAddress'   => $location['street_address']
				];

				if ( empty( $this->defaultLocation ) && $location['is_default_location'] ) {
					$this->defaultLocation = $this->locations;
				}
			}
		}
	}

	public function get( $find = false ) {
		$location = null;

		if( !empty( $find ) ) {
			foreach ( $this->locations as $potentialLocation ) {
				if ( strcasecmp( $potentialLocation['addressLocality'], $find ) === 0 ) {
					$location = $potentialLocation;
					break;
				}
			}
		} else {
			return $this->getDefault();
		}

		if ( !empty( $location ) ) {
			$location['@type'] = 'PostalAddress';
		}

		return $location;
	}

	public function getDefault() {
		return $this->defaultLocation;
	}

	public function getAll() {
		$locations = array_values( $this->locations );
		foreach( $locations as &$location ) {
			$location[ '@type' ] = 'PostalAddress';
		}
		return $locations;
	}
}