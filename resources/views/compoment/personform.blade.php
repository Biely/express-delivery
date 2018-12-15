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
        <label for="tel" class="col-md-4 col-form-label text-md-right">{{ __('手机号') }}</label>

        <div class="col-md-10">
            <input id="tel" type="text" class="form-control{{ $errors->has('tel') ? ' is-invalid' : '' }}" name="tel" value="{{ old('tel') }}" required>

            @if ($errors->has('tel'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('tel') }}</strong>
                </span>
            @endif
        </div>
    </div>
    
    <div class="form-group row">
        <label for="etype" class="col-md-2 col-form-label text-md-right">{{ __('快递公司') }}</label>

        <div class="col-md-10">
          <select id="etype" class="form-control{{ $errors->has('etype') ? ' is-invalid' : '' }}" name="etype">
            @foreach (elists() as $elist)
                <option value="{{ $elist->name }}">{{ $elist->name }}</option>
            @endforeach
          </select>
          
            @if ($errors->has('etype'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('etype') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row">
        <label for="store" class="col-md-2 col-form-label text-md-right">{{ __('快递网点') }}</label>

        <div class="col-md-10">
            <select id="store" class="form-control{{ $errors->has('store') ? ' is-invalid' : '' }}" name="store">
                @foreach (wdlist() as $store)
                    <option value="{{ $store->name }}">{{ $store->name }}</option>
                @endforeach
              </select>
                @if ($errors->has('store'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('store') }}</strong>
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
