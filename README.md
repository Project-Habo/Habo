# Habo
Πρότζεκτ Τεχνολογίας Λογισμικού - Έτος 4ο, Εξάμηνο 8ο

## Κλάσσεις του πρότζεκτ
- Οι κλάσσεις που ανταποκρίνονται στο Class Diagram βρίσκονται στο φάκελο `includes/classes`

## Οδηγίες εκτέλεσης
1. Εγκατάσταση του προγράμματος **XAMPP**(https://www.apachefriends.org/index.html).
2. Στο path που εγκαταστήθηκε το πρόγραμμα, μεταβείτε στον φάκελο `htdocs` και δημιουργήστε φάκελο με ονομασία `habo`(!!!). Για παράδειγμα `C:\xampp\htdocs\habo`.
3. Κάντε αποσυμπίεση του κώδικα του πρότζεκτ στο παραπάνω path.
Στο σημείο αυτό ο φάκελος `habo` θα πρέπει να έχει την παρακάτω δομή.
* habo
  * *assets*
  * *config*
  * *includes*
    * *classes*
    * *form_handlers*
    * *handlers*
  * .htaccess
  * admin_test.php
  * database.sql
  * groups_test.php
  * index.php
  * login.php
  * profile.php
  * register.php
  
4. Κατεβάστε το SQL αρχείο `habo.sql`.
5. Ξεκινήστε το πρόγραμμα XAMPP και επιλέξτε "Start" στις επιλογές **Apache** και **MySQL**.
6. Πληκτρολογείστε στον browser τη διεύθυνση `localhost/phpmyadmin`.
7. Απο την αριστερή στήλη δημιουργήστε μια νέα βάση δεδομένων με ονομασία `habo`(!!!) και κάντε "Import" το SQL αρχείο που κατεβάσατε στο βήμα 4.
8. Πληκτρολογείστε στον browser τη διεύθυνση `localhost/habo/login.php`.
9. Καντε login με email: `test10@email.com` και password: `12345`

