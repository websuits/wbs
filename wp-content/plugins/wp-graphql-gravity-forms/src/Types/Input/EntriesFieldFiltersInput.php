<?php
/**
 * GraphQL Input Type - EntriesFieldFiltersInput
 * Field Filters input type for Entries queries.
 *
 * @package WPGraphQLGravityForms\Types\Input
 * @since   0.0.1
 */

namespace WPGraphQLGravityForms\Types\Input;

use WPGraphQLGravityForms\Interfaces\Hookable;
use WPGraphQLGravityForms\Interfaces\InputType;
use WPGraphQLGravityForms\Types\Enum\FieldFiltersOperatorInputEnum;

/**
 * Class - EntriesFieldFiltersInput
 */
class EntriesFieldFiltersInput implements Hookable, InputType {
	/**
	 * Type registered in WPGraphQL.
	 */
	const TYPE = 'EntriesFieldFiltersInput';

	/**
	 * Register hooks to WordPress.
	 */
	public function register_hooks() : void {
		add_action( 'graphql_register_types', [ $this, 'register_input_type' ] );
	}

	/**
	 * Register input type to GraphQL schema.
	 */
	public function register_input_type() : void {
		register_graphql_input_type(
			self::TYPE,
			[
				'description' => __( 'Field Filters input fields for Entries queries.', 'wp-graphql-gravity-forms' ),
				'fields'      => [
					'key'          => [
						'type'        => 'String',
						'description' => __( 'The ID of the field to filter by. Use "0" to search all keys. You can also use the names of the columns in Gravity Forms\' database table for entries, such as "date_created", "is_read, "created_by", etc.', 'wp-graphql-gravity-forms' ),
					],
					'operator'     => [
						'type'        => FieldFiltersOperatorInputEnum::$type,
						'description' => __( 'The operator to use for filtering.', 'wp-graphql-gravity-forms' ),
					],
					// @TODO - Is there a cleaner way to do this? Values can be any of these types.
					'stringValues' => [
						'type'        => [ 'list_of' => 'String' ],
						'description' => __( 'The field value(s) to filter by. Must be string values. If using this field, do not also use intValues, floatValues or boolValues.', 'wp-graphql-gravity-forms' ),
					],
					'intValues'    => [
						'type'        => [ 'list_of' => 'Int' ],
						'description' => __( 'The field value(s) to filter by. Must be integer values. If using this field, do not also use stringValues, floatValues or boolValues.', 'wp-graphql-gravity-forms' ),
					],
					'floatValues'  => [
						'type'        => [ 'list_of' => 'Float' ],
						'description' => __( 'The field value(s) to filter by. Must be float values. If using this field, do not also use stringValues, intValues or boolValues.', 'wp-graphql-gravity-forms' ),
					],
					'boolValues'   => [
						'type'        => [ 'list_of' => 'Boolean' ],
						'description' => __( 'The field value(s) to filter by. Must be boolean values. If using this field, do not also use stringValues, intValues or floatValues.', 'wp-graphql-gravity-forms' ),
					],
				],
			]
		);
	}
}
