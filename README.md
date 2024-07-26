<h1> API Documentation </h1>

# POST /log-baru
Membuat row baru di tabel logs <br>
<b>Body:</b>
```
{
    "username": "johndoe",
    "tanggal": "2024-05-07" // format: YYYY-MM-DD
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Log entry created successfully",
    "log": {
      "user_id": 1,
      "tanggal": "2024-05-07"
    }
}
```

# POST /presensi-masuk
mengupdate checkpoint jam presensi <br>
<b>Body:</b>
```
{
    "user_id": "1",
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
    "user_id": "1",
    "tanggal": "2024-05-07",
    "istirahat": "11:42:00" //format: hh:mm:ss
}
```
<b>Response Berhasil:</b>
```
{
    "message": "Sukses update jam istirahat",
    "masuk": "11:42:00",
    "next": "kembali"
}
```

# POST /presensi-kembali
mengupdate checkpoint jam kembali dari istirahat <br>
<b>Body:</b>
```
{
    "user_id": "1",
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
    "user_id": "1",
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

# POST /kebaikan

# POST /register

# POST /login

# GET /logout

# POST /reset-password

# GET /reset-password/{token}