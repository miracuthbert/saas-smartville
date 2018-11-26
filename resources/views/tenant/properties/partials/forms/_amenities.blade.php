<div class="form-group row{{ $errors->has('amenities') ? ' has-error' : '' }}">
    <label for="amenities" class="control-label col-md-4">Amenities</label>
    <div class="col-md-6">
        @foreach($amenities as $amenity)
            <div class="form-check">
                @if(collect(old('amenities', []))->contains($amenity->id) ||
                optional($property->amenities)->firstWhere('id', $amenity->id))
                    <input class="form-check-input{{ $errors->has('amenities') ? ' is-invalid' : '' }}"
                           type="checkbox" name="amenities[]" id="amenities_{{ $amenity->id }}"
                           value="{{ $amenity->id }}" checked>
                @else
                    <input class="form-check-input{{ $errors->has('amenities') ? ' is-invalid' : '' }}"
                           type="checkbox" name="amenities[]" id="amenities_{{ $amenity->id }}"
                           value="{{ $amenity->id }}">
                @endif
                <label class="form-check-label" for="amenities_{{ $amenity->id }}">{{ $amenity->name }}</label>
            </div>
        @endforeach

        <input type="hidden" class="form-control{{ $errors->has('amenities') ? ' is-invalid' : '' }}">

        @if($errors->has('amenities'))
            <div class="invalid-feedback">
                @foreach($errors->get('amenities.*') as $message)
                    <div><strong>{{ $message }}</strong></div>
                @endforeach
            </div>
        @endif

        <small class="form-text">
            Select amenities specific to this property.
        </small>
    </div>
</div>