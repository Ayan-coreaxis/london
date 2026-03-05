@extends('layouts.app')

@section('title', 'Contact Us – London InstantPrint')
@section('meta_description', 'Get in touch with London InstantPrint. We\'re here to help with all your printing needs.')

@section('styles')
<style>
.contact-hero {
    background: linear-gradient(135deg, #1e3a6e 0%, #2a5298 100%);
    padding: 60px 24px;
    text-align: center;
    color: #fff;
}
.contact-hero h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: 38px; font-weight: 900; margin: 0 0 12px;
}
.contact-hero p { font-size: 16px; opacity: 0.85; margin: 0; }

.contact-wrap {
    max-width: 1100px; margin: 0 auto;
    padding: 60px 24px 80px;
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    gap: 48px;
    align-items: start;
}
@media(max-width: 768px){ .contact-wrap { grid-template-columns: 1fr; } }

/* Info side */
.contact-info h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px; font-weight: 800; color: #1e3a6e; margin: 0 0 20px;
}
.contact-item {
    display: flex; align-items: flex-start; gap: 14px;
    margin-bottom: 22px;
}
.contact-item .icon {
    width: 44px; height: 44px; background: #f0f4ff;
    border-radius: 10px; display: flex; align-items: center;
    justify-content: center; font-size: 20px; flex-shrink: 0;
}
.contact-item h4 {
    font-family: 'Montserrat', sans-serif; font-size: 13px;
    font-weight: 700; color: #1e3a6e; margin: 0 0 4px;
}
.contact-item p { font-size: 13px; color: #555; margin: 0; line-height: 1.6; }
.contact-item a { color: #1e3a6e; text-decoration: none; }
.contact-item a:hover { text-decoration: underline; }

.hours-box {
    background: #f7f9ff; border-radius: 10px; padding: 18px 20px;
    margin-top: 10px;
}
.hours-box h4 {
    font-family: 'Montserrat', sans-serif; font-size: 13px;
    font-weight: 700; color: #1e3a6e; margin: 0 0 12px;
}
.hours-row {
    display: flex; justify-content: space-between;
    font-size: 13px; color: #555; padding: 5px 0;
    border-bottom: 1px solid #eee;
}
.hours-row:last-child { border-bottom: none; }

/* Form side */
.contact-form-card {
    background: #fff; border: 1px solid #e8e8e8;
    border-radius: 12px; padding: 32px 36px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.05);
}
.contact-form-card h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px; font-weight: 800; color: #1e3a6e; margin: 0 0 24px;
}
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 14px; }
@media(max-width:500px){ .form-row { grid-template-columns: 1fr; } }
.form-group label {
    display: block; font-size: 12px; font-weight: 700; color: #555;
    margin-bottom: 5px; font-family: 'Montserrat', sans-serif; text-transform: uppercase;
}
.form-group input,
.form-group textarea,
.form-group select {
    width: 100%; padding: 11px 14px; border: 1.5px solid #ddd;
    border-radius: 7px; font-size: 14px; color: #333; outline: none;
    box-sizing: border-box; transition: border-color 0.2s;
    font-family: 'Open Sans', sans-serif;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus { border-color: #1e3a6e; }
.form-group textarea { resize: vertical; min-height: 120px; }
.btn-send {
    width: 100%; padding: 14px; background: #e8352a; color: #fff; border: none;
    border-radius: 7px; font-family: 'Montserrat', sans-serif; font-size: 14px;
    font-weight: 700; cursor: pointer; transition: background 0.2s; margin-top: 6px;
}
.btn-send:hover { background: #c42d24; }
.alert-success {
    background: #f0faf0; border: 1px solid #99dd99; border-radius: 8px;
    padding: 14px 18px; font-size: 14px; color: #2a7a2a; margin-bottom: 20px;
}
.alert-error {
    background: #fff3f2; border: 1px solid #fbc9c6; border-radius: 8px;
    padding: 12px 16px; font-size: 13px; color: #c0392b; margin-bottom: 20px;
}
</style>
@endsection

@section('content')

<div class="contact-hero">
    <h1>Get In Touch</h1>
    <p>We'd love to hear from you. Our team is always ready to help.</p>
</div>

<div class="contact-wrap">
    {{-- Info --}}
    <div class="contact-info">
        <h2>Contact Information</h2>

        <div class="contact-item">
            <div class="icon">📍</div>
            <div>
                <h4>Our Address</h4>
                <p>London InstantPrint<br>123 Print Street<br>London, EC1A 1BB</p>
            </div>
        </div>

        <div class="contact-item">
            <div class="icon">📞</div>
            <div>
                <h4>Phone</h4>
                <p><a href="tel:+442071234567">+44 (0) 207 123 4567</a></p>
            </div>
        </div>

        <div class="contact-item">
            <div class="icon">✉️</div>
            <div>
                <h4>Email</h4>
                <p><a href="mailto:info@londoninstantprint.co.uk">info@londoninstantprint.co.uk</a></p>
            </div>
        </div>

        <div class="hours-box">
            <h4>🕐 Opening Hours</h4>
            <div class="hours-row"><span>Monday – Friday</span><span>8:00am – 6:00pm</span></div>
            <div class="hours-row"><span>Saturday</span><span>9:00am – 2:00pm</span></div>
            <div class="hours-row"><span>Sunday</span><span>Closed</span></div>
        </div>
    </div>

    {{-- Form --}}
    <div class="contact-form-card">
        <h2>Send Us a Message</h2>

        @if(session('success'))
            <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('contact.send') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Your Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="John Smith" required>
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Phone (Optional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+44 7700 000000">
                </div>
                <div class="form-group">
                    <label>Subject *</label>
                    <select name="subject" required>
                        <option value="">— Select a subject —</option>
                        <option value="General Enquiry" {{ old('subject')=='General Enquiry' ? 'selected' : '' }}>General Enquiry</option>
                        <option value="Order Help" {{ old('subject')=='Order Help' ? 'selected' : '' }}>Order Help</option>
                        <option value="Artwork / Design" {{ old('subject')=='Artwork / Design' ? 'selected' : '' }}>Artwork / Design</option>
                        <option value="Delivery Issue" {{ old('subject')=='Delivery Issue' ? 'selected' : '' }}>Delivery Issue</option>
                        <option value="Pricing / Quote" {{ old('subject')=='Pricing / Quote' ? 'selected' : '' }}>Pricing / Quote</option>
                        <option value="Complaint" {{ old('subject')=='Complaint' ? 'selected' : '' }}>Complaint</option>
                        <option value="Other" {{ old('subject')=='Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px;">
                <label>Message *</label>
                <textarea name="message" placeholder="Tell us how we can help you..." required>{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn-send">Send Message ✉️</button>
        </form>
    </div>
</div>

@endsection
