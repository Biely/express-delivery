<form>
  <fieldset disabled>
    <div class="form-group">
      <label for="disabledTextInput">用户名</label>
      <input type="text" id="disabledTextInput" class="form-control" value="{{ Auth::user()->name }}">
    </div>
    <div class="form-group">
      <label for="disabledTextInput">邮箱</label>
      <input type="text" id="disabledTextInput" class="form-control" value="{{ Auth::user()->email }}">
    </div>
    <div class="form-group">
      <label for="disabledTextInput">QQ</label>
      <input type="text" id="disabledTextInput" class="form-control" value="{{ Auth::user()->qq }}">
    </div>
    <div class="form-group">
      <label for="disabledTextInput">网点</label>
      <input type="text" id="disabledTextInput" class="form-control" value="{{ Auth::user()->store }}">
    </div>
    <div class="form-group">
      <label for="disabledTextInput">快递商家</label>
      <input type="text" id="disabledTextInput" class="form-control" value="{{ Auth::user()->etype }}">
    </div>
  </fieldset>
  <a href="{{ route('person.edit',Auth::user()->id) }}" class="btn btn-primary">修改资料</a>
</form>
