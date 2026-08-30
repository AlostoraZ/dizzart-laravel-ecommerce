<div class="mb-3">
    <label class="form-label">Code</label>
    <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $promo->code ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Discount Percentage</label>
    <input type="number" min="1" max="100" name="discount_percentage" class="form-control" value="{{ old('discount_percentage', $promo->discount_percentage ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Max Uses</label>
    <input type="number" min="1" name="max_uses" class="form-control" value="{{ old('max_uses', $promo->max_uses ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Expires At (optional)</label>
    <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', isset($promo->expires_at) ? $promo->expires_at->format('Y-m-d\TH:i') : '') }}">
</div>
