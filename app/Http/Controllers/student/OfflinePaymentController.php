<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\FileUploader;
use App\Models\OfflinePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OfflinePaymentController extends Controller
{
    public function store(Request $request)
    {
        // check amount
        $payment_details = Session::get('payment_details');
        if ($request->amount != $payment_details['payable_amount']) {
            Session::flash('error', get_phrase('Invalid amount.'));
            return redirect()->back();
        }

        $rules = [
            'phone_no' => 'required_without:bank_no',
            'bank_no'  => 'required_without:phone_no',
            'doc'      => 'required|mimes:jpeg,jpg,pdf,txt,png,docx,doc|max:3072',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $items     = array_column($payment_details['items'], 'id');
        $itemsJson = json_encode($items);
        if ($request->item_type == 'bootcamp') {
            $item = $items[0];
        }

        $file      = $request->doc;
        $file_name = Str::random(20) . '.' . $file->extension();
        $path      = 'uploads/offline_payment/' . Str::slug(auth()->user()->name) . '/' . $file_name;
        FileUploader::upload($file, $path, null, null, 300);

        $offline_payment['user_id']      = auth()->user()->id;
        $offline_payment['item_type']    = $request->item_type;
        $offline_payment['items']        = isset($item) ? $item : $items;
        $offline_payment['tax']          = $payment_details['tax'];
        $offline_payment['total_amount'] = $request->amount;
        $offline_payment['phone_no']     = $request->phone_no;
        $offline_payment['bank_no']      = $request->bank_no;
        $offline_payment['doc']          = $path;
        $offline_payment['coupon']       = $request->coupon;

        // insert offline payment history
        OfflinePayment::insert($offline_payment);

        // remove from cart
        if ($request->item_type == 'course') {
            $url = 'courses';
            CartItem::whereIn('course_id', $items)->where('user_id', auth()->user()->id)->delete();
        } elseif ($request->item_type == 'bootcamp') {
            $url = 'bootcamps';
        } elseif ($request->item_type == 'package') {
            $url = 'team.packages';
        }

        // return to courses
        Session::flash('success', get_phrase('Your request is in process.'));
        return redirect()->route($url);
    }
}
