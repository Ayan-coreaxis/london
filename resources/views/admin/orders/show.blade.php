@extends('layouts.admin')
@section('title','Order '.$order->order_ref)
@section('page_title','Order Detail')

@section('styles')
<style>
.timeline { position: relative; padding-left: 28px; }
.timeline::before { content:''; position:absolute; left:9px; top:0; bottom:0; width:2px; background:#eee; }
.tl-item { position:relative; margin-bottom:18px; }
.tl-dot { position:absolute; left:-24px; top:3px; width:12px; height:12px; border-radius:50%; border:2px solid #1e3a6e; background:#fff; }
.tl-dot.active { background:#1e3a6e; }
.tl-status { font-size:13px; font-weight:700; color:#333; }
.tl-note { font-size:12px; color:#888; margin-top:2px; }
.tl-time { font-size:11px; color:#bbb; margin-top:2px; }
</style>
@endsection

@section('content')

{{-- Header --}}
<div style="display:flex;gap:10px;margin-bottom:24px;align-items:center;flex-wrap:wrap">
  <a href="{{ route('admin.orders.index') }}" class="btn-primary btn-sm" style="background:#888">
    <i class="fas fa-arrow-left"></i> Back
  </a>
  <h2 style="font-size:20px;color:#1e3a6e;font-weight:800">{{ $order->order_ref }}</h2>
  <span class="badge badge-{{ $order->status }}" style="font-size:13px;padding:5px 14px">
    {{ ucfirst(str_replace('_',' ',$order->status)) }}
  </span>
  <span style="margin-left:auto;font-size:12px;color:#aaa">
    <i class="fas fa-clock"></i> {{ date('d M Y H:i', strtotime($order->created_at)) }}
  </span>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px">

  {{-- ── LEFT COLUMN ── --}}
  <div>

    {{-- Order Items --}}
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-box" style="margin-right:6px;color:#1e3a6e"></i>Order Items</h3>
      </div>
      <table class="data-table">
        <thead>
          <tr><th>Product</th><th>Options</th><th>Artwork</th><th>Qty</th><th>Unit</th><th>Total</th></tr>
        </thead>
        <tbody>
          @foreach($items as $item)
          <tr>
            <td><strong>{{ $item->product_name }}</strong></td>
            <td style="font-size:12px;color:#888">
              @if($item->options)
                @foreach(json_decode($item->options,true) ?? [] as $k=>$v)
                  <span style="background:#f3f3f3;padding:1px 7px;border-radius:3px;margin:1px;display:inline-block">{{ $k }}: {{ $v }}</span>
                @endforeach
              @else —
              @endif
            </td>
            <td>
              @if(!empty($item->artwork_url))
                @php $ext = strtolower(pathinfo($item->artwork_url, PATHINFO_EXTENSION)); @endphp
                @if(in_array($ext, ['jpg','jpeg','png']))
                  <a href="{{ asset($item->artwork_url) }}" target="_blank">
                    <img src="{{ asset($item->artwork_url) }}" style="max-width:60px;max-height:45px;border-radius:4px;border:1px solid #e0e0e0;object-fit:cover">
                  </a>
                @else
                  <a href="{{ asset($item->artwork_url) }}" target="_blank" style="color:#059669;font-weight:700;font-size:12px">
                    <i class="fas fa-file-pdf" style="margin-right:3px"></i>View
                  </a>
                @endif
                <a href="{{ asset($item->artwork_url) }}" download style="color:#1e3a6e;font-size:11px;margin-left:6px"><i class="fas fa-download"></i></a>
              @else
                <span style="color:#f59e0b;font-size:12px"><i class="fas fa-exclamation-triangle"></i> Missing</span>
              @endif
            </td>
            <td style="font-weight:700">{{ $item->quantity }}</td>
            <td style="color:#888">£{{ number_format($item->unit_price,2) }}</td>
            <td><strong style="color:#1e3a6e">£{{ number_format($item->line_total,2) }}</strong></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- ═══ ARTWORK FILES ═══ --}}
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-palette" style="margin-right:6px;color:#1e3a6e"></i>Artwork Files ({{ count($artworkFiles ?? []) }})</h3>
      </div>
      <div style="padding:20px">
        @if(!empty($artworkFiles) && count($artworkFiles) > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-bottom:16px">
          @foreach($artworkFiles as $art)
          <div style="border:1px solid #e8e8e8;border-radius:8px;overflow:hidden;background:#fafafa">
            {{-- Preview --}}
            <div style="height:120px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;overflow:hidden">
              @if(in_array($art->file_type, ['jpg','jpeg','png']))
                <a href="{{ asset($art->file_path) }}" target="_blank"><img src="{{ asset($art->file_path) }}" style="width:100%;height:120px;object-fit:cover"></a>
              @elseif($art->file_type === 'pdf')
                <a href="{{ asset($art->file_path) }}" target="_blank" style="text-decoration:none;text-align:center">
                  <i class="fas fa-file-pdf" style="font-size:36px;color:#e53935"></i>
                  <div style="font-size:10px;color:#888;margin-top:4px">PDF</div>
                </a>
              @else
                <a href="{{ asset($art->file_path) }}" target="_blank" style="text-decoration:none;text-align:center">
                  <i class="fas fa-file-alt" style="font-size:36px;color:#1e3a6e"></i>
                  <div style="font-size:10px;color:#888;margin-top:4px">{{ strtoupper($art->file_type) }}</div>
                </a>
              @endif
            </div>
            {{-- Info --}}
            <div style="padding:10px 12px">
              <div style="font-size:12px;font-weight:700;color:#333;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $art->file_name }}">{{ $art->file_name }}</div>
              <div style="font-size:11px;color:#888;margin-top:3px">
                {{ $art->label ?? '' }}
                @if($art->product_name) · <span style="color:#1e3a6e">{{ $art->product_name }}</span>@endif
              </div>
              <div style="font-size:10px;color:#aaa;margin-top:2px">
                {{ number_format($art->file_size / 1024, 0) }} KB · {{ date('d M H:i', strtotime($art->created_at)) }}
                · <span style="color:{{ $art->uploaded_by === 'admin' ? '#6366f1' : '#059669' }}">{{ ucfirst($art->uploaded_by) }}</span>
              </div>
              <div style="display:flex;gap:6px;margin-top:8px">
                <a href="{{ route('artwork.download', $art->id) }}" class="btn-primary btn-sm" style="font-size:10px;padding:4px 8px"><i class="fas fa-download"></i> Download</a>
                <a href="{{ asset($art->file_path) }}" target="_blank" class="btn-primary btn-sm" style="font-size:10px;padding:4px 8px;background:#666"><i class="fas fa-eye"></i> View</a>
                <form method="POST" action="{{ route('artwork.delete', $art->id) }}" onsubmit="return confirm('Delete this file?')" style="display:inline">@csrf @method('DELETE')
                  <button type="submit" class="btn-primary btn-sm" style="font-size:10px;padding:4px 8px;background:#e53935"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
          <div style="text-align:center;padding:20px;color:#aaa;font-size:13px">
            <i class="fas fa-cloud-upload-alt" style="font-size:32px;display:block;margin-bottom:8px"></i>
            No artwork uploaded yet
          </div>
        @endif

        {{-- Upload Form --}}
        <div style="background:#f8f9ff;border:1px solid #e0e7ff;border-radius:8px;padding:16px;margin-top:8px">
          <div style="font-weight:700;font-size:13px;color:#1e3a6e;margin-bottom:10px"><i class="fas fa-upload"></i> Upload Artwork</div>
          @if($errors->any())
            <div style="background:#fff3f2;border:1px solid #fbc9c6;border-radius:8px;padding:10px 14px;font-size:13px;color:#c0392b;margin-bottom:16px;">
              ⚠️ <strong>Validation Error:</strong>
              <ul style="margin:4px 0 0 16px;padding:0;">
                @foreach ($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form method="POST" action="{{ route('admin.orders.artwork.upload', $order->id) }}" enctype="multipart/form-data">
            @csrf
            <div style="display:flex;gap:10px;align-items:end;flex-wrap:wrap">
              <div>
                <label style="font-size:11px;font-weight:600;color:#666;display:block;margin-bottom:4px">Files</label>
                <input type="file" name="artwork_files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.ai,.eps,.tiff" class="form-control" style="height:auto;padding:6px;font-size:12px" required>
              </div>
              <div>
                <label style="font-size:11px;font-weight:600;color:#666;display:block;margin-bottom:4px">For Item</label>
                <select name="order_item_id" class="form-control" style="height:38px;font-size:12px">
                  <option value="">— All items —</option>
                  @foreach($items as $itm)<option value="{{ $itm->id }}">{{ $itm->product_name }}</option>@endforeach
                </select>
              </div>
              <div>
                <label style="font-size:11px;font-weight:600;color:#666;display:block;margin-bottom:4px">Label</label>
                <input type="text" name="label" class="form-control" style="height:38px;font-size:12px" placeholder="e.g. Front, Back...">
              </div>
              <button type="submit" class="btn-yellow" style="height:38px;padding:0 16px;font-size:12px;white-space:nowrap"><i class="fas fa-upload"></i> Upload</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- ═══ ORDER NOTES / MESSAGES ═══ --}}
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-comments" style="margin-right:6px;color:#1e3a6e"></i>Order Notes &amp; Messages ({{ count($orderNotes ?? []) }})</h3>
      </div>
      <div style="max-height:400px;overflow-y:auto;padding:16px 20px">
        @forelse($orderNotes ?? [] as $note)
        <div style="margin-bottom:12px;padding:10px 14px;border-radius:8px;position:relative;{{ $note->author_type === 'admin' ? 'background:#f0f4ff;border-left:3px solid #1e3a6e' : 'background:#f0faf0;border-left:3px solid #22a85a' }}">
            @if($note->is_internal)<span style="position:absolute;top:6px;right:8px;font-size:9px;background:#fef3c7;color:#92400e;padding:1px 6px;border-radius:3px;font-weight:700">INTERNAL</span>@endif
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                <strong style="font-size:12px;color:{{ $note->author_type === 'admin' ? '#1e3a6e' : '#22a85a' }}">
                    {{ $note->author_type === 'admin' ? '🏢 ' : '👤 ' }}{{ $note->author_name }}
                </strong>
                <span style="font-size:10px;color:#aaa">{{ date('d M Y H:i', strtotime($note->created_at)) }}</span>
            </div>
            <div style="font-size:13px;color:#444;line-height:1.6">{{ $note->message }}</div>
        </div>
        @empty
        <div style="text-align:center;padding:20px;color:#aaa;font-size:13px">No notes yet</div>
        @endforelse
      </div>
      <div style="padding:14px 20px;border-top:1px solid #e8e8e8;background:#f9f9f9">
        <form method="POST" action="{{ route('admin.orders.note', $order->id) }}">
          @csrf
          <div style="display:flex;gap:8px;align-items:end">
            <div style="flex:1">
              <textarea name="message" rows="2" class="form-control" placeholder="Write a note or reply to customer..." required style="font-size:13px"></textarea>
            </div>
            <div style="display:flex;flex-direction:column;gap:4px">
              <label style="font-size:10px;color:#888;display:flex;align-items:center;gap:4px">
                <input type="checkbox" name="is_internal" value="1"> Internal only
              </label>
              <button type="submit" class="btn-yellow" style="height:36px;padding:0 16px;font-size:12px"><i class="fas fa-paper-plane"></i> Send</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Update Status --}}
    <div class="data-card" style="margin-bottom:20px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-edit" style="margin-right:6px;color:#1e3a6e"></i>Update Status</h3>
      </div>
      <div style="padding:20px">
        <form method="POST" action="{{ route('admin.orders.status',$order->id) }}">
          @csrf
          <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:14px;align-items:end">
            <div class="form-group" style="margin-bottom:0">
              <label>New Status</label>
              <select name="status" class="form-control">
                @foreach(['pending','confirmed','in_production','dispatched','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ $order->status===$s?'selected':'' }}>
                  {{ ucfirst(str_replace('_',' ',$s)) }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label>Note (optional)</label>
              <input type="text" name="note" class="form-control" placeholder="e.g. DHL tracking: 1Z99AA…">
            </div>
            <button type="submit" class="btn-yellow" style="white-space:nowrap">
              <i class="fas fa-save"></i> Save
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Status History Timeline --}}
    @if(count($history) > 0)
    <div class="data-card">
      <div class="data-card-hdr">
        <h3><i class="fas fa-history" style="margin-right:6px;color:#1e3a6e"></i>Status History</h3>
      </div>
      <div style="padding:20px 24px">
        <div class="timeline">
          @foreach($history as $i => $h)
          <div class="tl-item">
            <div class="tl-dot {{ $i===0 ? 'active' : '' }}"></div>
            <div class="tl-status">
              <span class="badge badge-{{ $h->status }}">{{ ucfirst(str_replace('_',' ',$h->status)) }}</span>
              @if($h->admin_name ?? null)
                <span style="font-size:11px;color:#aaa;margin-left:8px">by {{ $h->admin_name }}</span>
              @endif
            </div>
            @if($h->note)
              <div class="tl-note">{{ $h->note }}</div>
            @endif
            <div class="tl-time">{{ date('d M Y H:i',strtotime($h->created_at)) }}</div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  </div>

  {{-- ── RIGHT COLUMN ── --}}
  <div>

    {{-- Order Summary --}}
    <div class="data-card" style="margin-bottom:16px">
      <div class="data-card-hdr" style="background:#1e3a6e">
        <h3 style="color:#fff"><i class="fas fa-receipt" style="margin-right:6px"></i>Order Summary</h3>
      </div>
      <div style="padding:18px">
        <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #f5f5f5">
          <span style="color:#666">Subtotal (ex. VAT)</span><span>£{{ number_format($order->subtotal,2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #f5f5f5">
          <span style="color:#666">VAT (20%)</span><span>£{{ number_format($order->vat,2) }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px;padding:6px 0;border-bottom:1px solid #f5f5f5">
          <span style="color:#666">Delivery</span>
          <span style="{{ $order->delivery_cost > 0 ? 'color:#333' : 'color:#22a85a;font-weight:700' }}">
            {{ $order->delivery_cost > 0 ? '£'.number_format($order->delivery_cost,2) : 'FREE' }}
          </span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:17px;font-weight:800;color:#1e3a6e;padding:10px 0 0">
          <span>Total</span><span>£{{ number_format($order->total,2) }}</span>
        </div>
        <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f0f0f0">
          <div style="font-size:11px;color:#aaa;margin-bottom:4px">Payment Method</div>
          <span style="background:#f3f3f3;padding:4px 10px;border-radius:4px;font-size:12px;font-weight:600">
            {{ ucfirst($order->payment_method ?? 'Card') }}
          </span>
        </div>
      </div>
    </div>

    {{-- Customer Info --}}
    <div class="data-card" style="margin-bottom:16px">
      <div class="data-card-hdr">
        <h3><i class="fas fa-user" style="margin-right:6px;color:#1e3a6e"></i>Customer</h3>
        @if($order->user_id)
          <a href="{{ route('admin.users.show',$order->user_id) }}" class="btn-primary btn-sm">Profile</a>
        @endif
      </div>
      <div style="padding:16px;font-size:13px;line-height:1.9">
        <div style="font-weight:700;font-size:14px">{{ $order->first_name }} {{ $order->last_name }}</div>
        @if($order->company)<div style="color:#888">{{ $order->company }}</div>@endif
        <div><a href="mailto:{{ $order->email }}" style="color:#1e3a6e">{{ $order->email }}</a></div>
        <div style="color:#666">{{ $order->phone }}</div>
      </div>
    </div>

    {{-- Delivery Address --}}
    <div class="data-card">
      <div class="data-card-hdr">
        <h3><i class="fas fa-map-marker-alt" style="margin-right:6px;color:#1e3a6e"></i>Delivery Address</h3>
      </div>
      <div style="padding:16px;font-size:13px;line-height:1.9;color:#555">
        <div>{{ $order->address_line1 }}</div>
        @if($order->address_line2)<div>{{ $order->address_line2 }}</div>@endif
        <div>{{ $order->city }}, {{ $order->postcode }}</div>
        <div>{{ $order->country ?? 'United Kingdom' }}</div>
        @if($order->delivery_notes)
          <div style="margin-top:8px;padding:8px 10px;background:#fff8e6;border-radius:5px;font-size:12px;color:#888;border:1px solid #ffe099">
            <i class="fas fa-info-circle" style="color:#d08800"></i> {{ $order->delivery_notes }}
          </div>
        @endif
      </div>
    </div>

  </div>
</div>

@endsection
