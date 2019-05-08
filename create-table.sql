create table [dbo].[Hewan](
    id INT NOT NULL IDENTITY(1,1) PRIMARY KEY(id),
    nama VARCHAR(50),
    nama_ilmiah VARCHAR(50),
    kelas VARCHAR(50),
    date DATE
);