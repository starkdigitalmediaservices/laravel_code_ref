<?php

namespace App\Http\Controllers\Affiliate;

use Auth;
use Flash;
use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Payment;
use App\Models\UserCredit;
use Illuminate\Http\Request;
use App\Traits\ByDesignAPITrait;
use App\Traits\taxJarOrderTrait;
use App\Http\Controllers\Controller;
use App\Models\UserPackageSubscription;
use App\Traits\UserPackageSubscriptionTrait;
use App\Http\Requests\Affiliate\UpdateAffiliateRequest;

class AffiliateController extends Controller
{
	use ByDesignAPITrait; use UserPackageSubscriptionTrait;
    use taxJarOrderTrait;

    /**
     * profile details of the affiliate user
     */
    public function info()
    {
        $objUser = Auth::user();
        
        $user =	[];

        if(!isset($objUser)){
            Flash::error(trans('label.invalid_record'))->important();
            return redirect()->back();
        }

       

        $objUser = $objUser->load('affiliate_meta');

        $response = $this->process_api_request('GET', 'api/user/rep/'.$objUser->affiliate_meta->user_name.'/info', [], NULL, [], true);

        $response = json_decode(json_encode($response->original), true);

        
        if($response['code'] == 200)
        {
        	$user =	$response['data']; 
            ## set the email address if api data not contains the email address
            if(!isset($user['Email']) && isset($objUser->email))
                $user['Email'] = $objUser->email;

            ## get the affiliate current rank
            $rank_response = $this->process_api_request('GET', 'api/Revolution/Dashboard/QuickSummary?repDID='.$objUser->affiliate_meta->user_name, [], NULL, [], true);

            $rank_response = $rank_response->original;
            if($rank_response['code'] == 200) {
                $user['rank_details'] = $rank_response['data'];
            }
        }

        if(isset($user['DateofBirth']))
        	$user['DateofBirth'] =	Carbon::parse($user['DateofBirth'])->format('M d Y');
        
        if(isset($user['RenewalDate']))
            $user['RenewalDate'] =  Carbon::parse($user['RenewalDate'])->format('M d Y');

        // taxjar address validation
        $user_address_trait = $this->userRoleWiseAddress();                  
        $taxJarAddressResp = $this->validateAddress([
            'country'=> $user['ShipCountry']??($user['BillCountry']??'US'),
            'state'=>$user['ShipState']??($user['BillState']??'UT'),
            'zip'=>$user['ShipPostalCode']??($user['BillPostalCode']??'84104'),
            'city'=>$user['ShipCity']??($user['BillCity']??'SALT LAKE CITY'),
            'street'=>$user['ShipStreet1']??($user['BillStreet1']??'350 E 2100 S'),
        ]);
        
        if(isset($taxJarAddressResp['status']) && $taxJarAddressResp['status'] == false){
            Flash::error($taxJarAddressResp['message'])->important();
        } 

        $mlm_details = $this->get_mlm_details();
        return view('affiliate.user.profile', compact('user','mlm_details'));
    }

    /**
     * Get MLM details from the ByDesigin API of the affiliate
     */
    public function get_mlm_details() 
    {
        $objUser = Auth::user();
        $affiliate_id = $objUser->id;

        /**
         * @todo 
         * set the API url to get the MLM details of affilate 
         */

        ## Get the seamless token of the Affiliate to load the MLM heirarchy in iframe
        if(isset($objUser->affiliate_meta->rep_did) && !empty($objUser->affiliate_meta->rep_did)){
            $token_resp = $this->process_api_request('GET', '/api/Authentication/Seamless/ForRep/'.$objUser->affiliate_meta->rep_did, [], NULL, [], true);
        }else{
            $token_resp = $this->process_api_request('GET', '/api/Authentication/Seamless/ForRep/'.$objUser->affiliate_meta->user_name, [], NULL, [], true);
        }

        //$response = collect(['original' => ['code' => 200, 'data' => '']]); //$this->process_api_request('GET', 'api/user/rep/'.$objUser->affiliate_meta->user_name.'/info', [], NULL, [], true);
        $response = $token_resp->original;
        if(isset($response) && !empty($response) && $response['code'] == 200)
        {
            $mlm_details = $response['data'];
            Session::put('seamless_auth_token', $mlm_details['Token']);
            return $mlm_details;
        } else {
            return [];
        } 
        
    }

    /**
     * Edit affiliate proifle for the email address
     */
    public function edit()
    {
        if(!Auth::check())
            return redirect('404');
        $objUser = Auth::user();
        return view('affiliate.profile.edit', compact('objUser'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $affiliate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAffiliateRequest $request, User $affiliate)
    {
        if(isset($affiliate) && empty($affiliate))
            return redirect('404');

        $arrInput = request()->all();

        try {
            $arrInput['role_id']    =   2;

            $intResponse = $affiliate->update(['name' => $arrInput['name'],'last_name' => $arrInput['last_name'],'email' => $arrInput['email'], 'profile_completed_at' => now()]);
            
            /*if($intResponse == 0)
            {
                Flash::error(trans('label.database_error'))->important();
                return redirect()->back()->withInput(request()->all());
            }*/

            Flash::success(trans('label.profile')." ".trans('label.update_success'))->important(); 

            return redirect('/affiliate/dashboard');            
        } catch (PDOException $e) {            
            Flash::error(trans('label.database_error'))->important();
            return redirect()->back()->withInput(request()->all());
        }
    }

    /**
     * profile details of the affiliate user
     */
    public function ranking()
    {
        $objUser = Auth::user();
        
        $user =	[];

        if(!isset($objUser)){
            Flash::error(trans('label.invalid_record'))->important();
            return redirect()->back();
        }

        $objUser = $objUser->load('affiliate_meta');
        ## get the affilate profile details.
        $response = $this->process_api_request('GET', 'api/Revolution/Dashboard/QuickSummary?repDID='.$objUser->affiliate_meta->user_name, [], NULL, [], true);

        $response = json_decode(json_encode($response->original), true);

        if($response['code'] == 200)
        {
        	$ranking =	$response['data']; 
        }
        
        return view('affiliate.user.ranking', compact('ranking'));
    }

    /**
     * list of all packages & subscriptions which user have selected and are active 
    */
    public function list_package_subscription(){
        
        $user = Auth::user(); 

        $package_subscription = $this->list_package_subscriptions(); 

        return view('package-subscription', compact('package_subscription','user'));
    }

    /**
     * payment details  of the affiliate user
     */
    public function get_payment_detailas($id){

        $user = Auth::user(); 
        $id = $user->id;
        if(!empty($id)){
            $if_user_exist = User::find($id);
            if(!empty($if_user_exist)){
                $payment = Payment::where('user_id',$id)->with('user_purchases_info',function($q){
                      $q->with('notifiable');
                })->get();
            //dd($payment->toArray());
            return view('affiliate.user.payment_details', compact('payment','id'));    
            }else{
                Flash::error(trans('User not found'))->important(); 
            }
        }else{
            Flash::error(trans('Id not found'))->important();
        }
    }

    /**
     * get affiliate details using API
     */
    public function get_affiliate_details() {
        $objUser = Auth::user();
        
        $user = [];

        if(!isset($objUser)){
            return 0;
        }

        $objUser = $objUser->load('affiliate_meta');

        $response = $this->process_api_request('GET', 'api/user/rep/'.$objUser->affiliate_meta->user_name.'/info', [], NULL, [], true);

        $response = $response->original;
        
        if($response['code'] == 200)
        {
            $user = $response['data']; 
            return $user;
        }
    }
}
