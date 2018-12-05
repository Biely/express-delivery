<form method="POST" action="{{ route('person.update',Auth::user()->id) }}">
    @csrf

    <div class="form-group row">
        <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-10">
            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name',Auth::user()->name) }}" required autofocus>

            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="qq" class="col-md-2 col-form-label text-md-right">{{ __('QQ') }}</label>

        <div class="col-md-10">
            <input id="qq" type="text" class="form-control{{ $errors->has('qq') ? ' is-invalid' : '' }}" name="qq" value="{{ old('qq',Auth::user()->qq) }}" required>

            @if ($errors->has('qq'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('qq') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="store" class="col-md-2 col-form-label text-md-right">{{ __('快递网点') }}</label>

        <div class="col-md-10">
            <input id="store" type="text" class="form-control{{ $errors->has('store') ? ' is-invalid' : '' }}" name="store" value="{{ old('store',Auth::user()->store) }}" required>

            @if ($errors->has('store'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('store') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="etype" class="col-md-2 col-form-label text-md-right">{{ __('快递商家') }}</label>

        <div class="col-md-10">
          <select id="etype" class="form-control{{ $errors->has('etype') ? ' is-invalid' : '' }}" name="etype">
            <option value="中通快递" {{ old('etype',Auth::user()->etype) == "中通快递" ? 'selected' : '' }}>中通快递</option>
            <option value="圆通快递" {{ old('etype',Auth::user()->etype) == "圆通快递" ? 'selected' : '' }}>圆通快递</option>
            <option value="韵达快递" {{ old('etype',Auth::user()->etype) == "韵达快递" ? 'selected' : '' }}>韵达快递</option>
          </select>
            @if ($errors->has('etype'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('etype') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-10 offset-md-2">
            <button type="submit" class="btn btn-primary">
                {{ __('提交') }}
            </button>
        </div>
    </div>
</form>
