<?php

namespace RefinedDigital\CMS\Modules\Users\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use RefinedDigital\CMS\Modules\Core\Traits\EditFormFieldsTrait;
use RefinedDigital\CMS\Modules\Core\Traits\ExtraFillableFieldsTrait;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use RefinedDigital\CMS\Modules\Users\Traits\UserLevel;

class User extends Authenticatable implements Sortable
{
    use Notifiable, SortableTrait, SoftDeletes, UserLevel, EditFormFieldsTrait, ExtraFillableFieldsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active', 'position', 'user_level_id', 'first_name', 'last_name', 'email', 'password',
    ];

    protected $casts = [
      'id' => 'integer',
      'active' => 'integer',
      'position' => 'integer',
      'user_level_id' => 'integer',
      'user_group_id' => 'integer',
    ];

    protected $config = 'users';

    public $sortable = [
        'order_column_name' => 'position',
        'sort_when_creating' => true,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'name',
    ];

    protected $with = [
        'groups',
    ];


    /**
     * The fields to be displayed for creating / editing
     *
     * @var array
     */
    private $formFields = [
      [
        'name' => 'User Details',
        'blocks' => [
          [
            'name' => 'Profile',
            'fields' => [
              [
                [ 'label' => 'Active', 'name' => 'active', 'required' => true, 'type' => 'select', 'options' => [1 => 'Yes', 0 => 'No'] ],
                [ 'label' => 'User Level', 'name' => 'user_level_id', 'required' => true, 'type' => 'userLevels', 'note' => 'User <strong>Admin</strong> for all administrators to edit website information<br/>Use <strong>Member</strong> for all users who login to the website' ],
                [ 'label' => 'User Group', 'name' => 'groups', 'type' => 'userGroups'],
              ],
              [
                [ 'label' => 'First Name', 'name' => 'first_name', 'required' => true ],
                [ 'label' => 'Last Name', 'name' => 'last_name', 'required' => true ],
                [ 'label' => 'Email', 'name' => 'email', 'type' => 'email', 'required' => true, 'note' => 'Used for login' ],
              ]
            ],
          ],
          [
            'name' => 'Password',
            'fields' => [
              [
                [ 'label' => 'Password', 'name' => 'password', 'type' => 'password', 'required' => true ],
                [ 'label' => 'Confirm Password', 'name' => 'password_confirmation', 'type' => 'password', 'required' => true ],
              ]
            ]
          ]
        ]
      ]
    ];

    public function groups()
    {
        return $this->belongsToMany(UserGroup::class);
    }

    public function getNameAttribute()
    {
        $n = [];
        if ($this->first_name) {
            $n[] = $this->first_name;
        }
        if ($this->last_name) {
            $n[] = $this->last_name;
        }

        return implode(' ', $n);
    }


	public function scopeActive($query)
	{
		$query->whereActive(1);
	}

	public function scopeOrder($query, $default = 'position', $direction = 'asc')
	{
        if(request()->has('sort')) {
            $sort = request()->get('sort');
        }

        if(request()->has('dir')) {
            $dir = request()->get('dir');
        }

		if(isset($sort) && isset($dir)) {
			$query->orderBy($sort, $dir);
		}

		$query->orderBy($default, $direction);
	}

	public function scopePaging($query, $perPage=20)
	{
		if(request()->has('perPage')) {
			$perPage = request()->get('perPage');

			if ($perPage == 'all') {
			    return $query->get();
			}
		}

		return $query->paginate($perPage);
	}

	public function scopeKeywords($query)
    {
        if(request()->has('keywords') && strlen(request()->get('keywords')) > 0) {
            $query
                ->where('first_name','LIKE','%'.request()->get('keywords').'%')
                ->orWhere('last_name','LIKE','%'.request()->get('keywords').'%')
                ->orWhere('email','LIKE','%'.request()->get('keywords').'%')
            ;
        }
    }
}
