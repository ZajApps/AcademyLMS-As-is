@php $amount = $payment_details['payable_amount']; @endphp

<form action="{{ route('payment.offline.store') }}" method="post" enctype="multipart/form-data">@csrf
    <input type="hidden" name="item_type" value="{{ $payment_details['custom_field']['item_type'] }}">
    <input type="hidden" name="coupon" value="{{ $payment_details['coupon'] ?? null }}">

    <div class="mb-3">
        <label for="amount" class="form-label">{{ get_phrase('Payable amount') }}</label>
        <input type="text" min="{{ $amount }}" max="{{ $amount }}" name="amount" id="amount" class="form-control" value="{{ $amount }}" />
    </div>

    <div class="mb-3">
        <label for="phone_no" class="form-label">{{ get_phrase('Phone number') }}</label>
        <input type="tel" name="phone_no" id="phone_no" class="form-control" />
    </div>

    <div class="mb-3">
        <label for="bank_no" class="form-label">{{ get_phrase('Bank info') }}</label>
        <input type="text" name="bank_no" id="bank_no" class="form-control" />
    </div>

    <div class="mb-3">
        <label for="" class="form-label d-flex justify-content-between">
            <span>{{ get_phrase('Payment Document') }}</span>
            <span>{{ get_phrase('(jpg, pdf, txt, png, docx)') }}</span>
        </label>
        <input type="file" name="doc" class="form-control" required>
    </div>

    <input type="submit" class="btn btn-primary" value="{{ get_phrase('Pay offline') }}">
</form>
