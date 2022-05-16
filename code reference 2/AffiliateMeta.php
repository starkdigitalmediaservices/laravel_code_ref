<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Concerns\AffiliateMetaAccessors;

class AffiliateMeta extends Model
{
    use HasFactory,AffiliateMetaAccessors;

    protected $table = 'affiliate_meta';

    protected $fillable = [ 'user_id', 'phone_no', 'user_name', 'rep_did','address1','address2', 'status', 'ssn_ein','city', 'state_id', 'country_id', 'zipcode', 'access_token', 'token_type', 'client_id', 'rank', 'terms_and_conditions', 'issued', 'expires', 'refresh_token', 'response', 'rank_id', 'profile_image'];

    protected $appends = [
       'status_str', 'status_action'
    ];
  	
  	/**
     * User relationship
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * Relationship with ranks model
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fn_rank()
    {
        return $this->belongsTo('App\Models\Rank', 'rank_id');
    }

        //Reation with state
    public function states()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }

    //Reation with country
    public function countries()
    {
        return $this->belongsTo('App\Models\Country','country_id');
    }

    //Reation with city
    public function cities()
    {
        return $this->belongsTo('App\Models\City','city');
    }
}