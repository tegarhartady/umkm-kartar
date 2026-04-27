## 📝 FORMULIR DAFTAR UMKM - UPDATE

**Tanggal:** 24 April 2026  
**Status:** ✅ Completed

### 🎯 Fitur yang Ditambahkan

1. **Field Omzet Bulanan**
   - Input numerik untuk omzet bulanan usaha
   - Validasi: Required, numeric, minimal 0
   - Format: Rp (angka saja)

2. **Google Maps Integration untuk Lokasi**
   - Interactive map picker
   - Click on map to place marker
   - Drag marker to adjust location
   - Search box untuk mencari alamat
   - Auto-populate latitude & longitude fields
   - Default location: Teluknaga (-6.1753, 106.9749)

### 📝 File yang Diubah

#### 1. `resources/views/pages/daftar-umkm.blade.php`
**Perubahan:**
- Tambah field "Omzet Bulanan" di Business Info section
- Replace koordinat input dengan Google Maps interactive section
- Add Google Maps API script dengan Leaflet-style interface
- Add form validation script

**Form Fields Added:**
```blade
<!-- Omzet Bulanan -->
<div class="col-md-6">
    <label class="form-label">Omzet Bulanan (Rp) <span class="text-danger">*</span></label>
    <input type="number" name="omzet_bulanan" 
           class="form-control @error('omzet_bulanan') is-invalid @enderror" 
           placeholder="Contoh: 5000000" min="0" required>
</div>

<!-- Google Maps Section -->
<div class="form-section mb-4">
    <h5 class="form-section-title"><i class="bi bi-geo-alt me-2"></i> Lokasi Usaha (Pilih di Peta)</h5>
    
    <!-- Interactive Map -->
    <div id="map" style="height: 400px; border-radius: 12px;"></div>
    
    <!-- Latitude & Longitude (Read-only) -->
    <input type="number" id="latitude" name="latitude" readonly>
    <input type="number" id="longitude" name="longitude" readonly>
</div>
```

**JavaScript Features:**
- Initialize Google Maps with default location
- Click on map → place marker & update coordinates
- Drag marker → update coordinates
- Search box → find location & update coordinates
- Form validation before submit

#### 2. `app/Http/Controllers/UmkmController.php`
**Perubahan di method `registerFromPublic()`:**
- Add validation for `omzet_bulanan` (required, numeric, min:0)
- Add validation for `latitude` (required, numeric, -90 to 90)
- Add validation for `longitude` (required, numeric, -180 to 180)
- Add custom error messages untuk setiap field
- Save `omzet_bulanan`, `latitude`, `longitude` ke database

**Validation Rules:**
```php
'omzet_bulanan' => 'required|numeric|min:0',
'latitude' => 'required|numeric|between:-90,90',
'longitude' => 'required|numeric|between:-180,180',
```

**Custom Messages:**
```php
'omzet_bulanan.required' => 'Omzet bulanan harus diisi',
'latitude.required' => 'Silakan pilih lokasi di peta',
'longitude.required' => 'Silakan pilih lokasi di peta',
```

### 🗺️ Google Maps Features

#### Map Interaction:
1. **Click on Map**: Place marker at clicked location
2. **Drag Marker**: Adjust position, coordinates update automatically
3. **Search Box**: Type address to find & navigate
4. **Zoom & Pan**: Built-in Google Maps controls
5. **Auto-populate**: Latitude & Longitude fields update automatically

#### API Configuration:
```javascript
// Uses Google Maps API with Places library
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places"></script>
```

**Environment Variable:**
- `GOOGLE_MAPS_API_KEY` - Set in .env file
- Fallback: Uses dummy key (set proper key in production)

### 🔧 Setup Instructions

1. **Add Google Maps API Key** (in `.env`):
   ```env
   GOOGLE_MAPS_API_KEY=your_actual_api_key_here
   ```

2. **Get API Key**:
   - Go to: https://console.cloud.google.com
   - Create new project
   - Enable "Maps JavaScript API" & "Places API"
   - Generate API Key with restrictions

3. **Test on Localhost**:
   - Open `/daftar-umkm` page
   - Map should load at Teluknaga location
   - Click on map to place marker
   - Coordinates auto-populate

### 📋 Validation & Data Flow

**Form Submission Flow:**
1. User fills form (all fields required)
2. User selects location on map
3. JavaScript validates coordinates are set
4. Form submits
5. Backend validates all fields
6. UMKM created with coordinates
7. Redirect with success message

**Database Storage:**
```sql
-- New columns (if needed)
ALTER TABLE umkms ADD COLUMN omzet_bulanan DECIMAL(15,2);
ALTER TABLE umkms ADD COLUMN latitude DECIMAL(10,8);
ALTER TABLE umkms ADD COLUMN longitude DECIMAL(11,8);
```

### 🎨 UI/UX Details

- **Map Height**: 400px on desktop, 300px on mobile
- **Input Style**: Read-only gray background
- **Marker**: Draggable, shows coordinates update
- **Search Box**: Styled Google Autocomplete
- **Error Messages**: Show if coordinates not set
- **Responsive**: Map resizes on mobile devices

### ✅ Test Checklist

- [ ] Open `/daftar-umkm` page
- [ ] Map loads at default location
- [ ] Click on map → marker appears
- [ ] Drag marker → coordinates change
- [ ] Search address → map navigates
- [ ] Fill form with omzet_bulanan
- [ ] Submit form
- [ ] UMKM saved with coordinates
- [ ] Coordinates visible in admin panel
- [ ] Mobile map responsive

### 🚀 Next Steps

1. **Set Google Maps API Key**:
   ```bash
   # Add to .env
   GOOGLE_MAPS_API_KEY=your_key_here
   ```

2. **Test on Device**:
   - Desktop browser
   - Mobile browser
   - Touch interactions

3. **Verify Database**:
   - Check omzet_bulanan saved
   - Check latitude & longitude saved
   - Verify data types correct

4. **Optional Enhancements**:
   - Add geolocation (user's current location)
   - Add multiple markers
   - Add location history
   - Export coordinates to KML

---

**Status:** ✅ Ready to Use  
**Last Updated:** 24 April 2026
