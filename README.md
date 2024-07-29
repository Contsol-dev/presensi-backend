<h1> API Documentation </h1>


# POST /register
register user (pemagang) baru <br>
<b>body:</b>
```
{
    "username": "contsoldev",
    "email": "contsolhelp@gmail.com",
    "password": "admin#1234",
    "konfirm_password": "admin#1234",
    "nama": "Cont Solutions Indonesia",
    "nomor_hp": "08833351344",
    "tempat_lahir": "Sleman",
    "tanggal_lahir": "2024-07-13",
    "asal_sekolah": "UPN Veteran Yogyakarta"
}
```
<b>Response berhasil:</b>
```
{
    "success": true,
    "message": "register berhasil"
}
```

# POST /login
<b>body:</b>
```
{
    "email": "contsolhelp@gmail.com",
    "password": "admin#1234"
}
```
<b>response sukses:</b>
```
{
    "success": true,
    "message": "login sukses",
    "username": "contsoldev",
    "nama": "Cont Solutions Indonesia"
}
```

# GET /logout
<b>response sukses:</b>
```
{
    "success": true,
    "message": "logout berhasil"
}
```

# POST /reset-password
mengirim permintaan reset password. mengirim email otomatis ke email yang diinputkan
<b>body:</b>
```
{
    "email": "contsolhelp@gmail.com"
}
```
<b>response:</b>
```
{
    "message": "Request token reset password berhasil dibuat."
}
```

# GET /reset-password/{token}
request dari link yg dikirim melalui email.

# POST /log-baru
Membuat row baru di tabel logs <br>
<b>Body:</b>
```
{
    "username": "contsoldev",
    "tanggal": "2024-05-07" // format: YYYY-MM-DD
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Log entry created successfully",
    "log": {
      "username": "contsoldev",
      "tanggal": "2024-05-07"
    }
}
```

# POST /presensi-masuk
mengupdate checkpoint jam presensi <br>
<b>Body:</b>
```
{
    "username": "contsoldev",
    "tanggal": "2024-05-07",
    "masuk": "11:42:00" //format: hh:mm:ss
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Sukses update jam masuk",
    "masuk": "11:42:00",
    "next": "istirahat"
}
```

# POST /presensi-istirahat
mengupdate checkpoint jam istirahat <br>
<b>Body:</b>
```
{
    "username": "contsoldev",
    "tanggal": "2024-05-07",
    "istirahat": "11:42:00" //format: hh:mm:ss
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Sukses update jam istirahat",
    "istirahat": "11:42:00",
    "next": "kembali"
}
```

# POST /presensi-kembali
mengupdate checkpoint jam kembali dari istirahat <br>
<b>Body:</b>
```
{
    "username": "contsoldev",
    "tanggal": "2024-05-07",
    "kembali": "11:42:00" //format: hh:mm:ss
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Sukses update jam kembali",
    "masuk": "11:42:00",
    "next": "pulang"
}
```

# POST /presensi-pulang
mengupdate checkpoint jam pulang <br>
<b>Body:</b>
```
{
    "username": "contsoldev",
    "tanggal": "2024-05-07",
    "pulang": "11:42:00" //format: hh:mm:ss
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Sukses update jam pulang",
    "masuk": "11:42:00",
    "next": ""
}
```

# POST /log-activity
menambah logbook harian <br>
<b>Body:</b>
```
{
    "username": "contsoldev",
    "tanggal": "2024-05-07",
    "log_activity": "menambahkan 5 endpoint dan mengupdate migration",
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Sukses menambahkan log activity harian",
    "log_activity": "menambahkan 5 endpoint dan mengupdate migration"
}
```

# POST /kebaikan
menambah kebaikan harian <br>
<b>Body:</b>
```
{
    "username": "contsoldev",
    "tanggal": "2024-05-07",
    "kebaikan": "apa gitu hal yang sekiranya baik",
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Sukses menambahkan log activity harian",
    "kebaikan": "apa gitu hal yang sekiranya baik"
}
```