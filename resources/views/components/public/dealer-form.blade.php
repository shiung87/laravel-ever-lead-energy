@php
    $numA = random_int(2, 9);
    $numB = random_int(1, 9);
    $states = ['Johor', 'Kedah', 'Kelantan', 'Kuala Lumpur', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Putrajaya', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'];
    $businessTypes = ['Automotive Workshop', 'Car Accessory Shop', 'Battery Retailer', 'Tyre & Service Centre', 'Fleet / Commercial Operator', 'Automotive Entrepreneur'];
    $volumes = ['10 - 30 units / month', '31 - 50 units / month', '51 - 100 units / month', '100+ units / month'];
    $partnershipTypes = ['Authorized Battery Retailer', 'Authorized Workshop Installer', 'Regional Stockist / Distributor', 'Fleet Service Partner'];
@endphp

<form method="POST" action="{{ route('dealer.store') }}" enctype="multipart/form-data" class="el-card p-4 p-lg-5">
    @csrf
    <div class="d-none" aria-hidden="true">
        <input type="text" name="website_url" tabindex="-1" autocomplete="off">
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Business Name <span class="text-danger">*</span></label>
            <input type="text" name="business_name" value="{{ old('business_name') }}" class="form-control el-form-control @error('business_name') is-invalid @enderror" required>
            @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Business Registration No. (SSM) <span class="text-danger">*</span></label>
            <input type="text" name="registration_number" value="{{ old('registration_number') }}" class="form-control el-form-control @error('registration_number') is-invalid @enderror" required>
            @error('registration_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Contact Person <span class="text-danger">*</span></label>
            <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="form-control el-form-control @error('contact_person') is-invalid @enderror" required>
            @error('contact_person')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Mobile Number <span class="text-danger">*</span></label>
            <input type="tel" name="mobile_number" value="{{ old('mobile_number') }}" class="form-control el-form-control @error('mobile_number') is-invalid @enderror" required>
            @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control el-form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label text-light small fw-semibold">Business Address <span class="text-danger">*</span></label>
            <textarea name="business_address" rows="2" class="form-control el-form-control @error('business_address') is-invalid @enderror" required>{{ old('business_address') }}</textarea>
            @error('business_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
            <input type="text" name="city" value="{{ old('city') }}" class="form-control el-form-control @error('city') is-invalid @enderror" required>
            @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Business Type <span class="text-danger">*</span></label>
            <select name="business_type" class="form-select el-form-select" required>
                @foreach($businessTypes as $bt)
                    <option value="{{ $bt }}" @selected(old('business_type') === $bt)>{{ $bt }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Years in Operation <span class="text-danger">*</span></label>
            <input type="number" name="years_in_operation" value="{{ old('years_in_operation', 1) }}" min="0" max="100" class="form-control el-form-control" required>
        </div>

        <div class="col-md-4">
            <label class="form-label text-light small fw-semibold">Number of Outlets <span class="text-danger">*</span></label>
            <input type="number" name="number_of_outlets" value="{{ old('number_of_outlets', 1) }}" min="1" max="500" class="form-control el-form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Estimated Monthly Sales Volume <span class="text-danger">*</span></label>
            <select name="estimated_monthly_volume" class="form-select el-form-select" required>
                @foreach($volumes as $v)
                    <option value="{{ $v }}" @selected(old('estimated_monthly_volume') === $v)>{{ $v }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label text-light small fw-semibold">Interested Partnership Type <span class="text-danger">*</span></label>
            <select name="partnership_type" class="form-select el-form-select" required>
                @foreach($partnershipTypes as $pt)
                    <option value="{{ $pt }}" @selected(old('partnership_type') === $pt)>{{ $pt }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <label class="form-label text-light small fw-semibold">Existing Battery Brands Carried</label>
            <input type="text" name="existing_brands" value="{{ old('existing_brands') }}" class="form-control el-form-control" placeholder="e.g. Amaron, Century, Varta, or None">
        </div>

        <div class="col-12">
            <label class="form-label text-light small fw-semibold">Upload Business Registration Documents (SSM / License - PDF/JPG/PNG, max 5MB each)</label>
            <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="form-control el-form-control @error('documents.*') is-invalid @enderror">
            @error('documents.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label text-light small fw-semibold">Additional Remarks</label>
            <textarea name="remarks" rows="3" class="form-control el-form-control" placeholder="Tell us about your workshop/retail setup or territory goals...">{{ old('remarks') }}</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label text-amber small fw-semibold">Security Check: What is {{ $numA }} + {{ $numB }}? <span class="text-danger">*</span></label>
            <input type="hidden" name="captcha_expected" value="{{ $numA + $numB }}">
            <input type="number" name="captcha_answer" class="form-control el-form-control @error('captcha_answer') is-invalid @enderror" required>
            @error('captcha_answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input @error('consent') is-invalid @enderror" type="checkbox" value="1" id="dealer_consent" name="consent" required>
                <label class="form-check-label small text-secondary" for="dealer_consent">
                    I confirm that the business information provided is accurate and consent to Everlead Energy reviewing my dealer application. <span class="text-danger">*</span>
                </label>
                @error('consent')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-12 pt-2">
            <button type="submit" class="btn btn-amber btn-lg w-100">
                <i class="fa-solid fa-handshake me-2"></i> Submit Dealer Application
            </button>
        </div>
    </div>
</form>
