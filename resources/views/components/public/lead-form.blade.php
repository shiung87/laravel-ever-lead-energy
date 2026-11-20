@props([
    'products' => collect(),
    'promotions' => collect(),
    'selectedProduct' => null,
    'selectedPromotion' => null,
    'enquiryType' => 'product_enquiry',
])

@php
    $numA = random_int(2, 9);
    $numB = random_int(1, 9);
    $states = ['Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Putrajaya', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'];
@endphp

<form method="POST" action="{{ route('enquiry.store') }}" class="el-card p-4 p-lg-5">
    @csrf
    <!-- Honeypot -->
    <div class="d-none" aria-hidden="true">
        <label for="website_url">Leave this field blank</label>
        <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control el-form-control @error('full_name') is-invalid @enderror" placeholder="e.g. Ahmad Faiz" required>
            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Mobile Number <span class="text-danger">*</span></label>
            <input type="tel" name="mobile_number" value="{{ old('mobile_number') }}" class="form-control el-form-control @error('mobile_number') is-invalid @enderror" placeholder="e.g. 012-3456789" required>
            @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Email Address (Optional)</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control el-form-control @error('email') is-invalid @enderror" placeholder="you@example.com">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Enquiry Type <span class="text-danger">*</span></label>
            <select name="enquiry_type" class="form-select el-form-select @error('enquiry_type') is-invalid @enderror" required>
                @foreach(\App\Models\CustomerLead::ENQUIRY_TYPES as $key => $label)
                    <option value="{{ $key }}" @selected(old('enquiry_type', $enquiryType) === $key)>{{ $label }}</option>
                @endforeach
            </select>
            @error('enquiry_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">State <span class="text-danger">*</span></label>
            <select name="state" class="form-select el-form-select @error('state') is-invalid @enderror" required>
                <option value="">Select State</option>
                @foreach($states as $st)
                    <option value="{{ $st }}" @selected(old('state') === $st)>{{ $st }}</option>
                @endforeach
            </select>
            @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">City <span class="text-danger">*</span></label>
            <input type="text" name="city" value="{{ old('city') }}" class="form-control el-form-control @error('city') is-invalid @enderror" placeholder="e.g. Petaling Jaya" required>
            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Vehicle Brand</label>
            <input type="text" name="vehicle_brand" value="{{ old('vehicle_brand', request('vehicle_brand')) }}" class="form-control el-form-control" placeholder="e.g. Perodua, Honda">
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Vehicle Model</label>
            <input type="text" name="vehicle_model" value="{{ old('vehicle_model', request('vehicle_model')) }}" class="form-control el-form-control" placeholder="e.g. Myvi, Civic">
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Vehicle Year</label>
            <input type="text" name="vehicle_year" value="{{ old('vehicle_year', request('vehicle_year')) }}" class="form-control el-form-control" placeholder="e.g. 2021">
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Battery Requirement / Product</label>
            @if($products->isNotEmpty())
                <select name="product_id" class="form-select el-form-select">
                    <option value="">Not sure / Recommend for my car</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" @selected(old('product_id', $selectedProduct?->id) == $prod->id)>
                            {{ $prod->name }} ({{ $prod->model_number }})
                        </option>
                    @endforeach
                </select>
            @else
                <input type="text" name="battery_requirement" value="{{ old('battery_requirement') }}" class="form-control el-form-control" placeholder="e.g. NS40ZL / DIN55">
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Preferred Service Location</label>
            <input type="text" name="preferred_service_location" value="{{ old('preferred_service_location') }}" class="form-control el-form-control" placeholder="e.g. Doorstep / Home / Office / Workshop">
        </div>

        @if($selectedPromotion)
            <input type="hidden" name="promotion_id" value="{{ $selectedPromotion->id }}">
        @elseif($promotions->isNotEmpty())
            <div class="col-12">
                <label class="form-label text-light small fw-semibold">Promotion / Campaign (Optional)</label>
                <select name="promotion_id" class="form-select el-form-select">
                    <option value="">None</option>
                    @foreach($promotions as $promo)
                        <option value="{{ $promo->id }}" @selected(old('promotion_id') == $promo->id)>{{ $promo->title }} ({{ $promo->discount_label }})</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-12">
            <label class="form-label text-light small fw-semibold">Additional Remarks</label>
            <textarea name="remarks" rows="3" class="form-control el-form-control" placeholder="Share any symptoms, urgency, or preferred installation time...">{{ old('remarks') }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label text-amber small fw-semibold">Security Check: What is {{ $numA }} + {{ $numB }}? <span class="text-danger">*</span></label>
            <input type="hidden" name="captcha_expected" value="{{ $numA + $numB }}">
            <input type="number" name="captcha_answer" class="form-control el-form-control @error('captcha_answer') is-invalid @enderror" placeholder="Enter sum" required>
            @error('captcha_answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="marketing_consent" name="marketing_consent" @checked(old('marketing_consent', true))>
                <label class="form-check-label small text-secondary" for="marketing_consent">
                    I agree to receive quotation updates, warranty reminders, and promotional offers from Everlead Energy.
                </label>
            </div>
        </div>

        <div class="col-12 pt-2">
            <button type="submit" class="btn btn-amber btn-lg w-100">
                <i class="fa-solid fa-paper-plane me-2"></i> Submit Battery Enquiry
            </button>
        </div>
    </div>
</form>
