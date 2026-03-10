@extends('layouts.admin')
@section('title','Footer Links')
@section('page_title','Footer Links Manager')

@section('content')
<form method="POST" action="{{ route('admin.footer-links.save') }}">
@csrf
<div class="data-card">
    <div class="data-card-hdr"><h3><i class="fas fa-link" style="color:#1e3a6e;margin-right:6px"></i>Footer Links</h3></div>
    <div style="padding:20px">
        <p style="font-size:12px;color:#888;margin-bottom:16px">Manage the links shown in the website footer. Drag rows to reorder.</p>
        <table class="data-table" id="linksTable">
            <thead><tr><th>Label</th><th>URL</th><th>Target</th><th>Active</th><th></th></tr></thead>
            <tbody>
            @foreach($links as $l)
            <tr>
                <td><input type="hidden" name="link_id[]" value="{{ $l->id }}"><input type="text" name="link_label[]" value="{{ $l->label }}" class="form-control" style="height:36px"></td>
                <td><input type="text" name="link_url[]" value="{{ $l->url }}" class="form-control" style="height:36px"></td>
                <td><select name="link_target[]" class="form-control" style="height:36px;width:100px"><option value="_self" {{ $l->target==='_self'?'selected':'' }}>Same tab</option><option value="_blank" {{ $l->target==='_blank'?'selected':'' }}>New tab</option></select></td>
                <td><input type="checkbox" name="link_active[]" value="{{ $l->id }}" {{ $l->is_active?'checked':'' }}></td>
                <td><button type="button" onclick="this.closest('tr').remove()" style="color:#e53935;background:none;border:none;cursor:pointer"><i class="fas fa-trash"></i></button></td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <button type="button" class="btn-primary btn-sm" style="margin-top:10px" onclick="addLinkRow()"><i class="fas fa-plus"></i> Add Link</button>
    </div>
</div>
<div style="margin-top:20px"><button type="submit" class="btn-yellow" style="padding:13px 36px;font-size:15px"><i class="fas fa-save"></i> Save Footer Links</button></div>
</form>
<script>
function addLinkRow(){var t=document.querySelector('#linksTable tbody');var r=document.createElement('tr');r.innerHTML='<td><input type="hidden" name="link_id[]" value="new"><input type="text" name="link_label[]" class="form-control" style="height:36px" placeholder="Link text"></td><td><input type="text" name="link_url[]" class="form-control" style="height:36px" placeholder="/page-url"></td><td><select name="link_target[]" class="form-control" style="height:36px;width:100px"><option value="_self">Same tab</option><option value="_blank">New tab</option></select></td><td><input type="checkbox" name="link_active[]" value="new" checked></td><td><button type="button" onclick="this.closest(\'tr\').remove()" style="color:#e53935;background:none;border:none;cursor:pointer"><i class="fas fa-trash"></i></button></td>';t.appendChild(r);}
</script>
@endsection
