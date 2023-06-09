<?php
/**
 * GraphQL Edge Type - EntryUser
 * Creates a 1:1 relationship between an Entry and the User who created it.
 *
 * @package WPGraphQLGravityForms\Types\Entry
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Entry;

use WP_User;
use WPGraphQL\Model\User;
use GraphQL\Error\UserError;
use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\Type;
use WPGraphQLGravityForms\Interfaces\Field;
use WPGraphQLGravityForms\Types\Entry\Entry;

/**
 * Creates a 1:1 relationship between an Entry and the User who created it.
 */
class EntryUser implements Hookable, Type, Field {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'EntryUser';

	/**
	 * Field registered in WPGraphQL.
	 */
	const FIELD = 'createdBy';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_type' ] );
		add_action( 'graphql_register_types', [ $this, 'register_field' ] );
	}

	/**
	 * Register new edge type.
	 */
	public function register_type() : void {
		register_graphql_type(
			self::TYPE,
			[
				'description' => __( 'The user who created the entry.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'node' => [
						'type'        => 'User',
						'description' => __( 'The user who created the entry.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}

	/**
	 * Register EntryUser query.
	 */
	public function register_field() : void {
		register_graphql_field(
			Entry::TYPE,
			self::FIELD,
			[
				'type'        => self::TYPE,
				'description' => __( 'The user who created the entry.', 'wp-graphql-gravity-forms' ),
				'resolve'     => function( array $entry ) : array {
					$user = isset( $entry['createdById'] ) ? get_userdata( $entry['createdById'] ) : null;

					if ( ! $user instanceof WP_User ) {
						throw new UserError( __( 'The user who created this entry could not be found.', 'wp-graphql-gravity-forms' ) );
					}

					return [
						'node' => new User( $user ),
					];
				},
			]
		);
	}
}
