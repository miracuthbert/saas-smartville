<div class="form-group row{{ $errors->has('category_id') ? ' has-error' : '' }}">
    <label for="category_id" class="control-label col-md-4">Category</label>
    <div class="col-md-6">
        <select name="category_id" id="category_id"
                class="form-control custom-select{{ $errors->has('category_id') ? ' is-invalid' : '' }}">
            <option value="">--- Select a category ---</option>
            @foreach($property_categories as $cat)
                @if(old('category_id') == $cat->id)
                    <option value="{{ $cat->id }}" selected>{{ $cat->name }}</option>
                @elseif(isset($category_id) && $category_id == $cat->id)
                    <option value="{{ $cat->id }}" selected>{{ $cat->name }}</option>
                @else
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endif
            @endforeach
        </select>

        @if($errors->has('category_id'))
            <div class="invalid-feedback">
                <strong>{{ $errors->first('category_id') }}</strong>
            </div>
        @endif
    </div>
</div>
