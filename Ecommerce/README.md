# PiLUS - Free Open Source E-commerce Solution
#==========================================#

				  PiLUS
     Free Open Source E-commerce Software

	  Contact    : webdev@kartatopia.com
      Source     : http://getpilus.com
      
#==========================================#

A. Apa itu PiLUS ?
-------------------------------------------
PiLUS adalah web based online shop management system, yaitu aplikasi berbasis web untuk mengelola toko online. 
Untuk menggunakan dan menjalankan PiLUS diperlukan server web dengan PHP dan MySQL. 
PiLUS merupakan aplikasi sumber terbuka(Open Source) dan gratis(Free) yang didistribusikan dibawah lisensi GPL versi 3. 

B. PERSYARATAN SISTEM :
-------------------------------------------
1. Server Web
2. PHP versi >= 5.1.2 
3. MySQL >= 5.x.x
4. Mod Rewrite aktif
5. PDO MySQL On


C. INSTALASI PILUS DI KOMPUTER(PC):
-----------------------------------------------------
1. Buatlah folder di direktori web anda. 
   
   contoh:
   --> xampp = C:/xampp/htdocs/namafolder
   --> wampp = C:/wampp/www/namafolder
   
2. Ekstrak file PiLUS-x.x.x.zip pada folder yang telah anda buat sebelumnya 
   
3. Buat Basis data(database) melalui phpMyAdmin.

4. Import databasenya(pilus.sql) ke database yang telah Anda buat sebelumnya.

5. Lakukan pengaturan pada PiLUS supaya dapat terhubung dengan basis data.   
   5.1 File pengaturan bisa anda temukan di folder PiLUS --> core/setting.php :
   5.2 Setelah anda rename, maka lakukan pengaturan seperti contoh berikut:
       
       // Database credential
       define('PL_DBTYPE', '');  --> ketikkan type databasenya MySQL
       define('PL_DBHOST', '');  --> ketikkan database host dengan localhost
       define('PL_DBUSER', '');  --> ketikkan database user anda
       define('PL_DBPASS', '');  --> ketikkan password database anda jika anda menggunkan password
       define('PL_DBNAME', '');  --> ketikkan nama database yang telah anda buat
       
       // Site configuration
       define('PL_DIR', ''); --> ketikkan lokasi URL (http://localhost/namafolder/) atau (http://namadomain)
     
       define('PL_SITEEMAIL', ''); -->ganti dengan alamat email anda
     
6. Halaman Utama Toko Online Anda, yaitu Front Store dapat diakses pada browser, 
   ketikkan http://localhost/pilus 
   
7. Sedangkan untuk masuk ke halaman Back Store, 
   ketik alamat berikut http://localhost/pilus/cabin, 
   --> Isikan nama pengguna = admin dan kata sandi = admin123

8. Pastikan rewrite_module = On

9. Selamat Mencoba!
