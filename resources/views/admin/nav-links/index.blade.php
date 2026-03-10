@extends('layouts.admin')
@section('title','Navigation Links')
@section('page_title','Navigation Links Manager')

@section('content')
<form method="POST" action="{{ route('admin.nav-links.save') }}">
@csrf
<div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-bars" style="color:#1e3a6e;margin-right:6px"></i>Header Navigation Links</h3></div>
    <div style="padding:20px">
        <p style="font-size:12px;color:#888;margin-bottom:16px">These links appear in the main navigation bar and mobile menu.</p>
        <table class="data-table" id="navTable">
            <thead><tr><th>Label</th><th>URL</th><th>Active</th><th></th></tr></thead>
            <tbody>
            @foreach($links as $l)
            <tr>
                <td><input type="hidden" name="nav_id[]" value="{{ $l->id }}"><input type="text" name="nav_label[]" value="{{ $l->label }}" class="form-control" style="height:36px"></td>
                <td><input type="text" name="nav_url[]" value="{{ $l->url }}" class="form-control" style="height:36px"></td>
                <td><input type="checkbox" name="nav_active[]" value="{{ $l->id }}" {{ $l->is_active?'checked':'' }}></td>
                <td><button type="button" onclick="this.closest('tr').remove()" style="color:#e53935;background:none;border:none;cursor:pointer"><i class="fas fa-trash"></i></button></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <button type="button" class="btn-primary btn-sm" style="margin-top:10px" onclick="addNavRow()"><i class="fas fa-plus"></i> Add Link</button>
    </div>
</div>
<div style="margin-top:20px"><button type="submit" class="btn-yellow" style="padding:13px 36px;font-size:15px"><i class="fas fa-save"></i> Save Navigation</button></div>
</form>
<script>
function addNavRow(){var t=document.querySelector('#navTable tbody');var r=document.createElement('tr');r.innerHTML='<td><input type="hidden" name="nav_id[]" value="new"><input type="text" name="nav_label[]" class="form-control" style="height:36px" placeholder="Link label"></td><td><input type="text" name="nav_url[]" class="form-control" style="height:36px" placeholder="/page-url"></td><td><input type="checkbox" name="nav_active[]" value="new" checked></td><td><button type="button" onclick="this.closest(\'tr\').remove()" style="color:#e53935;background:none;border:none;cursor:pointer"><i class="fas fa-trash"></i></button></td>';t.appendChild(r);}
</script>
@endsection
