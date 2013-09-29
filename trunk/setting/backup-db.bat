"E:\nginx\mysql\bin\mysqldump.exe" -h localhost -uroot -p"123456" --opt -R --default-character-set=utf8 hecart > .\hecart.sql

"C:\Program Files\7-Zip\7z.exe" a .\hecart.7z .\hecart.sql

del /Q .\hecart.sql
