@extends('layouts.admin')
@section('title','Delivery Management')
@section('page_title','Delivery Zones & Methods')

@section('content')
<form method="POST" action="{{ route('admin.delivery.save') }}">
@csrf

{{-- DELIVERY ZONES --}}
<div class="data-card" style="margin-bottom:24px">
    <div class="data-card-hdr"><h3><i class="fas fa-globe" style="color:#1e3a6e;margin-right:6px"></i>Delivery Zones</h3></div>
    <div style="padding:20px">
        <p style="font-size:12px;color:#888;margin-bottom:16px">Define regions by postcode prefixes (comma-separated). Leave empty for all postcodes.</p>
        <table class="data-table" id="zonesTable">
            <thead><tr><th>Zone Name</th><th>Postcode Prefixes</th><th>Active</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach($zones as $z)
            <tr>
                <td><input type="hidden" name="zone_id[]" value="{{ $z->id }}"><input type="text" name="zone_name[]" value="{{ $z->name }}" class="form-control" style="height:36px"></td>
                <td><input type="text" name="zone_postcodes[]" value="{{ $z->postcodes }}" class="form-control" style="height:36px" placeholder="EC,WC,E,W,N..."></td>
                <td><input type="checkbox" name="zone_active[]" value="{{ $z->id }}" {{ $z->is_active ? 'checked' : '' }}></td>
                <td><a href="#" onclick="event.preventDefault();if(confirm('Delete zone?'))document.getElementById('delZone{{ $z->id }}').submit()" style="color:#e53935;font-size:12px"><i class="fas fa-trash"></i></a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <button type="button" class="btn-primary btn-sm" style="margin-top:10px" onclick="addZoneRow()"><i class="fas fa-plus"></i> Add Zone</button>
    </div>
</div>

{{-- DELIVERY METHODS --}}
<div class="data-card" style="margin-bottom:24px">
    <div class="data-card-hdr"><h3><i class="fas fa-truck" style="color:#1e3a6e;margin-right:6px"></i>Delivery Methods</h3></div>
    <div style="padding:20px">
        <table class="data-table" id="methodsTable">
            <thead><tr><th>Name</th><th>Slug</th><th>Description</th><th>Price (£)</th><th>Est. Days</th><th>Zone</th><th>Active</th><th></th></tr></thead>
            <tbody>
            @foreach($methods as $m)
            <tr>
                <td><input type="hidden" name="method_id[]" value="{{ $m->id }}"><input type="text" name="method_name[]" value="{{ $m->name }}" class="form-control" style="height:36px"></td>
                <td><input type="text" name="method_slug[]" value="{{ $m->slug }}" class="form-control" style="height:36px;width:120px"></td>
                <td><input type="text" name="method_description[]" value="{{ $m->description }}" class="form-control" style="height:36px"></td>
                <td><input type="number" step="0.01" name="method_price[]" value="{{ $m->price }}" class="form-control" style="height:36px;width:80px"></td>
                <td><input type="text" name="method_days[]" value="{{ $m->estimated_days }}" class="form-control" style="height:36px;width:110px"></td>
                <td><select name="method_zone_id[]" class="form-control" style="height:36px;width:130px"><option value="">All Zones</option>@foreach($zones as $z)<option value="{{ $z->id }}" {{ $m->zone_id==$z->id?'selected':'' }}>{{ $z->name }}</option>@endforeach</select></td>
                <td><input type="checkbox" name="method_active[]" value="{{ $m->id }}" {{ $m->is_active ? 'checked' : '' }}></td>
                <td><a href="#" onclick="event.preventDefault();if(confirm('Delete?'))document.getElementById('delMethod{{ $m->id }}').submit()" style="color:#e53935;font-size:12px"><i class="fas fa-trash"></i></a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <button type="button" class="btn-primary btn-sm" style="margin-top:10px" onclick="addMethodRow()"><i class="fas fa-plus"></i> Add Method</button>
    </div>
</div>

<button type="submit" class="btn-yellow" style="padding:13px 36px;font-size:15px"><i class="fas fa-save"></i> Save All Delivery Settings</button>
</form>

@foreach($zones as $z)
<form id="delZone{{ $z->id }}" method="POST" action="{{ route('admin.delivery.zone.delete', $z->id) }}" style="display:none">@csrf @method('DELETE')</form>
@endforeach
@foreach($methods as $m)
<form id="delMethod{{ $m->id }}" method="POST" action="{{ route('admin.delivery.method.delete', $m->id) }}" style="display:none">@csrf @method('DELETE')</form>
@endforeach

<script>
function addZoneRow(){var t=document.querySelector('#zonesTable tbody');var r=document.createElement('tr');r.innerHTML='<td><input type="hidden" name="zone_id[]" value="new"><input type="text" name="zone_name[]" class="form-control" style="height:36px" placeholder="Zone name"></td><td><input type="text" name="zone_postcodes[]" class="form-control" style="height:36px" placeholder="EC,WC,E..."></td><td><input type="checkbox" name="zone_active[]" value="new" checked></td><td></td>';t.appendChild(r);}
function addMethodRow(){var t=document.querySelector('#methodsTable tbody');var r=document.createElement('tr');r.innerHTML='<td><input type="hidden" name="method_id[]" value="new"><input type="text" name="method_name[]" class="form-control" style="height:36px"></td><td><input type="text" name="method_slug[]" class="form-control" style="height:36px;width:120px"></td><td><input type="text" name="method_description[]" class="form-control" style="height:36px"></td><td><input type="number" step="0.01" name="method_price[]" value="0" class="form-control" style="height:36px;width:80px"></td><td><input type="text" name="method_days[]" class="form-control" style="height:36px;width:110px"></td><td><select name="method_zone_id[]" class="form-control" style="height:36px;width:130px"><option value="">All</option>@foreach($zones as $z)<option value="{{ $z->id }}">{{ $z->name }}</option>@endforeach</select></td><td><input type="checkbox" name="method_active[]" value="new" checked></td><td></td>';t.appendChild(r);}
</script>
@endsection
