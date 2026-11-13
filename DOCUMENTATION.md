# 🏠 EmläkGO — Backend & Dolandyryş Paneli Doly Gözükdirmesi (Documentation)

Bu resminama **EmläkGO** platformasynyň backend gurluşy, API nokatlary, dolandyryş paneli (Admin & Ulanyjy), howpsuzlyk ulgamy we soňky girizilen ähli sazlamalar barada giňişleýin maglumat berýär.

---

## 📌 Mazmuny
1. [Taslama Barada Umumy Maglumat](#1-taslama-barada-umumy-maglumat)
2. [Ulanylan Tehnologiýalar](#2-ulanylan-tehnologiýalar)
3. [Roller we Rugsat Ulgamy (RBAC)](#3-roller-we-rugsat-ulgamy-rbac)
4. [Dolandyryş Paneli (Blade & Tailwind)](#4-dolandyryş-paneli-blade--tailwind)
5. [Suratlar we Faýl Ulgamy](#5-suratlar-we-faýl-ulgamy)
6. [API Nokatlarynyň Sanawy (REST API)](#6-api-nokatlarynyň-sanawy-rest-api)
7. [Taslamany Gurnamak we Işletmek](#7-taslamany-gurnamak-we-işletmek)

---

## 1. Taslama Barada Umumy Maglumat

**EmläkGO** — Türkmenistanda emläk algy-satgysyny, kärendesine bermek we gözlemek işlerini aňsatlaşdyrýan platforma.
Taslama iki esasy bölekden ybarat:
- **Backend (Laravel):** Maglumatlar binýady, REST API, autentifikasiýa, surat ýüklemek we içerki Dolandyryş Paneli (Blade).
- **Frontend (Vue 3 + Tailwind CSS + Vite):** Ulanyjylar üçin döwrebap we responsive web portaly.

---

## 2. Ulanylan Tehnologiýalar

- **PHP 8.2+ / Laravel 13** — Backend freýmwork
- **PostgreSQL / MySQL / SQLite** — Maglumatlar binýady
- **Tailwind CSS** — Dolandyryş paneliniň we frontend-iň dizaýny
- **Custom ApiToken / Bearer Auth** — Howpsuz REST API awtorizasiýasy
- **Laravel Storage (public disk)** — Emläk suratlaryny saklamak

---

## 3. Roller we Rugsat Ulgamy (RBAC)

Ulgamda iki hili ulanyjy roly bar: `admin` we `user`.

```
                ┌──────────────────────────────────┐
                │          EmläkGO Ulgamy          │
                └─────────────────┬────────────────┘
                                  │
                 ┌────────────────┴────────────────┐
                 ▼                                 ▼
         ┌───────────────┐                 ┌───────────────┐
         │     Admin     │                 │ Adaty Ulanyjy │
         └───────┬───────┘                 └───────┬───────┘
                 │                                 │
     • Ähli emläkleri görýär           • Diňe öz emläklerini görýär
     • Tassyklaýar / Ret edýär         • Tassyklap/ret edip bilmeýär
     • Ulanyjylary dolandyrýar         • Ulanyjylar sahypasyna girip bilmeýär (403)
     • Ähli soraglary görýär           • Diňe öz emlägine gelen soraglary görýär
```

### A. Admin Ygtyýarlyklary:
- Platformadaky **ähli ulanyjylaryň** goýan emläklerini görmek.
- Täze goşulan emläkleri barlap **Tassyklamak** (`active`) ýa-da **Ret etmek** (`rejected`).
- Ähli ulanyjylaryň sanawyny görmek we zerur bolsa ulanyjyny pozmak (`/admin/users`).
- Platformanyň ähli umumy statistikasyny (jemi ulanyjy, jemi emläk, ähli soraglar) görmek.

### B. Adaty Ulanyjy (User) Ygtyýarlyklary:
- **Diňe öz goýan emläklerini** dolandyrmak (`where('user_id', $user->id)`).
- Başga ulanyjylaryň goşan emläkleri we maglumatlary dolandyryş panelinde **görkezilmeýär**.
- Emläkleri tassyklamak ýa-da ret etmek ygtyýary **ýok** (bu düwmeler gizlenen we serwer tarapynda `abort(403)` bilen goralan).
- **"Ulanyjylar"** sahypasyna girmäge rugsat ýok — menýudan aýrylan we göni URL ýazylsa `403 Forbidden` ýalňyşlygyny berýär.
- **Soraglar sahypasynda** diňe öz goýan emläklerine müşderilerden gelen soraglar görkezilýär.

---

## 4. Dolandyryş Paneli (Blade & Tailwind)

### 🎨 Reňk Sazlamasy (Goýy Ýaşyl Tema):
Panel öňki gök reňkden doly arassalanyp, frontend bilen birmeňzeş bolan **Goýy Ýaşyl (Emerald #065f46 / #047857)** reňke geçirildi:
- Ýokarky Navbar: `bg-emerald-800 border-b border-emerald-900`
- Aktiw menýu düwmeleri: `bg-emerald-700 text-white`
- Statistika kartlary: Ýaşyl, pöwrize (teal) we gyzyl çäkler bilen döwrebaplaşdyryldy.
- Giriş sahypasy (`login.blade.php`): Goýy ýaşyl gradient we ýaşyl giriş düwmesi.

### 🖱️ Basylýan Statistika Kartlary (Clickable Dashboard Cards):
Baş sahypadaky kartlaryň ählisi işjeň edildi:
1. **Meniň Emläklerim (ýa-da Jemi Emläkler):**
   - Karta basylanda ➔ Ähli emläkler sanawyna geçýär (`/admin/properties`).
   - Aşagyndaky **"Garaşylýar"**, **"Aktif"**, **"Ret"** sanlaryna basylanda ➔ degişli status boýunça awtomatiki süzüp açýar.
2. **Emläklerime Gelen Soraglar:**
   - Karta basylanda ➔ Müşderileriň ýazan hatlarynyň sanawyna geçýär (`/admin/inquiries`).
   - Ol ýerde müşderiniň ady, email salgysy, telefon belgisi (bir basyşda jaň edip bolýar) we haty görkezilýär.
3. **Emläklerimiň Halanan Sany:**
   - Karta basylanda ➔ Halananlar sanawyna geçýär (`/admin/favorites`).
   - Kimiň haýsy emlägi halandygyny aýdyň görkezýär.
4. **Ulanyjylar (Diňe Admin üçin):**
   - Karta basylanda ➔ Ulanyjylar sanawyna geçýär (`/admin/users`).

---

## 5. Suratlar we Faýl Ulgamy

### A. Saklanyş Düzgüni:
- Ulanyjy täze emläk goşanda suratlar `storage/app/public/properties/` bukjasynda unikal at bilen ýerleşdirilýär.
- Public elýeterlilik üçin `php artisan storage:link` arkaly `public/storage` arabaglanyşygy döredildi.

### B. Ýalňyşlyklaryň Öňüni Alyş:
- **`placeholder.jpg`:** Maglumatlar binýadynda suratsyz döredilen ilkinji synag emläkleri üçin ätiýaçlyk `placeholder.jpg` suraty elýeterli edildi.
- **Frontend Galereýa:** Boýy `h-72 sm:h-96 md:h-[420px]` bilen çäklendirildi, aşa uly bolup ekrany tutmaýar.
- **Slaýder:** Birnäçe surat bar bolsa, üstündäki **❮ ❯** düwmeleri we aşagyndaky kiçi suratlar (thumbnails) arkaly amatly aýlap bolýar.
- **Fallback (`@error`):** Islendik ýagdaýda surat faýly bozulsa ýa-da ýitse, ulgam döwük şekil görkezmän, awtomatiki arassa jaý suratyny açýar.

---

## 6. API Nokatlarynyň Sanawy (REST API)

Ähli API nokatlary `/api/...` prefiksi bilen gurnalandyr:

| Usul (Method) | URL Salgysy | Düşündiriş | Rugsat (Auth) |
|---|---|---|---|
| `POST` | `/api/auth/register` | Täze ulanyjy agza bolmak | Açyk |
| `POST` | `/api/auth/login` | Ulgama girmek we Bearer token almak | Açyk |
| `POST` | `/api/auth/logout` | Ulgamdan çykmak | Token gerek |
| `GET` | `/api/auth/me` | Hasap maglumatlaryny almak | Token gerek |
| `GET` | `/api/categories` | Ähli kategoriýalar we sany | Açyk |
| `GET` | `/api/properties` | Süzgüçli emläkler sanawy (city, rooms, min/max price) | Açyk |
| `GET` | `/api/properties/{id}` | Emlägiň doly maglumatlary we suratlary | Açyk |
| `POST` | `/api/properties` | Täze emläk goşmak (multipart/form-data) | Token gerek |
| `PUT` | `/api/properties/{id}` | Emlägi üýtgetmek (diňe eýesi ýa-da admin) | Token gerek |
| `DELETE` | `/api/properties/{id}` | Emlägi pozmak (diňe eýesi ýa-da admin) | Token gerek |
| `POST` | `/api/properties/{id}/inquiries` | Emläk eýesine sorag/hat ibermek | Açyk |
| `GET` | `/api/favorites` | Ulanyjynyň halanan emläkleri | Token gerek |
| `POST` | `/api/properties/{id}/favorite` | Emlägi halananlara goşmak / aýyrmak | Token gerek |

---

## 7. Taslamany Gurnamak we Işletmek

### 1. Backend-i işletmek:
```bash
# EmläkGO - backend bukjasyna geçiň
cd "C:\Users\Notebook Market\Desktop\EmläkGO - backend"

# Maglumatlar binýady migrasiýasy (zerur bolsa)
php artisan migrate

# Storage link baglanyşygyny barlamak
php artisan storage:link

# Local serweri işletmek (Port 8000)
php artisan serve
```

### 2. Frontend-i işletmek:
```bash
# EmläkGO - Frontend bukjasyna geçiň
cd "C:\Users\Notebook Market\Desktop\EmläkGO - Frontend"

# Bagly paketleri barlamak
npm install

# Ösüş serwerini işletmek (Port 5173)
npm run dev
```

### 3. Çalt Geçiş Salgylary:
- **Frontend Web Portaly:** `http://localhost:5173`
- **Dolandyryş Paneli (Admin & Ulanyjy):** `http://localhost:8000/admin`
- **Panel Giriş Sahypasy:** `http://localhost:8000/login`
- **API Baş Salgysy:** `http://localhost:8000/api`

---
*Taýýarlanyldy: EmläkGO Taslama Topary (Kuwwadow Hojanepes) — 2026*
