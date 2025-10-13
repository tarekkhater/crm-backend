<div class="input-group">
   <span class="input-group-btn">
     <a  data-input="{{ $id }}" data-preview="{{ $id }}_holder" class="btn btn-primary lfm">
       <i class="fas fa-picture"></i> Choose Image
     </a>
   </span>
    @if (isset($image))
        <input id="{{ $id }}" class="form-control" type="hidden" value="{{ $image }}" name="{{ $field }}">
        <input disabled class="form-control" value="{{ $image }}" type="text">
    @else
        <input id="{{ $id }}" class="form-control" type="hidden" name="{{ $field }}">
        <input disabled class="form-control" value="" type="text">
    @endif
</div>
<div id="{{ $id }}_holder" style="margin-top:15px; margin-bottom:20px;max-height:200px;"></div>

