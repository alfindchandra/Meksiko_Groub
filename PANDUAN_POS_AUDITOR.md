# Panduan Halaman POS Auditor

## Fitur yang Telah Ditambahkan

Halaman **POS Auditor** tersedia di `/auditor/pos` dengan fitur-fitur berikut:

### 1. **Pilih Audit**
- Tampilkan daftar audit yang tersedia
- Setiap audit menampilkan:
  - Nomor audit (contoh: AUD-20260202-001)
  - Nama produk yang diaudit
  - Jumlah uang yang seharusnya diterima
  - Nama outlet

### 2. **Konfirmasi Titip Audit (Tahap 1)**
Setelah memilih audit, auditor akan melihat:

**Input Titip:**
- Kolom untuk memasukkan jumlah uang yang dititipkan
- Dalam kurung ditampilkan jumlah yang seharusnya (Expected)

**Display Kekurangan:**
- Jika ada kekurangan, ditampilkan kotak merah dengan nominal kekurangannya
- Jika titip sudah lengkap, ditampilkan kotak hijau dengan status "Audit Lengkap ✓"

**Tombol Konfirmasi:**
- Tombol "Konfirmasi Titip" (warna hijau)
- Setelah diklik, sistem akan:
  - Menyimpan jumlah titip ke database
  - Menghitung shortage (kekurangan)
  - Mengubah status deposit menjadi "confirmed"
  - Membuka tahap 2: Input barang yang laku

### 3. **Input Barang yang Laku (Tahap 2)**
Setelah titip dikonfirmasi, auditor dapat:

**Cari & Tambah Produk:**
- Pencarian real-time saat mengetik (minimal 2 karakter)
- Hasil pencarian berdasarkan nama atau SKU produk
- Klik produk untuk menambahkan ke daftar

**Manage Barang:**
- Ubah quantity dengan tombol + dan -
- Hapus item dari daftar
- Lihat total harga real-time

**Input Catatan:**
- Text area untuk menambahkan catatan barang yang laku (opsional)

### 4. **Simpan Barang yang Laku (Tombol Final)**
- Tombol "Simpan Barang yang Laku" (warna biru)
- Setelah diklik:
  - Membuat Sale/Penjualan baru dengan nomor AUDIT-[timestamp]
  - Mencatat setiap item barang yang laku
  - Mengurangi stok di sistem
  - Menyimpan informasi ke database audit
  - Redirect ke halaman receipt/struk

### 5. **Info Audit (Sidebar)**
Menampilkan info tetap:
- Nomor audit
- Nama produk
- Nama outlet
- Tombol "Kembali" untuk memilih audit lain

## Data yang Disimpan ke Database

Ketika fitur ini digunakan, data berikut disimpan ke tabel `audits`:

```
- deposit_amount: Jumlah uang yang dititipkan
- deposit_date: Tanggal dan waktu konfirmasi titip
- deposit_status: Status titip (pending/confirmed)
- shortage_amount: Jumlah kekurangan
- sold_items_count: Jumlah item barang yang laku
- sold_amount: Total harga barang yang laku
- sold_status: Status penjualan (pending/completed)
```

Dan membuat `Sale` baru dengan items di tabel `sales` dan `sale_items`.

## Cara Mengakses

1. Login sebagai auditor
2. Buka URL: `/auditor/pos`
3. Pilih salah satu audit dari daftar
4. Ikuti tahapan sesuai panduan di atas

## File yang Dimodifikasi/Dibuat

**File Baru:**
- `app/Livewire/Pos/AuditorPos.php` - Component Livewire
- `resources/views/livewire/pos/auditor-pos.blade.php` - View
- `database/migrations/2026_06_05_000000_add_auditor_pos_fields_to_audits_table.php` - Migration

**File yang Dimodifikasi:**
- `routes/web.php` - Ditambahkan route untuk halaman POS auditor
- `app/Models/Audit.php` - Ditambahkan fillable dan casts fields baru

## Notes Teknis

- Validasi dilakukan di frontend (Livewire) dan backend
- Stock diupdate otomatis setelah penjualan disimpan
- Menggunakan transaksi database untuk memastikan data consistency
- Tidak perlu user intervensi manual, semua terotomasi
