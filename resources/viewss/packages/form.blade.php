
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($package)->name) }}" minlength="1" maxlength="255" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
    <label for="description" class="col-md-2 control-label">Description</label>
    <div class="col-md-10">
        <textarea class="form-control" name="description" cols="50" rows="10" id="description" minlength="1" maxlength="1000">{{ old('description', optional($package)->description) }}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('percent_profit') ? 'has-error' : '' }}">
    <label for="percent_profit" class="col-md-2 control-label">Percent Profit</label>
    <div class="col-md-10">
        <input class="form-control" name="percent_profit" type="text" id="percent_profit" value="{{ old('percent_profit', optional($package)->percent_profit) }}" minlength="1" placeholder="Enter percent profit here...">
        {!! $errors->first('percent_profit', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('period') ? 'has-error' : '' }}">
    <label for="period" class="col-md-2 control-label">Period</label>
    <div class="col-md-10">
        <input class="form-control" name="period" type="text" id="period" value="{{ old('period', optional($package)->period) }}" minlength="1" placeholder="Enter period here...">
        {!! $errors->first('period', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('minimum_purchase') ? 'has-error' : '' }}">
    <label for="minimum_purchase" class="col-md-2 control-label">Minimum Purchase</label>
    <div class="col-md-10">
        <input class="form-control" name="minimum_purchase" type="text" id="minimum_purchase" value="{{ old('minimum_purchase', optional($package)->minimum_purchase) }}" minlength="1" placeholder="Enter minimum purchase here...">
        {!! $errors->first('minimum_purchase', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('maximum_purchase') ? 'has-error' : '' }}">
    <label for="maximum_purchase" class="col-md-2 control-label">Maximum Purchase</label>
    <div class="col-md-10">
        <input class="form-control" name="maximum_purchase" type="text" id="maximum_purchase" value="{{ old('maximum_purchase', optional($package)->maximum_purchase) }}" minlength="1" placeholder="Enter maximum purchase here...">
        {!! $errors->first('maximum_purchase', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
    <label for="status" class="col-md-2 control-label">Status</label>
    <div class="col-md-10">
        <input class="form-control" name="status" type="text" id="status" value="{{ old('status', optional($package)->status) }}" minlength="1" placeholder="Enter status here...">
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

