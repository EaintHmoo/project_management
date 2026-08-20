<?php

namespace App\Filters;

use App\Enums\UserStatus;

class UserFilter extends Filters
{
	/**
	 * Register filter properties
	 */
	protected $filters = [
		'keyword',
		'role_id',
		'active'
	];

	/**
	 * Filter by phone_no.
	 */
	public function keyword($value)
	{
		return $this->builder->where(function ($query) use ($value) {
			$query->where('name', 'LIKE', "%{$value}%")
				->orWhere('email', 'LIKE', "%{$value}%")
				->orWhere('phone_no', 'LIKE', "%{$value}%");
		});
	}

	/**
	 * Filter by role
	 */
	public function role_id($value)
	{
		return $this->builder->where('role_id', $value);
	}

	/**
	 * Filter by active status
	 */
	public function active($value)
	{
		if ($value == "all") {
			return $this->builder;
		}

		return $this->builder->where('active', $value);
	}
}