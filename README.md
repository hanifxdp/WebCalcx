# Columnar & Diagonal Cipher - Cryptography

## Columnar Cipher

melibatkan penulisan teks biasa dalam baris, dan kemudian membaca teks tersandi dalam kolom satu per satu.

### Contoh enkripsi

PT : ikan hiu makan tomat
K : ernec

<img src="/src/ss-1.png">

Maka,
CT : \_m_tihatn_naauamkiko

### Contoh dekripsi

CT : \_m_tihatn_naauamkiko
K : ernec

<img src="/src/ss-2.png">

Maka,
PT :
ikan_hiu_makan_tomat

---

## Diagonal Cipher
Menyusun plaintext pada kolom dengan pola diagonal.

### Contoh
PT : belajaralgoritmakriptografi
K : 6

<img src="/src/ss-3.png">

Maka,
CT : berlaialtijgmpaaoatfxrkoixrxxxxx

