"C:\Program Files\7-Zip\7z.exe" e .\hecart.7z

E:\nginx\mysql\bin\mysql.exe -h localhost -uroot -p"123456" --default-character-set=utf8 -e "DROP DATABASE hecart;"

E:\nginx\mysql\bin\mysql.exe -h localhost -uroot -p"123456" --default-character-set=utf8 -e "CREATE DATABASE hecart CHARACTER SET UTF8 COLLATE UTF8_GENERAL_CI;"

E:\nginx\mysql\bin\mysql.exe -h localhost -uroot -p"123456" --default-character-set=utf8 hecart < .\hecart.sql

del /Q .\hecart.sql
